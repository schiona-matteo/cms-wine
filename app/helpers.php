<?php

use Carbon\Carbon;

function listings($base_search, $with = null, $withCount = null, $withSum = null)
{
    $take = request('take', 25);
    $page = request('page', 1);
    $orderBy = request('orderBy');
    $total = $base_search->count();
    $total_pages = intval(ceil($total / $take));
    $page = $page < 1 ? 1 : $page;
    $page = $page > $total_pages ? $total_pages : $page;
    $skip = $take * ($page - 1);

    if ($orderBy != null && $orderBy['column'] != null && $orderBy['column'] != '' && ! str_contains($orderBy['column'], '.')) {
        $base_search->orderBy($orderBy['column'], $orderBy['dir']);
    }

    $data = $base_search->take($take)->skip($skip);

    if ($with) {
        $data->with($with);
    }

    if ($withCount) {
        foreach ($withCount as $relation) {
            $data->withCount($relation);
        }
    }

    if ($withSum) {
        foreach ($withSum as $table => $field) {
            $data->withSum($table, $field);
        }
    }

    return [
        'data' => $data->get(),
        'total' => $total,
        'take' => $take,
        'total_pages' => $total_pages,
        'current' => $page,
    ];
}

function filters($filters, &$query, $having = false)
{
    foreach ($filters as $key => $type) {
        if (request('filters.'.$key)) {
            switch ($type) {
                case '=':
                    if ($having) {
                        $query->having($key, request('filters.'.$key));
                    } else {
                        $query->where($key, request('filters.'.$key));
                    }
                    break;
                case 'BOOL':
                    if ($having) {
                        $query->having($key, request('filters.'.$key) == 'true');
                    } else {
                        $query->where($key, request('filters.'.$key) == 'true');
                    }
                    break;
                case 'LIKE':
                    if ($having) {
                        $query->having($key, 'LIKE', '%'.request('filters.'.$key).'%');
                    } else {
                        $query->where($key, 'LIKE', '%'.request('filters.'.$key).'%');
                    }
                    break;
                case 'BETWEEN':
                    $array = request('filters.'.$key);
                    if ($array[0] == null) {
                        $array[0] = 0;
                    }
                    if ($array[1] == null) {
                        $array[1] = 99999999999999;
                    }

                    if (preg_match('/\d{4}-\d{2}-\d{2}/', $array[0])) {
                        $array[0] = Carbon::parse($array[0])->startOfDay();
                    }

                    if (preg_match('/\d{4}-\d{2}-\d{2}/', $array[1])) {
                        $array[1] = Carbon::parse($array[1])->endOfDay();
                    }

                    if ($having) {
                        $query->havingBetween($key, $array);
                    } else {
                        $query->whereBetween($key, $array);
                    }

                    break;
                case 'HAS':
                    $query->whereHas($key);
                    break;
                case 'HAS.NAME':
                case 'HAS.NAME.LIKE':
                    $column = explode('.', strtolower($type));

                    $query->whereHas($key, function ($q) use ($key, $column) {
                        if (count($column) == 2) {
                            $q->where(last($column), request('filters.'.$key));
                        } else {
                            $verb = last($column);
                            $q->where($column[1], $verb, ($verb == 'like' ? '%' : '').request('filters.'.$key).($verb == 'like' ? '%' : ''));
                        }
                    });
                    break;
                case 'NOT IN':
                    $query->whereNotIn('id', request('filters.'.$key));
                    break;
            }
        }
    }
}

function responseSuccess($data = [])
{
    return response()->json(array_merge([
        'status' => true,
        'message' => 'Aggiornamento riuscito',
    ], $data));
}

function responseError($data = [])
{
    return response()->json(array_merge([
        'status' => false,
        'message' => 'Aggiornamento non riuscito',
    ], $data), 500);
}

function findOrNewModel($class, $id)
{
    if ($id) {
        $model = $class::findOrFail($id);
    } else {
        $model = new $class;
    }

    return $model;
}
