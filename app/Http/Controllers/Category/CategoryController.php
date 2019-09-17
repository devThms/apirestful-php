<?php

namespace App\Http\Controllers\Category;

use App\Model\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Category::all();

        return response()->json([
            'data' => $categorias
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            $errors = $validator->errors();

            return response()->json([
                'ok' => false,
                'message' => 'Error en validación de formulario',
                'errors' => $errors
            ], 400);

        }

        $category = Category::create($request->all());

        return response()->json([
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {

        return response()->json([
            'data' => $category
        ], 200);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            $errors = $validator->errors();

            return response()->json([
                'ok' => false,
                'message' => 'Error en validación de formulario',
                'errors' => $errors
            ], 400);

        }
        
        $category->name = $request->name;
        $category->description = $request->description;

        if ($category->isClean())
        {
            return response()->json([
                'error' => 'Se debe especificar al menos un valor diferente para actualizar',
                'code' => '422'
            ], 422);
        }

        $category->save();

        return response()->json([
            'data' => $category
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'data' => $category
        ], 200);
    }
}
