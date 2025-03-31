<?php

namespace App\Services;

use App\DTO\DataVoidDTO;
use App\DTO\DataObjectDTO;
use App\Models\Post;
use App\Models\User;
use App\Traits\UploadFiles;
use App\Traits\UploadsImages;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PostService
{
    use UploadsImages, UploadFiles;

    public function getPosts(array $data, Post $modelPost, int $perPage): DataObjectDTO
    {
        if (isset($data['name']) || isset($data['created_at_to']) || isset($data['created_at_from']) || isset($data['date_fixed'])) {
            $query = $modelPost->filterCustom($data);

            $postList = $query->orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $data['page']);
        } else {
            if (isset($data['admin'])) {
                $postList = Post::orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $data['page']);
            } else {
                $postList = Cache::remember('post_index_page_' . $data['page'], Post::cashSecond, function () use ($data, $perPage) {
                    return Post::orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $data['page']);
                });
            }
        }

        return new DataObjectDTO(status: true, data: $postList);
    }

    public function getPost(int $id): DataObjectDTO
    {

        $postSingle = Cache::rememberForever('post_id_' . $id, function () use ($id) {
            return Post::where('id', '=', $id)->get();
        });

        if ($postSingle) {
            return new DataObjectDTO(status: true, data: $postSingle);
        } else {
            return new DataObjectDTO(status: false, error: 'Новости не существует', code: 404);
        }
    }

    public function createPost(array $data, User $user): DataObjectDTO
    {
        $arraySavePost = [
            'name' => $data['name'],
            'content' => $data['content'] ?? '',
            'short_description' => $data['short_description'] ?? '',
            'seo_title' => $data['seo_title'] ?? '',
            'seo_description' => $data['seo_description'] ?? '',
            'category_id' => $data['category_id'] ?? 0,
            'author' => $user->id
        ];

        if(isset($data['img'])){
            $dataStringDtoIMG = $this->uploadImage($data['img']);

            if($dataStringDtoIMG->status){
                $arraySavePost['img'] = $dataStringDtoIMG->data;
            }
            else{
                return new DataObjectDTO(status: false, error: $dataStringDtoIMG->error, code: 400);
            }
        }

        $post = Post::create($arraySavePost);
        if ($post) {
            return new DataObjectDTO(status: true, data: $post);
        } else {
            return new DataObjectDTO(status: false, error: 'Новость не была создана', code: 500);
        }
    }

    public function updatePost(array $data): DataVoidDTO
    {
        $arraySavePost = [
            'name' => $data['name'],
            'content' => $data['content'] ?? '',
            'short_description' => $data['short_description'] ?? '',
            'seo_title' => $data['seo_title'] ?? '',
            'seo_description' => $data['seo_description'] ?? '',
            'category_id' => $data['category_id'] ?? 0,
        ];

        if(isset($data['img'])){
            $dataStringDtoIMG = $this->uploadImage($data['img']);

            if($dataStringDtoIMG->status){
                $arraySavePost['img'] = $dataStringDtoIMG->data;
            }
            else{
                return new DataVoidDTO(status: false, error: $dataStringDtoIMG->error, code: 400);
            }
        }

        $post = Post::where('id', '=', $data['id'])->update($arraySavePost);

        Cache::forget('post_id_' . $data['id']);

        if ($post) {
            return new DataVoidDTO(status: true);
        } else {
            return new DataVoidDTO(status: false, code: 500);
        }
    }


    public function deletePost(int $id): DataObjectDTO
    {
        $post = Post::where('id', '=', $id)->first();

        if (!$post) {
            return new DataObjectDTO(status: false, error: 'Новость не найдена', code: 404);
        }

        if ($post->img && $post->img !== '') {
            $dataFileVoidDTO = $this->deleteFile($post->img);
            if (!$dataFileVoidDTO->status) {
                return new DataObjectDTO(status: false, error: 'Ошибка при удалении изображения новости', code: 500);
            }
        }

        $post->delete();

        Cache::forget('post_id_' . $id);

        return new DataObjectDTO(status: true);
    }

}
