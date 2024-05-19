<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model{

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'content',
        'short_description',
        'seo_title',
        'seo_description',
        'img',
        'category_id',
        'author',
    ];

}
