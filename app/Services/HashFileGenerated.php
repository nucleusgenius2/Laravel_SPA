<?php

namespace App\Services;


use Illuminate\Support\Facades\Log;
use ZipArchive;

class HashFileGenerated
{
    public function getHash(string $type, string $archiveName ){
        $hash = [];

        $zip = new ZipArchive;
        function rrmdir($dir)
        {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir . "/" . $object) == "dir")
                            rrmdir($dir . "/" . $object);
                        else unlink($dir . "/" . $object);
                    }
                }
                reset($objects);
                rmdir($dir);
            }
        }


        if ($zip->open(public_path($type).'/'.$archiveName) === TRUE) {
            $newDir = public_path().'/'.request()->user()->name;

            $createDir = mkdir($newDir, 0700);
            if ( $createDir ){
                $zip->extractTo( $newDir  );
                $zip->close();

                $dirName = scandir($newDir);

                if ( isset($dirName[2]) ) {
                    $files = scandir($newDir . '/' . $dirName[2]);
                    foreach ($files as $key => $value) {
                        if ($key > 1) {
                            $thisFile = $newDir .'/'.$dirName[2]. '/' . $value;

                            $validFile = is_dir( $thisFile );
                            if (!$validFile){
                                //генерация хеша файла в sha256
                                $hash[$value] = hash_file('sha256', $thisFile);
                                //удаляем файл
                                unlink($thisFile);
                            }
                            else {
                                rrmdir($newDir .'/'.$dirName[2]. '/' . $value);
                            }
                        }
                    }
                    //удаляем папки
                    rmdir($newDir.'/'.$dirName[2]);
                    rmdir($newDir);

                    return $hash;
                }
                else {
                    log::error('ошибка генерации хеша '.$type.' '.$archiveName);
                }
            } else {
                log::error('ошибка создания папки для генерации хеша  '.$type.' '.$archiveName);
            }

        } else {
            log::error('ошибка генерации хеша '.$type.', не найден архив '.$archiveName);
        }

    }

}
