<?php

namespace App\Http\Controllers;

use App\Models\InventoryPurchase;
use App\Models\InventoryProvider;
use App\Models\InventoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryPurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:inventory_purchases.index')->only('index');
        $this->middleware('hasPermission:inventory_purchases.store')->only(['store', 'getDataToCreatePurchase']);
        $this->middleware('hasPermission:inventory_purchases.show')->only('show');
        $this->middleware('hasPermission:inventory_purchases.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');
        return InventoryPurchase::search($search)->with('provider')->orderBy('id', 'desc')->paginate(8);
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
            'date_bill' => 'required|date|before:tomorrow',
            'observations' => 'required|max:500',

            'details' => 'array',

            'details.*.inventory_product_id' => 'required|exists:inventory_products,id',
            'details.*.quantity' => 'required|numeric|min:1|max:1000000',
            'details.*.price_unit' => 'required|numeric|min:1|max:100000000',
        ], [
            'date_bill.before' => 'La fecha de la factura debe ser menor o igual a la de hoy'
        ], [
            'inventory_provider_id' => 'Proveedor',
            'bill' => 'Factura',
            'date_bill' => 'Fecha factura',
            'observations' => 'Observaciónes',
        ]);

        $existsPurchaseWithBillAndConfirmed = InventoryPurchase::where('bill', $data['bill'])->where('status', 'Confirmado')->exists();

        if ($existsPurchaseWithBillAndConfirmed) {
            return response()->json(['errors' => ['message' => ['Ya existe una compra confirmada con el número de factura']]], 422);
        }

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
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['message' => ['La compra no se pudo registrar debido a un error en el servidor: ' . $e->getMessage()]]], 422);
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
        return InventoryPurchase::with(['provider', 'purchaseDetails.product', 'purchaseDetails.purchaseReturnDetails.inventoryPurchaseReturn'])
                                ->where('id', $inventoryPurchase)->firstOrFail();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryPurchase $inventoryPurchase)
    {
        if ($inventoryPurchase->status == 'Anulado') {
            return response()->json(['errors' => ['not_products' => ["La compra ya se encuentra anulada"]]], 422);
        }

        foreach ($inventoryPurchase->purchaseDetails as $purchaseDetail) {
            $productDB = InventoryProduct::findOrFail($purchaseDetail['inventory_product_id']);
            if ($purchaseDetail['quantity'] > $productDB->stock) {
                return response()->json(['errors' => ['not_products' => ["La cantidad del producto \"$productDB->name\" supera la cantidad disponible en stock($productDB->stock)"]]], 422);
            }            
        }

        try {
            DB::beginTransaction();
            foreach ($inventoryPurchase->purchaseDetails as $purchaseDetail) {
                $productDB = InventoryProduct::findOrFail($purchaseDetail['inventory_product_id']);
                $productDB->stock = $productDB->stock - $purchaseDetail->quantity;
                $productDB->save();
            }

            $inventoryPurchase->update(['status' => 'Anulado']);
           DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['errors' => ['not_products' => ['Ocurrió un error al intentar anular: ' . $e->getMessage()]]], 422);
        }
    }
}
