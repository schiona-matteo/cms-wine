<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function getView()
    {
        return view('backoffice.users');
    }

    public function apiList()
    {
        $base_search = User::select([
            'id', 'name', 'email', 'created_at',
        ]);

        $filters = [
            'name' => 'LIKE',
            'email' => 'LIKE',
            'created_at' => 'BETWEEN',
        ];

        filters($filters, $base_search);

        $result = listings($base_search);

        return response()->json($result);
    }

    public function apiPatchOrCreate()
    {
        request()->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(request('id', null), 'id'),
            ],
        ]);

        $model = findOrCreateModel(User::class, request('id'));

        $fields = ['email', 'name'];

        foreach ($fields as $field) {
            $model->{$field} = request($field);
        }

        if (request('password') != null) {
            $model->password = Hash::make(request('password'));
        }

        $model->save();

        return responseSuccess([
            'object' => $model,
        ]);
    }

    public function apiDelete()
    {
        $model = User::findOrFail(request('model_id'));

        if ($model->id != auth()->user()->id) {
            $model->delete();

            return responseSuccess();
        } else {
            return responseError([
                'message' => 'Impossibile eliminare questo utente',
            ], 401);
        }

        return responseError();
    }

    public function apiAutocompleSearch()
    {
        return User::where('name', 'like', '%'.request('query').'%')
            ->orWhere('email', 'like', '%'.request('query').'%')
            ->distinct()->select(['name', 'id as code'])->get();
    }

    public function apiSearch()
    {
        $items = User::whereNotIn('id', request('exclude', []))
            ->select(['id', 'name', 'email'])
            ->take(request('take', 25));

        if (request('query', '') != '') {
            $items->where('name', 'like', '%'.strtolower(request('query')).'%');
        }

        // if (request('params.onlyAdmin', false) === true) {
        //     $items->where('type', 'admin');
        // }

        $with = [];
        // if (request('params.withOrders', false) === true) {
        //     $with['orders'] = function ($q) {
        //         $q->where('available', '>', 0);
        //     };
        // }

        if (! empty($with)) {
            $items->with($with);
        }

        return response()->json([
            'items' => $items->get(),
        ]);
    }
}
