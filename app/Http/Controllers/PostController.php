<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\JsonResponse;


class PostController
{

    /**
     * returns a list of news with pagination or single news
     * @param string $total
     * @param string $id
     * @return JsonResponse
     */
    public function getPostListOrSinglePost(string $total, string $id): JsonResponse
    {
        if ($total != '1') {
            $array_news = News::orderBy('id', 'desc')->paginate($total);
        } else {
            $array_news = News::where('id', '=', $id)->get();
        }
        return response()->json($array_news, 200);
    }
}
