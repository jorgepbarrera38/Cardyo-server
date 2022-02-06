<?php

namespace App\Http\Controllers;

use App\Models\InventoryPurchase;
use App\Models\InventoryProvider;
use App\Models\InventoryProduct;
use Illuminate\Http\Request;

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
            'bill' => 'required|max:30',
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

        $inventoryPurchase = InventoryPurchase::create($data);
        $inventoryPurchase->purchaseDetails()->createMany($data['details']);

        return 'Bien';

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryPurchase  $inventoryPurchase
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryPurchase $inventoryPurchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryPurchase  $inventoryPurchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryPurchase $inventoryPurchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryPurchase  $inventoryPurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryPurchase $inventoryPurchase)
    {
        //
    }
}
