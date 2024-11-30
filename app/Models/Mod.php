<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Mod extends Model
{
    protected $table = 'mods';

    protected $fillable = [
        'url_img',
        'url_name',
        'name',
        'name_dir',
        'description',
        'author',
        'author_id',
        'version',
        'type',
        'ch',
        'mod_rate',
    ];

}


