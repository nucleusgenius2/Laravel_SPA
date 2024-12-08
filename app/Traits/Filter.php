<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait Filter
{
    public function filterCustom($filters = [], $fixeTime = false)
    {

        $tableName = $this->getTable();
        $query = $this;
        foreach ($filters as $field => $value) {
            //поиск по фрагменту значения
            if (isset($this->whereSearch) && in_array($field, $this->whereSearch)) {
                $query = $query->where($tableName . '.' . $field, 'LIKE', '%' . $value . '%');
            }

            //строгий поиск по точному совпадению
            if (isset($this->whereStrong) && in_array($field, $this->whereStrong)) {
                $query = $query->where($tableName . '.' . $field, '=',  $value );
            }

            //поиск в интервале дат
            if (isset($this->intervalSearch) && in_array($field, $this->intervalSearch)) {
                if (str_ends_with($field, 'from')){
                    $field = str_replace('_from', '', $field);
                    $query = $query->where($tableName . '.' . $field, '>=', Carbon::parse($value));
                }
                if (str_ends_with($field, 'to')){
                    $field = str_replace('_to', '', $field);

                    if ( $fixeTime ) {
                        $value .=' 23:59:59';
                    }

                    $query = $query->where($tableName . '.' . $field, '<=', Carbon::parse($value));
                }
            }

            if (isset($this->dateFixed) && in_array($field, $this->dateFixed)) {
                if ($value == 'day') {
                //фильтр – данные за последний день
                    $query = $query->whereDate('created_at', '>=', Carbon::yesterday());
                } else if ($value == 'week') {
                //фильтр – данные за последнюю неделю
                    $query = $query->whereDate('created_at', '>=', now()->subDays(7));
                } else if ($value == 'month') {
                //фильтр – данные за этот месяц (с 1 числа этого месяца)
                    $query = $query->whereDate('created_at', '>=', Carbon::now()->startOfMonth());
                } else if ($value == 'year') {
                //фильтр – данные за этот год
                    $query = $query->whereDate('created_at', '>=', Carbon::now()->startOfYear());
                }
            }


        }

        return $query;
    }
}
