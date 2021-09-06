<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();

        return $category;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CreateCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $input = $request->all();
        $categoryName = $input['name'];

        $category = Category::where('name', $categoryName)->get();

        if(!$category->isEmpty()) return abort(404, 'Category Already exists.');

        // http_response_code(500);
        // var_dump($category->isEmpty());
        // dd('ssssssss');
        
        $newCategory = Category::create([
            'name' => $categoryName
        ]);

        return $newCategory;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request)
    {
        $input = $request->all();

        $category = Category::where('id', $input['id'])->get();

        if($category->isEmpty()) return abort(404, 'Category not found.');

        $UpdatedCategory = Category::where('id', $input['id'])
        ->update(['name' => $input['name']]);

        return $UpdatedCategory;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        \App\Models\Post::where('category_id', $category['id'])->update(['category_id' => null]);
        $category->delete();

        return $category['id'];
    }
}
