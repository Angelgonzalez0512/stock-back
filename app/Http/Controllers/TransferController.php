<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transfer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $transfers = Transfer::with("user")->paginateOrNot($request->paginate, $request->per_page);
            return response()->json($transfers, 200);
        } catch (Exception $e) {
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
        //create transfer 

        DB::beginTransaction();
        try {
            $transfer = new Transfer();
            $transfer->supplier = isset($request->supplier) ? $request->supplier : "";
            $transfer->code = random_int(1000, 20000);
            $transfer->total = $request->total;
            $transfer->tax = 0;
            $transfer->discount = 0;
            $transfer->discount_type = "percent";
            $transfer->operation = $request->operation;
            $transfer->notes = $request->notes;
            $transfer->created_by = $request->user()->id;
            $transfer->save();

            //create transfer details
            foreach ($request->transfer_details as $transfer_detail) {
                $transfer->transfer_details()->create([
                    "product_id" => $transfer_detail["product_id"],
                    "quantity" => $transfer_detail["quantity"],
                    "price" => $transfer_detail["price"],
                    "total" => $transfer_detail["total"],
                    "created_by" => $request->user()->id,
                ]);
                $product = Product::find($transfer_detail["product_id"]);
                if ($transfer->operation == "ingreso") {
                    $product->stock += floatval($transfer_detail["quantity"]);
                } else {
                    $product->stock -= floatval($transfer_detail["quantity"]);
                }
                if($product->stock < 0){
                    return response()->json([
                        "success" => false,
                        "message" => "Stock insuficiente"
                    ], 200);
                }
                $product->save();
            }

            DB::commit();
            return response()->json([
                "success" => true,
                "message" => "Transfer created successfully"
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
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
        try {
            $transfer = Transfer::findOrfail($id);
            $details=$transfer->transfer_details;
            if(count($details)){
                foreach($details as $detail){
                    $detail->product;
                }
            }
            return response()->json(["data" => $transfer, "success" => true], 200);
        } catch (Exception $e) {
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
        DB::beginTransaction();
        try {
            $transfer = Transfer::find($id);
            $oldOperation = $transfer->operation;
            $transfer->supplier = $request->supplier;
            $transfer->code = $request->code;
            $transfer->total = $request->total;
            $transfer->tax = $request->tax;
            $transfer->discount = $request->discount;
            $transfer->discount_type = $request->discount_type;
            $transfer->operation = $request->operation;
            $transfer->notes = $request->notes;
            $transfer->created_by = $request->user()->id;
            $transfer->save();

            //update transfer details

            foreach ($request->transfer_details as $transfer_detail) {
                $transfer->transfer_details()->updateOrCreate([
                    "id" => $transfer_detail["id"]
                ], [
                    "product_id" => $transfer_detail["product_id"],
                    "quantity" => $transfer_detail["quantity"],
                    "price" => $transfer_detail["price"],
                    "total" => $transfer_detail["total"],
                    "created_by" => $request->user()->id,
                ]);
                $product = Product::find($transfer_detail["product_id"]);
                if ($transfer_detail["id"]) {
                    if ($oldOperation == "ingreso") {
                        $product->stock = floatval($product->stock) - floatval($transfer_detail["quantity"]);

                        $product->save();
                    } else {
                        $product->stock = floatval($product->stock) + floatval($transfer_detail["quantity"]);
                        $product->save();
                    }
                }

                if ($transfer->operation == "ingreso") {
                    $product->stock += floatval($transfer_detail["quantity"]);
                } else {
                    $product->stock -= floatval($transfer_detail["quantity"]);
                }
                if($product->stock < 0){
                    return response()->json([
                        "success" => false,
                        "message" => "Stock insuficiente"
                    ], 200);
                }
                $product->save();
            }



            DB::commit();
            return response()->json([
                "success" => true,
                "message" => "Transfer updated successfully"
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
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
        try {
            $transfer = Transfer::find($id);
            $transfer->delete();
            return response()->json([
                "success" => true,
                "message" => "Transfer deleted successfully"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
