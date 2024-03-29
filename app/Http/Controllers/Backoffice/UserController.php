<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Trait\Backoffice\ResourcesSkeleton;
use Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use ResourcesSkeleton;

    protected static $model = User::class;

    public function getView()
    {
        return view('backoffice.users');
    }

    public function apiList()
    {
        $result = self::apiListTemplate(self::$model, [
            'id', 'type', 'source', 'name', 'email', 'birthday', 'created_at',
        ], [
            'type' => '=',
            'source' => '=',
            'name' => 'LIKE',
            'email' => 'LIKE',
            'created_at' => 'BETWEEN',
        ]);

        return response()->json($result);
    }

    public function apiPatchOrCreate()
    {
        $result = self::apiPatchOrCreateTemplate(self::$model, [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(request('id', null), 'id'),
            ],
        ], ['email', 'name', 'type', 'source', 'birthday'], function ($object) {
            if (request('password') != null) {
                $object->password = Hash::make(request('password'));
            }
        });

        return responseSuccess([
            'object' => $result,
        ]);
    }

    public function apiDelete()
    {
        $result = self::apiDeleteTemplate(self::$model, function ($object) {
            return $object->id != auth()->user()->id;
        });

        if ($result['status'] == true) {
            return responseSuccess();
        } else {
            return responseError($result['message'] ? $result : null, 401);
        }
    }

    public function apiAutocompleSearch()
    {
        return self::apiAutocompleSearchTemplate(self::$model);
    }

    public function apiSearch()
    {
        $result = self::apiSearchTemplate(self::$model, ['id', 'name', 'email']);

        return response()->json([
            'items' => $result->get(),
        ]);
    }
}
