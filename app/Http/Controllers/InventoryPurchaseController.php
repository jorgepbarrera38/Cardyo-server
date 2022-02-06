<?php

namespace App\Http\Controllers;

use App\Models\InventoryPurchase;
use App\Models\InventoryProvider;
use App\Models\InventoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return InventoryPurchase::with('provider')->orderBy('created_at', 'desc')->paginate(8);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataToCreatePurchase()
    {
        $inventoryProviders = InventoryProvider::orderBy('name', 'asc')->get();
        $inventoryProducts = InventoryProduct::orderBy('name', 'asc')->get();

        return [
            'inventoryProviders' => $inventoryProviders,
            'inventoryProducts' => $inventoryProducts,
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
            return response()->json(['errors' => ['message' => ['No ha agregado productos']]], 422);
        }

        $data = $request->validate([
            'inventory_provider_id' => 'required|exists:inventory_providers,id',
            'bill' => 'required|max:30|unique:inventory_purchases,bill',
            'date_bill' => 'required|date',
            'observations' => 'required|max:500',

            'details' => 'array',

            'details.*.inventory_product_id' => 'required|exists:inventory_products,id',
            'details.*.quantity' => 'required|numeric|min:1|max:1000000',
            'details.*.price_unit' => 'required|numeric|min:1|max:100000000',
        ], [], [
            'inventory_provider_id' => 'Proveedor',
            'bill' => 'Factura',
            'date_bill' => 'Fecha factura',
            'observations' => 'Observaciónes',
        ]);

        $total = 0;

        foreach ($data['details'] as $index => $detail) {
            $total +=  $detail['quantity'] * $detail['price_unit'];
            $data['details'][$index]['price_total'] = $detail['quantity'] * $detail['price_unit'];
        }

        $data['total'] = $total;

        try {
            DB::beginTransaction();

            $inventoryPurchase = InventoryPurchase::create($data);
            $inventoryPurchase->purchaseDetails()->createMany($data['details']);

            foreach ($data['details'] as $detail) {
                $productDB = InventoryProduct::findOrFail($detail['inventory_product_id']);
                $productDB->update(['stock' => $productDB->stock + $detail['quantity']]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['errors' => ['message' => ['La compra no se pudo registrar debido a un error en el servidor']]], 422);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryPurchase  $inventoryPurchase
     * @return \Illuminate\Http\Response
     */
    public function show($inventoryPurchase)
    {
        return InventoryPurchase::with(['provider', 'purchaseDetails.product'])->where('id', $inventoryPurchase)->firstOrFail();
    }
}
