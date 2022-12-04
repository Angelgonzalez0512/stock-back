<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        //
        $categories = Category::byTerm($request->term)
        ->byUser($request->user_id)
        ->paginateOrNot($request->paginate, $request->per_page);
        return response()->json($categories);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $category =  new Category();
            $category->name = $request->name;
            $category->status = "active";
            $category->created_by = $request->user()->id;
            $category->description = $request->description;
            $category->save();
            return response()->json(["success"=>true ,"category" => $category], 201);
        } catch (Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $category = Category::findOrfail($id);
            return response()->json(["success" => true, "data" => $category], 200);
        } catch (Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 500);
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->status = $request->status;
            $category->description = $request->description;
            $category->save();
            return response()->json(["success" => true, "data" => $category], 200);
        } catch (Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $category=Category::findOrFail($id);
            $productExist=$category->products()->count();
            if($productExist>0){
                throw new Exception("No se puede eliminar la categoria porque tiene productos asociados");
            }
            $category->delete();
            return response()->json(["success"=>true,"message"=>"Category deleted successfully"],200);
        }catch(Exception $e){
            return response()->json(["success" => false, "message" => $e->getMessage()], 500);
        }
    }
}
