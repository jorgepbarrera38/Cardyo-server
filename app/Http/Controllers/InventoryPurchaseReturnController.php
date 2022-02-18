<?php

namespace App\Http\Controllers;

use App\Models\InventoryPurchaseReturn;
use App\Models\inventoryPurchase;
use App\Models\InventoryPurchaseDetail;
use App\Models\InventoryPurchaseReturnDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryPurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return InventoryPurchaseReturn::with('purchase')->orderBy('id', 'desc')->paginate(8);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataToCreatePurchase()
    {
        $inventoryPurchases = inventoryPurchase::with('purchaseDetails.product')
                                               ->with('purchaseDetails.purchaseReturnDetails')
                                                ->orderBy('created_at', 'desc')->get();

        return [
            'inventoryPurchases' => $inventoryPurchases,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!count($request->details)) {
            return response()->json(['errors' => ['message' => ['No hay productos con cantidades para devolver']]], 422);
        }

        $data = $request->validate([
            'inventory_purchase_id' => 'required|exists:inventory_purchases,id',
            'observations' => 'required|max:500',

            'details' => 'array',

            'details.*.inv_purchase_detail_id' => 'required|exists:inventory_purchase_details,id',
            'details.*.quantity' => 'required|numeric|min:1|max:10000000',
        ], [], [
            'inventory_purchase_id' => 'Compra',
            'observations' => 'Observaciónes ',
        ]);

        //Verificando que el detalle de la campra tenga cantidad para devolver
        foreach ($data['details'] as $detail) {
            $inventoryPurchaseDetail = InventoryPurchaseDetail::with('product')->findOrFail($detail['inv_purchase_detail_id']);
            $totalDetailQuantityReturned = InventoryPurchaseReturnDetail::where('inv_purchase_detail_id', $detail['inv_purchase_detail_id'])->sum('quantity');
            
            //Verificando que el detalle de la campra tenga cantidad para devolver
            if (($totalDetailQuantityReturned + $detail['quantity']) > $inventoryPurchaseDetail->quantity) {
                return response()->json(['errors' => ['message' => ['La cantidad a devolver del producto "'. $inventoryPurchaseDetail->product->name .'" supera la cantidad disponible para devolver en la compra']]], 422);
            }

            //Verificando que el producto tenga stock para devolver
            if (($detail['quantity']) > $inventoryPurchaseDetail->product->stock) {
                return response()->json(['errors' => ['message' => ['La cantidad a devolver del producto "'. $inventoryPurchaseDetail->product->name .'" supera la cantidad disponible en el stock del producto']]], 422);
            }
        }

       try {
            DB::beginTransaction();

            $inventoryPurchaseReturn = InventoryPurchaseReturn::create($data);
            $inventoryPurchaseReturn->purchaseReturnDetails()->createMany($data['details']);

            foreach ($data['details'] as $detail) {
                $inventoryPurchaseDetail = InventoryPurchaseDetail::with('product')->findOrFail($detail['inv_purchase_detail_id']);
                $inventoryPurchaseDetail->product()->update(['stock' => $inventoryPurchaseDetail->product->stock - $detail['quantity']]);
            }

           DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['errors' => ['message' => ['La devolución de compra no se pudo registrar debido a un error en el servidor']]], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryPurchaseReturn  $inventoryPurchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function show($inventoryPurchaseReturn)
    {
        return InventoryPurchaseReturn::with(['purchase.provider', 'purchaseReturnDetails.inventoryPurchaseDetail.product'])->where('id', $inventoryPurchaseReturn)->firstOrFail();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryPurchaseReturn  $inventoryPurchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryPurchaseReturn $inventoryPurchaseReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryPurchaseReturn  $inventoryPurchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryPurchaseReturn $inventoryPurchaseReturn)
    {
        //
    }
}