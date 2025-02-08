<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\PostSearchRequest;
use App\Models\Post;
use App\Services\PostService;
use App\Traits\StructuredResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Traits\UploadsImages;
class PostController extends Controller
{
    use UploadsImages;

    protected PostService $service;

    public function __construct(PostService $service)
    {
        $this->service = $service;
    }

    public int $perPageFrontend = 2;

    public function index(PostSearchRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();

        $dataPaginatorDTO = $this->service->getPosts(data: $data, modelPost: $post, perPage: $this->perPageFrontend);

        if ( $dataPaginatorDTO->status) {
            $this->code = 200;
            $this->status = 'success';
            $this->dataJson = $dataPaginatorDTO->data;
        } else {
            $this->text = 'Запрашиваемой страницы не существует';
            $this->code = 404;
        }

        return $this->responseJsonApi();
    }



    public function show(int $id): JsonResponse
    {
        if ($id > 0){
            $contentPostSingle = Cache::rememberForever('post_id_'.$id , function () use ($id ) {
                return Post::where('id', '=', $id )->get();
            });

            if (count($contentPostSingle) > 0) {
                $this->status = 'success';
                $this->code = 200;
                $this->dataJson = $contentPostSingle;
            } else {
                $this->text = 'Запрашиваемой новости не существует';
                $this->code = 404;
            }
        }

        return $this->responseJsonApi();
    }



    public function store(PostRequest $request): JsonResponse
    {
        $data = $request->validated();

        isset($data['img']) ? $imgUpload = $this->uploadImage($data['img']) : $imgUpload['status'] ='empty';
        if ( $imgUpload['status'] !='error' ) {

            $arraySavePost = [
                'name' => $data['name'],
                'content' => $data['content'] ?? '',
                'short_description' => $data['short_description'] ?? '',
                'seo_title' => $data['seo_title'] ?? '',
                'seo_description' => $data['seo_description'] ?? '',
                'category_id' => $data['category_id'] ?? 0,
                'author' => $request->user()->id
            ];
            if( $imgUpload['status'] =='success' ){
                $arraySavePost['img'] = $imgUpload['img'];
            }

            $post = Post::create($arraySavePost);

            if ($post) {
                $this->status = 'success';
                $this->code = 200;
                $this->text = 'Запись создана';
                $this->dataJson = $post->id;
            } else {
                $this->text = 'Запись не была создана';
            }
        }
        else{
            $this->text = $imgUpload['text'];
        }

        return $this->responseJsonApi();
    }


    public function update(PostRequest $request): JsonResponse
    {
        $data = $request->validated();

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
                $this->status = 'success';
                $this->code = 200;
                $this->text = 'Запись создана';
            }
            else {
                $this->text = 'Запрашиваемой страницы не существует';
                $this->code = 404;
            }
        }
        else{
            $this->text = $imgUpload['text'];
        }

        return $this->responseJsonApi();
    }


    public function destroy(int $id): JsonResponse
    {
        if($id > 0) {
            $post = Post::where('id', '=', $id)->delete();

            Cache::forget('post_id_' . $id);

            if ($post) {
                $this->status = 'success';
                $this->code = 200;
            } else {
                $this->text = 'Запрашиваемого ресурса не существует';
                $this->code = 404;
            }
        }

        return $this->responseJsonApi();
    }
}
