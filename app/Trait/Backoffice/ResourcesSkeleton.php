<?php

namespace App\Trait\Backoffice;

use Closure;

trait ResourcesSkeleton
{
    public function apiListTemplate($model, $select = [], $filters = [], $with = null, $withCount = null, $withSum = null)
    {
        $base_search = $model::select($select);

        filters($filters, $base_search);

        $result = listings($base_search, $with, $withCount, $withSum);

        return $result;
    }

    public function apiPatchOrCreateTemplate($model, $validation = [], $fields = [], Closure $callback = null)
    {
        request()->validate($validation);

        $object = findOrNewModel($model, request('id'));

        foreach ($fields as $field) {
            $object->{$field} = request($field);
        }

        if ($callback != null) {
            $callback($object);
        }

        activity()->performedOn($object)
        ->event($object->exists ? 'modifica' : 'creazione')
        ->log('Azione backoffice');

        $object->save();

        return $object;
    }

    public function apiDeleteTemplate($model, Closure $callback = null): array
    {
        $object = $model::findOrFail(request('model_id'));

        if ($callback != null) {
            if ($callback($object)) {
                $object->delete();

                return [
                    'status' => true,
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Impossibile eliminare questo utente',
                ];
            }
        } else {
            $object->delete();

            return [
                'status' => true,
            ];
        }

        return [
            'status' => false,
        ];
    }

    public function apiAutocompleSearchTemplate($model)
    {
        return $model::where('name', 'like', '%'.request('query').'%')
            ->orWhere('email', 'like', '%'.request('query').'%')
            ->distinct()->select(['name', 'id as code'])->get();
    }

    public function apiSearchTemplate($model, $select = [], $with = [])
    {
        $items = $model::whereNotIn('id', request('exclude', []))
            ->select($select)
            ->take(request('take', 25));

        if (request('query', '') != '') {
            $items->where('name', 'like', '%'.strtolower(request('query')).'%');
        }

        if (! empty($with)) {
            $items->with($with);
        }

        return $items;
    }
}
