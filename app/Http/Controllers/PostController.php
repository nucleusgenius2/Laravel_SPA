<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\UserLogin;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseController;

class PostController
{
    use ResponseController;


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
    public function index(Request $request): JsonResponse
    {
        $validated = Validator::make(['page' => $request->page], [
            'page' => 'required|integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $postList = Cache::remember('post_index_page_'.$data['page'], Post::cashSecond, function () use ($data) {
                return Post::orderBy('id', 'desc')->paginate(10, ['*'], 'page', $data['page']);
            });

            if (count($postList) > 0) {
                $this->status = 'success';
                $this->code = 200;
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
}
