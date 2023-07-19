<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RateListUpdateRequest;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\Category;
use App\Models\Product;

class RateListController extends CoreController {

    public function index(){
        $categories = Category::with(['products'=> function($query){
            $query->get()->keyBy('name');
        }])->get()->keyBy('id');
        return response($categories,200);
    }
    public function update(RateListUpdateRequest $request) {
        $products = Product::all();
        $updatedProducts = $request->all();

        foreach ($updatedProducts as $updatedProduct) {
            $product = $products->find($updatedProduct['id']);
            $product->price = $updatedProduct['price'];
            $product->save();
        }

        return response()->json(['message' => $products], 200);

    }
}
