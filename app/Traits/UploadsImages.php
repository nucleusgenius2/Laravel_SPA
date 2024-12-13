<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait UploadsImages
{
    /**
     * @param object $img
     * @return array
     */
    protected function uploadImage(object|string $img, string $path=''): array
    {
        $imageReturn = [
            'img' =>'',
            'status' => 'error',
            'text' => ''
        ];

        if (gettype($img) == 'string') {
            $imageReturn['img'] = $img;
            $imageReturn['status'] = 'success';

            return $imageReturn;
        }

        $validated = Validator::make(['img' => $img], [
            'img' => 'image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validated->fails()) {
            $imageReturn['text'] = $validated->errors();
        } else {
            $data = $validated->valid();

            $destinationPath = $path ==='' ? public_path('images') : public_path($path);

            $imageName = time() . '.' . $data['img']->extension();

            if ($data['img']->move($destinationPath, $imageName)) {
                $imageReturn['img'] = $path ==='' ? '/images/' . $imageName : '/'.$path .'/'. $imageName;

                $imageReturn['status'] = 'success';
            } else {
                $imageReturn['text'] = 'Не удалось загрузить изображение.';
            }
        }

        return $imageReturn;
    }
}
