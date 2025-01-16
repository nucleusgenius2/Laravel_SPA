<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait UploadsImages
{

    /**
     * @param object|string $img
     * @param string $path
     * @return string[]
     */
    protected function uploadImage(?object $img, string $path=''): array
    {
        $imageReturn = [
            'img' =>'',
            'status' => 'error',
            'text' => ''
        ];

        $destinationPath = $path ==='' ? public_path('images') : public_path($path);

        $imageName = time() . '.' . $img->extension();

        if ($img->move($destinationPath, $imageName)) {
            $imageReturn['img'] = $path ==='' ? '/images/' . $imageName : '/'.$path .'/'. $imageName;

            $imageReturn['status'] = 'success';
        } else {
            $imageReturn['text'] = 'Не удалось загрузить изображение.';
        }

        return $imageReturn;
    }
}
