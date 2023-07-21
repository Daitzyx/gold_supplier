<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    public function show ($id) {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(["message" => "Category not found"], 404);
        }
        return response()->json($category, 200);
    }

    public function store (CategoryRequest $request) {
        try {
            $category = new Category();
            $category->fill($request->all());

            DB::beginTransaction();

            $category->save();

            DB::commit();

            return response()->json($category, 201);
        }catch(\Exception $e){
            DB::rollBack();
        }
    }

    public function update (Request $request, $id) {
        try {
            $category = Category::find($id);
            if (!$category) {
                return response()->json(["message" => "Category not found"], 404);
            }

            $category->fill($request->all());

            DB::beginTransaction();

            $category->save();

            DB::commit();

            return response()->json($category, 200);
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function destroy (Request $request, $id) {
        try{
            $category = Category::find($id);

            if (!$category) {
                return response()->json(["message" => "Category not found"], 404);
            }
            DB::beginTransaction();

            $category->delete();
            DB::commit();


            return response()->json(["message"=> "Category deleted!"], 200);
        }catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
