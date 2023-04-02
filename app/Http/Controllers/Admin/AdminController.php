<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdminController
{

    /**
     * @var array
     */
    public array $response = [
        'data' => [
            'status' => 'error',
            'text' => '',
        ],
        'code' => 402
    ];


    /**
     * delete post
     * @param int $id
     * @return JsonResponse
     */
    public function deletePost(int $id): JsonResponse
    {
        $response = $this->response;

        $array_news = Post::where('id', '=', $id)->delete();

        if ($array_news) {
            $response['data']['status'] = 'success';
            $response['code'] = 200;
        }

        return response()->json($response['data'], $response['code']);
    }


    /**
     * image upload
     * @param object|null $img
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
            if ($img) {
                $imageReturn['img'] = $img;
                $imageReturn['status'] = 'success';
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
        $response = $this->response;

        $validated = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'content' => 'nullable|string',
            'short_description' => 'nullable|string',
            'seo_title' => 'nullable|string',
            'seo_discription' => 'nullable|string',
            'img' => 'nullable|image|mimes:png,jpg,jpeg',
            'id_category' => 'nullable|string',
            'autor' => 'required|string',
        ]);

        if ($validated->fails()) {
            $response['data']['text'] = $validated->errors();
        } else {
            $data = $validated->valid();

            $imageName = $this->uploaderImg($data['img']);

            $array_save_new = [
                'name' => $data['name'],
                'content' => $data['content'] ?? '',
                'short_description' => $data['short_description'] ?? '',
                'seo_title' => $data['seo_title'] ?? '',
                'seo_discription' => $data['seo_discription'] ?? '',
                'img' => $imageName,
                'id_category' => $data['id_category'] ?? '',
                'autor' => $data['autor']
            ];

            $flight = Post::create($array_save_new);

            if ($flight) {
                $response['data']['status'] = 'success';
                $response['data']['text'] = 'Запись обновлена';
                $response['code'] = 200;
            }
        }

        return response()->json($response['data'], $response['code']);
    }


    /**
     * update post
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePost(Request $request): JsonResponse
    {
        $response = $this->response;

        $validated = Validator::make($request->all(), [
            'id' => 'required|int',
            'name' => 'required|string|min:3',
            'content' => 'nullable|string',
            'short_description' => 'nullable|string',
            'seo_title' => 'nullable|string',
            'seo_discription' => 'nullable|string',
            'id_category' => 'nullable|string',
        ]);

        if ($validated->fails()) {
            $response['data']['text'] = $validated->errors();

        } else {
            $data = $validated->valid();

            $imageName = $this->uploaderImg($data['img']);

            if ( $imageName['status'] == 'success'){
                $array_save_new = Post::where('id', '=', $data['id'])->update([
                    'name' => $data['name'],
                    'content' => $data['content'] ?? '',
                    'short_description' => $data['short_description'] ?? '',
                    'seo_title' => $data['seo_title'] ?? '',
                    'seo_discription' => $data['seo_discription'] ?? '',
                    'img' => $imageName['img'],
                    'id_category' => $data['id_category'] ?? '',
                ]);

                if ($array_save_new) {
                    $response['data']['status'] = 'success';
                    $response['data']['text'] = 'Запись обновлена';
                    $response['code'] = 200;
                }
            }
            else {
                $response['data']['text'] =  $imageName['text'];
            }
        }

        return response()->json($response['data'], $response['code']);
    }

}


