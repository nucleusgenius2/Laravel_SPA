<?php

namespace App\Services;

use App\DTO\DataPaginatorDTO;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PostService
{
    public function getPosts(array $data, Post $modelPost, int $perPage): DataPaginatorDTO
    {

        if ( isset($data['name']) || isset($data['created_at_to']) || isset($data['created_at_from']) || isset($data['date_fixed']) ){
            $query = $modelPost->filterCustom($data);

            $postList = $query->orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $data['page']);
        }
        else{
            $postList = Cache::remember('post_index_page_'.$data['page'], Post::cashSecond, function () use ($data, $perPage) {
                return Post::orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $data['page']);
            });
        }

        if ($postList->isNotEmpty()) {
            return new DataPaginatorDTO(status: true, data: $postList);
        }
        else{
            return new DataPaginatorDTO(status: false);
        }

    }
}
