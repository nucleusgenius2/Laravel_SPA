<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait Filter
{
    public function filterCustom($filters = [])
    {

        $tableName = $this->getTable();
        $query = $this;
        foreach ($filters as $field => $value) {
            //поиск по фрагменту значения
            if (in_array($field, $this->whereFilterFields)) {
                $query = $query->where($tableName . '.' . $field, 'LIKE', '%'.$value.'%');
            }
            //строгий поиск по точному совпадению
            if ( isset($this->whereStrong)) {
                if (in_array($field, $this->whereStrong)) {
                    $query = $query->where($tableName . '.' . $field, '=',  $value );
                }
            }

            /*
           //поиск нескольких конкретных значений
           if (in_array($field, $this->whereInFilterFields)) {
               $query = $query->whereIn($tableName . '.' . $field, $value);
           }
           //поиск интервала между значениями
           if (in_array($field, $this->whereBetweenFilterFields)) {
               $query = $query->whereBetween($tableName . '.' . $field, $value);
           }
           */

            // фильтр поиск интервал
            if ( isset($this->whereInterval)) {
                if (in_array($field, $this->whereInterval)) {
                    if (str_ends_with($field, 'from')) {
                        $field = str_replace('_from', '', $field);
                        $query = $query->where($tableName . '.' . $field, '>=', $value);
                    }
                    if (str_ends_with($field, 'to')) {
                        $field = str_replace('_to', '', $field);
                        $query = $query->where($tableName . '.' . $field, '<=', $value);
                    }
                }
            }

            /*
            if (in_array($field, $this->whereDateFields)) {
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
            */

        }

        return $query;
    }
}
