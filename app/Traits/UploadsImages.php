<?php

namespace App\Traits;

use App\DTO\DataStringDto;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

trait UploadsImages
{


    /**
     * Загрузка изображений на сервер
     * @param object|null $img
     * @param string $path
     * @return DataStringDto
     */
    protected function uploadImage(?object $img, string $path=''): DataStringDto
    {
        $destinationPath = $path ==='' ? public_path('images') : public_path($path);

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $imageName = time() . '.' . $img->extension();

        if ($img->move($destinationPath, $imageName)) {
            $imgPath = $path ==='' ? '/images/' . $imageName : '/'.$path .'/'. $imageName;

            return new DataStringDto(status: true, data: $imgPath );
        } else {
            return new DataStringDto(status: false, error: 'Не удалось загрузить изображение.');
        }

    }
}
