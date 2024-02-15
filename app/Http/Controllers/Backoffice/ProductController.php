<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Trait\Backoffice\ResourcesSkeleton;
use Hash;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    use ResourcesSkeleton;

    protected static $model = Product::class;

    public function getView()
    {
        return view('backoffice.products.index');
    }

    public function getNewView()
    {
        $product = new Product([
            'type' => 'wine',
            'category' => '',
            'link_rewrite' => '',
            'name' => '',
            'subtitle_it' => '',
            'subtitle_en' => '',
            'description_it' => '',
            'description_en' => '',
            'settings' => [],
            'available_for_order' => true,
            'limited_buy' => null,
            'limited_buy_for_user' => null,
            'discountable' => true,
            'prevent_bankwire' => false,
            'is_virtual' => false,
            'meta_data' => [],
            'visibility' => Product::VISIBILITY_PUBLIC,
        ]);

        return view('backoffice.products.new_patch')->with('product', $product);
    }

    public function getPatchView(Product $product)
    {
        return view('backoffice.products.new_patch')->with('product', $product);
    }

    public function apiList()
    {
        $result = self::apiListTemplate(self::$model, [
            'id', 'type', 'category', 'link_rewrite', 'name', 'visibility', 'created_at',
        ], [
            'type' => '=',
            'category' => '=',
            'name' => 'LIKE',
            'visibility' => '=',
            'created_at' => 'BETWEEN',
        ], null, [
            'variants',
        ]);

        return response()->json($result);
    }

    public function apiPatchOrCreate()
    {
        //@todo
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
        $result = self::apiSearchTemplate(self::$model, ['id', 'name', 'type']);

        return response()->json([
            'items' => $result->get(),
        ]);
    }
}
