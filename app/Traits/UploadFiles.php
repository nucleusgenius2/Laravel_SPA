<?php

namespace App\Traits;

use App\DTO\DataEmptyDto;
use App\DTO\DataStringDto;
use App\DTO\DataVoidDTO;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

trait UploadFiles
{


    /**
     * Загрузка файлов на сервер
     * @param object|null $img
     * @param string $path
     * @return DataStringDto
     */
    protected function uploadFile(?object $img, string $path=''): DataStringDto
    {
        $destinationPath = $path ==='' ? public_path('files') : public_path($path);

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $fileName = time() . '.' . $img->extension();

        if ($img->move($destinationPath, $fileName)) {
            $filePath = $path ==='' ? '/files/' . $fileName : '/'.$path .'/'. $fileName;

            return new DataStringDto(status: true, data: $filePath );
        } else {
            return new DataStringDto(status: false, error: 'Не удалось загрузить файл.');
        }

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
