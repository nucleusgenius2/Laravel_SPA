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


}


