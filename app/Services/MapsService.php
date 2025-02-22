<?php

namespace App\Services;


use App\DTO\DataObjectDTO;
use App\DTO\DataVoidDTO;
use App\Models\Map;
use App\Models\Post;
use App\Models\User;
use App\Traits\HashFileGenerated;
use App\Traits\UploadFiles;
use App\Traits\UploadsImages;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MapsService
{
    use UploadsImages, UploadFiles, HashFileGenerated;

    public function getMaps(array $data, Map $map, int $perPage): DataObjectDTO
    {
        //ветка фильтра
        if ( isset($data['name']) || isset($data['total_player_from']) || isset($data['total_player_to']) || isset($data['size']) ){
            $query = $map->filterCustom($data);

            $mapsList = $query->orderBy('map_rate', 'desc')->paginate($perPage , ['*'], 'page', $data['page']);
        }
        else {
            $mapsList = Map::orderBy('map_rate', 'desc')->paginate($perPage, ['*'], 'page', $data['page']);
        }

        return new DataObjectDTO(status: true, data: $mapsList);
    }

    public function hasMap($mapName): DataVoidDTO
    {
        $validated = Validator::make(['name' => $mapName], [
            'name' => 'string|min:1|max:50|regex:/(^[A-Za-z0-9.-_(?!\S*\s\S*\s)]+$)+/',
        ]);

        if ($validated->fails()) {
            return new DataVoidDTO(status: false, error: $validated->errors(), code: 422);
        } else {
            $data = $validated->valid();

            $map = Map::where('name', '=', $data['name'])->orderBy('map_rate', 'desc')->exists();
            if ($map){
                return new DataVoidDTO(status: true);
            }
            else{
                return new DataVoidDTO(status: false, error: 'Карта отсутствует', code: 404);
            }
        }
    }

    public function createMaps(array $data, User $user): DataVoidDTO
    {
        $dataStringDtoIMG = $this->uploadImage($data['url_img'],'maps/preview');
        if ($dataStringDtoIMG->status) {

            $dataStringDtoFile = $this->uploadFile($data['map_archive'],'maps');
            if ( $dataStringDtoFile ->status) {

                $hash = $this->getHash('maps',$dataStringDtoFile->data);
                if (!$hash) {
                    $hash = [];
                }

                $map = Map::create([
                    'url_img' => $dataStringDtoIMG->data,
                    'url_name' => $dataStringDtoFile->data,
                    'name' => $data['name'],
                    'author' => $user->name,
                    'author_id' => $user->id,
                    'version' => $data['version'],
                    'total_player' => $data['total_player'],
                    'rate' => $data['rate'],
                    'size' => $data['map_size'],
                    'ch' => json_encode($hash),
                    'map_rate' => 0,
                ]);

                if ($map){
                    return new DataVoidDTO(status: true);
                }
                else{
                    return new DataVoidDTO(status: false, error: 'Данные не сохранились', code: 500);
                }
            }
            else{
                return new DataVoidDTO(status: false, error: $dataStringDtoFile->error, code: 400);
            }
        }
        else{
            return new DataVoidDTO(status: false, error: $dataStringDtoIMG->error, code: 400);
        }
    }

    public function deleteMaps(int $id): DataVoidDTO
    {
        $fileDataBase = Map::where('id', $id)->first();
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
