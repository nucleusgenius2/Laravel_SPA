<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


class PostController
{

    /**
     * returns a list of news with pagination or single news
     * @param int $total
     * @return JsonResponse
     */
    public function getPostList(int $total): JsonResponse
    {
        $array_news = Post::orderBy('id', 'desc')->paginate($total);
        if ($array_news) {
            $code = 200;
        }
        else {
            $code = 404;
        }
        return response()->json($array_news,  $code);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getPostSingle(int $id): JsonResponse
    {
        $array_news = Post::where('id', '=', $id)->get();
        if ($array_news) {
            $code = 200;
        }
        else {
            $code = 404;
        }
        return response()->json($array_news,  $code);
    }
}
