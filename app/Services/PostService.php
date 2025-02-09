<?php

namespace App\Services;

use App\DTO\DataEmptyDTO;
use App\DTO\DataObjectDTO;
use App\Models\Mod;
use App\Models\Post;
use App\Models\User;
use App\Traits\UploadsImages;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PostService
{
    use UploadsImages;

    public function getPosts(array $data, Post $modelPost, int $perPage): DataObjectDTO
    {
        if ( isset($data['name']) || isset($data['created_at_to']) || isset($data['created_at_from']) || isset($data['date_fixed']) ){
            $query = $modelPost->filterCustom($data);

            $postList = $query->orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $data['page']);
        }
        else{
            if (isset($data['admin'])) {
                $postList = Post::orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $data['page']);
            }
            else {
                $postList = Cache::remember('post_index_page_' . $data['page'], Post::cashSecond, function () use ($data, $perPage) {
                    return Post::orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $data['page']);
                });
            }
        }

        if ($postList->isNotEmpty()) {
            return new DataObjectDTO(status: true, data: $postList);
        }
        else{
            return new DataObjectDTO(status: false);
        }

    }

    public function getPost(int $id): DataObjectDTO
    {

        $postSingle = Cache::rememberForever('post_id_'.$id , function () use ($id) {
            return Post::where('id', '=', $id )->get();
        });

        if ($postSingle) {
            return new DataObjectDTO(status: true, data: $postSingle);
        }
        else{
            return new DataObjectDTO(status: false);
        }

    }

    public function createPost(array $data, User $user): DataObjectDTO
    {
        isset($data['img']) ? $imgUpload = $this->uploadImage($data['img']) : $imgUpload['status'] ='empty';

        if ( $imgUpload['status'] !='error' ) {
            $arraySavePost = [
                'name' => $data['name'],
                'content' => $data['content'] ?? '',
                'short_description' => $data['short_description'] ?? '',
                'seo_title' => $data['seo_title'] ?? '',
                'seo_description' => $data['seo_description'] ?? '',
                'category_id' => $data['category_id'] ?? 0,
                'author' => $user->id
            ];
            if ($imgUpload['status'] == 'success') {
                $arraySavePost['img'] = $imgUpload['img'];
            }

            $post = Post::create($arraySavePost);
            if ($post) {
                return new DataObjectDTO(status: true, data: $post);
            }
            else{
                return new DataObjectDTO(status: false);
            }
        }
        else{
            return new DataObjectDTO(status: false, error: $imgUpload['text']);
        }

    }

    public function updatePost(array $data): DataEmptyDTO
    {
        isset($data['img']) ? $imgUpload = $this->uploadImage($data['img']) : $imgUpload['status'] ='empty';
        if ( $imgUpload['status'] !='error' ) {

            $arraySavePost = [
                'name' => $data['name'],
                'content' => $data['content'] ?? '',
                'short_description' => $data['short_description'] ?? '',
                'seo_title' => $data['seo_title'] ?? '',
                'seo_description' => $data['seo_description'] ?? '',
                'category_id' => $data['category_id'] ?? 0,
            ];
            if( $imgUpload['status'] =='success' ){
                $arraySavePost['img'] = $imgUpload['img'];
            }

            $post = Post::where('id', '=', $data['id'])->update($arraySavePost);

            Cache::forget('post_id_'.$data['id']);

            if ($post) {
                return new DataEmptyDTO(status: true);
            }
            else {
                return new DataEmptyDTO(status: false);
            }
        }
        else{
            return new DataEmptyDTO(status: false, error: $imgUpload['text']);
        }
    }


    public function deletePost(int $id): DataObjectDTO
    {
        $post = Post::where('id', '=', $id)->first();

        $status = false;

        if ($post) {
            if ($post->img && $post->img!==''){
                $removeImg = File::delete(public_path($post->img));

                if ( $removeImg ) {
                    $status = true;
                }
                else{
                    return new DataObjectDTO(status: false, error: 'ошибка при удалении изображения новости');
                }
            }
            else{
                $status = true;
            }

            if($status){
                $post->delete();

                Cache::forget('post_id_' . $id);

                return new DataObjectDTO(status: true);
            }
        }
        else {
            return new DataObjectDTO(status: false, error: 'новость не найдена ');
        }

    }

}
