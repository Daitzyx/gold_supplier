<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Http\Requests\ProductRequest;


class ProductController extends Controller
{

    public function index (Request $request)
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    public function show (Request $request, $id) {
        $product = Product::find($id);

        return response()->json($product, 200);
    }

    public function store (ProductRequest $request) {
        try {
            $product = new Product();
            $product->fill($request->all());

            DB::beginTransaction();

            $product->save();
            DB::commit();


            return response()->json($product, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function update (Request $request, $id) {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(["message" => "Product not found"], 404);
            }

            $product->fill($request->all());

            DB::beginTransaction();

            $product->save();
            DB::commit();

            return response()->json($product, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function destroy ($id) {
        try{
            $product = Product::find($id);
            if (!$product) {
                return response()->json(["message" => "Product not found"], 404);
            }

            DB::beginTransaction();

            $product->delete();

            DB::commit();


            return response()->json(["message"=> "Category deleted!"], 200);
        }catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }


}
