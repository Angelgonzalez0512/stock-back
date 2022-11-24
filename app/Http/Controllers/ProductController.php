<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $products = Product::with("category")->byTerm($request->term)->byCategory($request->category)->paginateOrNot($request->paginate, $request->per_page);
            return response()->json($products, 200);
        }catch(Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

            $product=new Product();
            $product->name=$request->name;
            $product->barcode=$request->barcode;
            $product->price=$request->price;
            $product->presentation_quantity=0;
            $product->presentation=$request->presentation;
            $product->stock=$request->stock;
            $product->min_stock=$request->min_stock;
            $product->max_stock=$request->max_stock;
            $product->brand=$request->brand;
            $product->category_id=$request->category_id;
            $product->unit=$request->unit;
            $product->created_by=$request->user()->id;
            $product->description=$request->description;
            $product->save();
            return response()->json([
                "success" => true,
                "message" => "Product created successfully"
            ], 200);
        }catch(Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
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
        try{
            $product=Product::findOrFail($id);
            return response()->json(["success"=>true,"data"=>$product],200);

        }catch(Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
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
        try{
            $product=Product::find($id);
            $product->name=$request->name;
            $product->barcode=$request->barcode;
            $product->price=$request->price;
            $product->presentation_quantity=0;
            $product->presentation=$request->presentation;
            $product->stock=$request->stock;
            $product->min_stock=$request->min_stock;
            $product->max_stock=$request->max_stock;
            $product->brand=$request->brand;
            $product->category_id=$request->category_id;
            $product->unit=$request->unit;
            $product->description=$request->description;
            $product->save();
            return response()->json([
                "success" => true,
                "message" => "Product updated successfully"
            ], 200);
        }catch(Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
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
            $product=Product::findOrFail($id);
            $product->delete();
            return response()->json(["success"=>true,"message"=>"Product deleted successfully"],200);
        }catch(Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
