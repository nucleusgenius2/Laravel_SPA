<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ResponseController;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AdminController
{
    use ResponseController;

    /**
     * delete post
     * @param int $id
     * @return JsonResponse
     */
    public function deletePost(int $id): JsonResponse
    {
        $validated = Validator::make(['id' => $id], [
            'page' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $array_news = Post::where('id', '=', $data['id'])->delete();

            if ($array_news) {
                $this->status = 'success';
                $this->code = 200;
            } else {
                $this->text = 'Запрашиваемой страницы не существует';
            }
        }

        return $this->responseJsonApi();
    }


    /**
     * upload img
     * @param object|string|null $img
     * @return array
     */
    protected function uploaderImg(null|object|string $img): array
    {
        $imageReturn = [
            'img' =>'',
            'status' => 'error',
            'text' => ''
        ];

        if (gettype($img) == 'object') {
            $arrayValidateImg = [
                'img' => $img
            ];

            $validated = Validator::make($arrayValidateImg, [
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
        }
        else {
            log::info( '3');
            if ($img) {
                $imageReturn['img'] = $img;
                $imageReturn['status'] = 'success';

            }
            else {
                log::info( '3');
                $imageReturn['img'] ='';
            }
        }

        return $imageReturn;
    }


    /**
     * create post
     * @param Request $request
     * @return JsonResponse
     */
    public function createPost(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'content' => 'nullable|string',
            'short_description' => 'nullable|string|max:300',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'img' => 'nullable|image|mimes:png,jpg,jpeg',
            'id_category' => 'nullable|int',
            'author' => 'required|string|max:100',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $imageName = $this->uploaderImg($data['img']);

            $array_save_new = [
                'name' => $data['name'],
                'content' => $data['content'] ?? '',
                'short_description' => $data['short_description'] ?? '',
                'seo_title' => $data['seo_title'] ?? '',
                'seo_description' => $data['seo_description'] ?? '',
                'img' => $imageName['img'],
                'id_category' => $data['id_category'] ?? 0,
                'author' => $data['author']
            ];

            $flight = Post::create($array_save_new);

            if ($flight) {
                $this->status = 'success';
                $this->code = 200;
                $this->text = 'Запись создана';
                $this->json = $flight->id;
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
    public function updatePost(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'id' => 'required|int',
            'name' => 'required|string|min:3|max:255',
            'content' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'id_category' => 'nullable|int',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $imageName = $this->uploaderImg($data['img']);

            if ( $imageName['status'] == 'success'){
                $array_save_new = Post::where('id', '=', $data['id'])->update([
                    'name' => $data['name'],
                    'content' => $data['content'] ?? '',
                    'short_description' => $data['short_description'] ?? '',
                    'seo_title' => $data['seo_title'] ?? '',
                    'seo_description' => $data['seo_description'] ?? '',
                    'img' => $imageName['img'],
                    'id_category' => $data['id_category'] ?? 0,
                ]);

                if ($array_save_new) {
                    $this->status = 'success';
                    $this->code = 200;
                    $this->text = 'Запись создана';
                }
                else {
                    $this->text = 'Запрашиваемой страницы не существует';
                }
            }
            else {
                $this->text = $imageName['text'];
            }
        }

        return $this->responseJsonApi();
    }

}


