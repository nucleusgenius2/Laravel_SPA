<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Traits\StructuredResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\UploadsImages;
class PostController
{
    use StructuredResponse, UploadsImages;


    /**
     * @OA\Get(
     *  path="/api/posts",
     *  summary="Get list of posts",
     *  description="Pass the page number",
     *  operationId="postsGet",
     *  tags={"post"},
     *  @OA\Parameter(
     *        description="number page",
     *        in="query",
     *        name="page",
     *        required=true,
     *        example="1",
     *   ),
     *  @OA\Response(
     *    response=200,
     *    description="Array of posts received",
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="json", type="array", @OA\Items(
     *            @OA\Property(
     *                property="current_page",
     *                type="integer",
     *                example=1
     *            ),
     *            @OA\Property(
     *                property="data",
     *                type="array",
     *                @OA\Items(
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                       property="name",
     *                       type="string",
     *                       example="Тестовая новость"
     *                   ),
     *                   @OA\Property(
     *                        property="content",
     *                        type="string",
     *                        example="Содержимое новости"
     *                    ),
     *                    @OA\Property(
     *                         property="short_description",
     *                         type="string",
     *                         example="Краткое описание новости"
     *                     ),
     *                     @OA\Property(
     *                          property="seo_title",
     *                          type="string",
     *                          example="заголовок для сео"
     *                      ),
     *                      @OA\Property(
     *                           property="seo_description",
     *                           type="string",
     *                           example="описание для сео"
     *                      ),
     *                      @OA\Property(
     *                            property="img",
     *                            type="string",
     *                            example="/images/1680444267.png"
     *                       ),
     *                       @OA\Property(
     *                             property="category_id",
     *                             type="integer",
     *                             example=1
     *                       ),
     *                       @OA\Property(
     *                              property="author",
     *                              type="integer",
     *                              example="Вася"
     *                       ),
     *                       @OA\Property(
     *                               property="created_at",
     *                               type="string",
     *                               example="2024-05-27T19:19:59.000000Z"
     *                       ),
     *                       @OA\Property(
     *                               property="updated_at",
     *                               type="string",
     *                               example="2024-05-27T19:19:59.000000Z"
     *                        ),
     *                )
     *            ),
     *            @OA\Property(
     *                 property="first_page_url",
     *                 type="string",
     *                 example="http://localhost/api/posts?page=1"
     *             ),
     *             @OA\Property(
     *                  property="from",
     *                  type="integer",
     *                  example=1
     *             ),
     *             @OA\Property(
     *                   property="last_page",
     *                   type="integer",
     *                   example=1
     *              ),
     *              @OA\Property(
     *                    property="last_page_url",
     *                    type="string",
     *                    example="http://localhost/api/posts?page=1"
     *               )
     *        )
     *      ),
     *    )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity",
     *      @OA\JsonContent(
     *         @OA\Property(property="text", type="string", example="Запрашиваемой страницы не существует"),
     *         @OA\Property(property="status", type="string", example="error"),
     *         @OA\Property(property="json", type="null")
     *       )
     *  )
     * )
     */
    public function index(Request $request, Post $post): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'page' => 'required|integer|min:1',
            'created_at_from' => 'string|date',
            'created_at_to' => 'string|date',
            'name' => 'string|min:1|max:50',
            'date_fixed' => 'string|in:day,week,month,year',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            if ( isset($data['name']) || isset($data['created_at_to']) || isset($data['created_at_from']) || isset($data['date_fixed']) ){
                $query = $post->filterCustom($data);

                $postList = $query->orderBy('id', 'desc')->paginate(10, ['*'], 'page', $data['page']);
            }
            else{
                $postList = Cache::remember('post_index_page_'.$data['page'], Post::cashSecond, function () use ($data) {
                    return Post::orderBy('id', 'desc')->paginate(10, ['*'], 'page', $data['page']);
                });
            }

            $this->code = 200;

            if (count($postList) > 0) {
                $this->status = 'success';
                $this->json = $postList;
            } else {
                $this->text = 'Запрашиваемой страницы не существует';
            }
        }

        return $this->responseJsonApi();
    }


    /**
     * @OA\Get(
     *  path="/api/posts/{post_id}",
     *  summary="Get post",
     *  description="Get single post",
     *  operationId="postGet",
     *  tags={"post"},
     *  @OA\Parameter(
     *        description="post id",
     *        in="path",
     *        name="post_id",
     *        required=true,
     *        example="1",
     *   ),
     *  @OA\Response(
     *    response=200,
     *    description="Array of posts received",
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="json", type="array", @OA\Items(
     *          @OA\Property(
     *              property="id",
     *              type="integer",
     *              example=1
     *          ),
     *          @OA\Property(
     *              property="name",
     *              type="string",
     *              example="Тестовая новость"
     *          ),
     *          @OA\Property(
     *             property="content",
     *              type="string",
     *              example="Содержимое новости"
     *          ),
     *          @OA\Property(
     *             property="short_description",
     *             type="string",
     *             example="Краткое описание новости"
     *          ),
     *          @OA\Property(
     *             property="seo_title",
     *             type="string",
     *             example="заголовок для сео"
     *          ),
     *          @OA\Property(
     *             property="seo_description",
     *             type="string",
     *             example="описание для сео"
     *          ),
     *          @OA\Property(
     *             property="img",
     *             type="string",
     *             example="/images/1680444267.png"
     *          ),
     *          @OA\Property(
     *             property="category_id",
     *             type="integer",
     *             example=1
     *          ),
     *          @OA\Property(
     *             property="author",
     *             type="integer",
     *             example="username"
     *          ),
     *          @OA\Property(
     *             property="created_at",
     *             type="string",
     *             example="2024-05-27T19:19:59.000000Z"
     *          ),
     *          @OA\Property(
     *              property="updated_at",
     *              type="string",
     *              example="2024-05-27T19:19:59.000000Z"
     *           ),
     *        )
     *      ),
     *    )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity",
     *      @OA\JsonContent(
     *         @OA\Property(property="text", type="string", example="Запрашиваемой страницы не существует"),
     *         @OA\Property(property="status", type="string", example="error"),
     *         @OA\Property(property="json", type="null")
     *       )
     *  )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $contentPostSingle = Cache::rememberForever('post_id_'.$data['id'], function () use ($data) {
                return Post::where('id', '=', $data['id'])->get();
            });

            if (count($contentPostSingle) > 0) {
                $this->status = 'success';
                $this->code = 200;
                $this->json = $contentPostSingle;
            } else {
                $this->text = 'Запрашиваемой новости не существует';
            }
        }

        return $this->responseJsonApi();
    }


    /**
     * create post
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'content' => 'nullable|string',
            'short_description' => 'nullable|string|max:300',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'img' => 'nullable|image|mimes:png,jpg,jpeg',
            'category_id' => 'nullable|int',
            'author' => 'required|string|max:100',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $imgUpload = $this->uploadImage($data['img']);
            if ( $imgUpload['status'] =='success' ) {

                $arraySavePost = [
                    'name' => $data['name'],
                    'content' => $data['content'] ?? '',
                    'short_description' => $data['short_description'] ?? '',
                    'seo_title' => $data['seo_title'] ?? '',
                    'seo_description' => $data['seo_description'] ?? '',
                    'img' => $imgUpload['img'] ?? '',
                    'category_id' => $data['category_id'] ?? 0,
                    'author' => $data['author']
                ];

                $post = Post::create($arraySavePost);

                if ($post) {
                    $this->status = 'success';
                    $this->code = 200;
                    $this->text = 'Запись создана';
                    $this->json = $post->id;
                } else {
                    $this->text = 'Запись не была создана';
                }
            }
            else{
                $this->text = $imgUpload['text'];
            }
        }

        return $this->responseJsonApi();
    }

    /**
     * update post
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse

    {
        $validated = Validator::make($request->all(), [
            'id' => 'required|int',
            'name' => 'required|string|min:3|max:255',
            'content' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'category_id' => 'nullable|int',
        ]);


        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            if (gettype($data['img']) == 'object') {
                $imgUpload = $this->uploadImage($data['img']);
            }
            else {
                $imgUpload['img'] = $data['img'];
            }

            if ($validated->fails()) {
                $this->text = $validated->errors();
            } else {
                $arraySavePost = Post::where('id', '=', $data['id'])->update([
                    'name' => $data['name'],
                    'content' => $data['content'] ?? '',
                    'short_description' => $data['short_description'] ?? '',
                    'seo_title' => $data['seo_title'] ?? '',
                    'seo_description' => $data['seo_description'] ?? '',
                    'img' => $imgUpload['img'] ?? '',
                    'category_id' => $data['category_id'] ?? 0,
                ]);

                Cache::forget('post_id_'.$data['id']);

                if ($arraySavePost) {
                    $this->status = 'success';
                    $this->code = 200;
                    $this->text = 'Запись создана';
                }
                else {
                    $this->text = 'Запрашиваемой страницы не существует';
                }
            }
        }

        return $this->responseJsonApi();
    }

    /**
     * @OA\delete(
     *  path="/api/posts/{post_id}",
     *  summary="Destroy post",
     *  description="deleting a post by id",
     *  operationId="postsDestroy",
     *  security={{"sanctum":{}}},
     *  tags={"post"},
     *  @OA\Parameter(
     *        description="post id",
     *        in="path",
     *        name="post_id",
     *        required=true,
     *        example="1",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Array of posts received",
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="json", type="array", @OA\Items() )
     *   ),
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity",
     *      @OA\JsonContent(
     *         @OA\Property(property="text", type="string", example="Запрашиваемого ресурса не существует"),
     *         @OA\Property(property="status", type="string", example="error"),
     *         @OA\Property(property="json", type="null")
     *       )
     *  )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $post = Post::where('id', '=', $data['id'])->delete();

            Cache::forget('post_id_'.$data['id']);

            if ($post) {
                $this->status = 'success';
                $this->code = 200;
            } else {
                $this->text = 'Запрашиваемого ресурса не существует';
            }
        }

        return $this->responseJsonApi();
    }
}
