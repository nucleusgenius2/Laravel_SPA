<?php

namespace App\Services;

use App\DTO\DataObjectDTO;
use App\DTO\DataVoidDTO;
use App\Models\Mod;
use App\Models\User;
use App\Traits\HashFileGenerated;
use App\Traits\UploadFiles;
use App\Traits\UploadsImages;

class ModService
{
    use UploadsImages, UploadFiles, HashFileGenerated;
    public function getMods(array $data, int $perPage): DataObjectDTO
    {
        $mapsList = Mod::orderBy('mod_rate', 'desc')->paginate($perPage , ['*'], 'page', $data['page']);

        return new DataObjectDTO(status: true, data: $mapsList);
    }

    public function createMods(array $data, User $user): DataVoidDTO
    {
        $imgUpload = $this->uploadImage($data['url_img'],'preview_mods');
        if ( $imgUpload['status'] =='success' ) {

            $fileUpload = $this->uploadFile($data['mod_archive'],'file|mimes:zip|max:204800', $data['name'],'mods');
            if ( $fileUpload['status'] =='success' ) {

                $hash = $this->getHash('mods', $fileUpload['url']);
                if (!$hash) {
                    $hash = [];
                }

                $mod = Mod::create([
                    'url_img' => $imgUpload['img'],
                    'url_name' => $fileUpload['url'],
                    'name' => $data['name'],
                    'name_dir' => $data['name_dir'],
                    'description' => $data['description'],
                    'author' => $user->name,
                    'author_id' => $user->id,
                    'version' => $data['version'],
                    'type' => $data['type'],
                    'ch' => json_encode($hash),
                    'mod_rate' => 0,
                ]);

                if ($mod) {
                    return new DataVoidDTO(status: true);
                }
                else{
                    return new DataVoidDTO(status: false, error: 'Данные не сохранились', code: 500);
                }
            }
            else{
                return new DataVoidDTO(status: false, error: $fileUpload['text'], code: 500);
            }
        }
        else{
            return new DataVoidDTO(status: false, error: $imgUpload['text'], code: 500);
        }
    }


    public function deleteMods(int $id): DataVoidDTO
    {
        $fileDataBase = Mod::where('id', $id)->first();
        if ( $fileDataBase ){
            $dataFileVoidDTO = $this->deleteFile($fileDataBase->url_name);

            if( $dataFileVoidDTO->status ){
                $fileDataBase->delete();

                $dataImgVoidDTO = $this->deleteFile($fileDataBase->url_img);
                if( $dataImgVoidDTO->status ) {

                    return new DataVoidDTO(status: true);
                }
                else{
                    return new DataVoidDTO(status: false, error: $dataImgVoidDTO->error, code: 500);
                }
            }
            else{
                return new DataVoidDTO(status: false, error: $dataFileVoidDTO->error, code: 500);
            }
        }
        else{
            return new DataVoidDTO(status: false, error: 'Карта не найдена', code: 404);
        }
    }
}
