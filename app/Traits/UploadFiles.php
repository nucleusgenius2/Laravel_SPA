<?php

namespace App\Traits;

use App\DTO\DataEmptyDto;
use App\DTO\DataVoidDTO;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

trait UploadFiles
{


    /**
     * @param object|string $file
     * @param string $validation
     * @param string $path
     * @return array|string[]
     */
    protected function uploadFile(object|string $file, string $validation, string $name, string $path=''): array
    {
        $fileReturn = [
            'url' =>'',
            'status' => 'error',
            'text' => ''
        ];

        if (gettype($file) == 'string') {
            $imageReturn['url'] = $file;
            $imageReturn['status'] = 'success';

            return $imageReturn;
        }

        $validated = Validator::make(['file' => $file], [
            'file' => $validation,
        ]);

        if ($validated->fails()) {
            $fileReturn['text'] = $validated->errors();
        } else {
            $data = $validated->valid();

            $destinationPath = $path ==='' ? public_path() : public_path($path);

            $imageName = $name . '.' . $data['file']->extension();

            if ($data['file']->move($destinationPath, $imageName)) {
                $fileReturn['url'] = $path ==='' ? '/' . $imageName : '/'.$path .'/'. $imageName;

                $fileReturn['status'] = 'success';
            } else {
                $fileReturn['text'] = 'Не удалось загрузить файл.';
            }
        }

        return  $fileReturn;
    }

    protected function deleteFile(string $path): DataVoidDTO
    {
        $pathDelete = public_path($path);

        if (!File::exists($pathDelete )) {
            return new DataVoidDTO(status: false, error: 'Файл не найден' );
        }

        if (!File::isWritable($pathDelete)) {
            return new DataVoidDTO(status: false, error: 'Файл недоступен для удаления');
        }

        $deleteFile = File::delete($pathDelete);

        if ( $deleteFile ) {
            return new DataVoidDTO(status: true );
        }
        else{
            return new DataVoidDTO(status: false, error: 'Файл не удален');
        }
    }
}
