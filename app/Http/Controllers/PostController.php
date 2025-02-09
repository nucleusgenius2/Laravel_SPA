<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\PostSearchRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Traits\UploadsImages;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    protected PostService $service;

    public function __construct(PostService $service)
    {
        $this->service = $service;
    }

    public int $perPageFrontend = 10;

    /**
     * Возвращает список новостей
     * @param PostSearchRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function index(PostSearchRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();
        $dataObjectDTO = $this->service->getPosts(data: $data, modelPost: $post, perPage: $this->perPageFrontend);

        if ( $dataObjectDTO->status) {
            $this->code = 200;
            $this->status = 'success';
            $this->dataJson = $dataObjectDTO->data;
        } else {
            $this->text = 'Запрашиваемой страницы не существует';
            $this->code = 404;
        }

        return $this->responseJsonApi();
    }


    /**
     * Возвращает конкретную новость
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        if ($id > 0){

            $dataObjectDTO = $this->service->getPost(id: $id);

            if ($dataObjectDTO->status) {
                $this->status = 'success';
                $this->code = 200;
                $this->dataJson = $dataObjectDTO->data;
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

        $dataObjectDTO = $this->service->createPost(data: $data, user: $request->user());

        if ($dataObjectDTO->status) {
            $this->status = 'success';
            $this->code = 200;
            $this->text = 'Новость создана';
            $this->dataJson = $dataObjectDTO->data->id;
        } else {
            $this->text = $dataObjectDTO->error ?? 'Новость не была создана';
        }

        return $this->responseJsonApi();
    }


    public function update(PostRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dataEmptyDTO = $this->service->updatePost(data: $data);

        if ($dataEmptyDTO->status) {
                $this->status = 'success';
                $this->code = 200;
                $this->text = 'Запись обновлена';
        }
        else {
            $this->text = $dataEmptyDTO->error ;
            $this->code = 400;
        }

        return $this->responseJsonApi();
    }


    public function destroy(int $id): JsonResponse
    {
        if($id > 0) {
            $dataObjectDTO = $this->service->deletePost(id: $id);

            if ($dataObjectDTO->status) {
                $this->status = 'success';
                $this->code = 200;
            } else {
                $this->text = $dataObjectDTO->error ;
                $this->code = 400;
            }
        }

        return $this->responseJsonApi();
    }
}
