<?php


namespace App\Models;


use App\Traits\Filter;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use Filter;

    protected $table = 'maps';

    protected $fillable = [
        'url_img',
        'url_name',
        'name',
        'author',
        'author_id',
        'version',
        'total_player',
        'rate',
        'size',
        'ch',
        'map_rate',
    ];

    protected array $whereFilterFields = ['name'];
    protected array $whereStrong = ['size'];

   // protected array $whereInFilterFields = ['status', 'user_id', 'type'];

    //protected array $whereBetweenFilterFields = ['total_player'];

    protected array $whereInterval = ['total_player_from', 'total_player_to'];

   //protected array $whereDateFields = ['date_fixed' ];

}


