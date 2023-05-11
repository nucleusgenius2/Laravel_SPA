<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\UserLogin;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;


class PostController
{
    use ResponseController;

    /**
     * returns a list of news with pagination or single news
     * @param int $pagination
     * @return JsonResponse
     */
    public function getPostList(int $pagination): JsonResponse
    {
        $validated = Validator::make(['page' => $pagination], [
            'page' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $postList = Post::orderBy('id', 'desc')->paginate(10, ['*'], 'page', $data['page']);

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
     * @param int $id
     * @return JsonResponse
     */
    public function getPostSingle(int $id): JsonResponse
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $contentPostSingle = Post::where('id', '=', $data['id'])->get();

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
