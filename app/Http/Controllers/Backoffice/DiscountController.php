<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Trait\Backoffice\ResourcesSkeleton;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    use ResourcesSkeleton;

    protected static $model = Discount::class;

    public function getView()
    {
        return view('backoffice.discounts');
    }

    public function apiList()
    {
        $result = self::apiListTemplate(self::$model, [
            'id', 'name', 'code', 'type', 'usage_limit', 'usage_limit_user', 'count', 'expires_at', 'created_at',
        ], [
            'type' => '=',
            'code' => 'LIKE',
            'name' => 'LIKE',
            'expires_at' => 'BETWEEN',
            'created_at' => 'BETWEEN',
        ]);

        return response()->json($result);
    }

    public function apiPatchOrCreate()
    {
        $result = self::apiPatchOrCreateTemplate(self::$model, [
            'name' => 'required',
            'code' => ['required', Rule::unique('discounts')->ignore(request('id', null), 'id')],
            'type' => [
                'required',
                'in:fixed,percentage,shipment',
            ],
        ], ['name', 'code', 'type', 'usage_limit', 'usage_limit_user', 'expires_at']);

        return responseSuccess([
            'object' => $result,
        ]);
    }

    public function apiDelete()
    {
        $result = self::apiDeleteTemplate(self::$model);

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
        $result = self::apiSearchTemplate(self::$model, ['id', 'name', 'code']);

        return response()->json([
            'items' => $result->get(),
        ]);
    }
}
