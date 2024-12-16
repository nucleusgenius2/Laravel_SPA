<?php

namespace App\Models;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Filter;

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

    protected array $whereSearch = ['name'];
    protected array $intervalSearch = ['created_at_from', 'created_at_to'];

    protected array $dateFixed = ['date_fixed'];

    public const cashSecond = 300;

}
