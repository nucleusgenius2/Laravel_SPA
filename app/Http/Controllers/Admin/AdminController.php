<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;


use App\Models\Post;
use App\Traits\ResponseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AdminController
{
    use ResponseController;

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


    /**
     * @param object $img
     * @return array
     */
    protected function uploaderImg(object $img): array
    {
        $imageReturn = [
            'img' =>'',
            'status' => 'error',
            'text' => ''
        ];

        $validated = Validator::make(['img' => $img], [
            'img' => 'image|mimes:png,jpg,jpeg',
        ]);

        if ($validated->fails()) {
            $imageReturn['text'] = $validated->errors();
        } else {
            $data = $validated->valid();

            //upload img in dir
            $imageName = time() . '.' . $data['img']->extension();
            $data['img']->move(public_path('images'), $imageName);

            //name img for bd
            $imageReturn['img'] = '/images/' . $imageName;

            $imageReturn['status'] = 'success';
        }

        return $imageReturn;
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

            if (gettype($data['img']) == 'object') {
                $imageName = $this->uploaderImg($data['img']);
            }
            else {
                $imageName = $data['img'];
            }

            $arraySavePost = [
                'name' => $data['name'],
                'content' => $data['content'] ?? '',
                'short_description' => $data['short_description'] ?? '',
                'seo_title' => $data['seo_title'] ?? '',
                'seo_description' => $data['seo_description'] ?? '',
                'img' => $imageName['img'] ?? '',
                'category_id' => $data['category_id'] ?? 0,
                'author' => $data['author']
            ];

            $post = Post::create($arraySavePost);

            if ($post) {
                $this->status = 'success';
                $this->code = 200;
                $this->text = 'Запись создана';
                $this->json = $post->id;
            }
            else {
                $this->text = 'Запись не была создана';
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
                $imageName = $this->uploaderImg($data['img']);

                if ( $imageName['status'] !='success'){
                    return $this->responseJsonApi();
                }
            }
            else {
                $imageName['img'] = $data['img'];
            }

            $validated = Validator::make(['img'=>$imageName['img']], [
                'img' => 'nullable|string',
            ]);

            if ($validated->fails()) {
                $this->text = $validated->errors();
            } else {
                $arraySavePost = Post::where('id', '=', $data['id'])->update([
                    'name' => $data['name'],
                    'content' => $data['content'] ?? '',
                    'short_description' => $data['short_description'] ?? '',
                    'seo_title' => $data['seo_title'] ?? '',
                    'seo_description' => $data['seo_description'] ?? '',
                    'img' => $imageName['img'] ?? '',
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
}


