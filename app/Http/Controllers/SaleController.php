<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\InventoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:sales.index')->only('index');
        $this->middleware('hasPermission:sales.store')->only(['getDataToRegisterSale', 'store']);
        $this->middleware('hasPermission:sales.show')->only('show');
        $this->middleware('hasPermission:sales.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sale::orderBy('created_at', 'desc')->paginate(8);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function getDataToRegisterSale()
    {
        $clients = Client::orderBy('names', 'ASC')->get();
        $products = InventoryProduct::orderBy('name', 'ASC')->get();

        return [
            'clients' => $clients,
            'products' => $products
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
            return response()->json(['errors' => ['not_products' => ['No ha ingresado productos']]], 422);
        }

        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'details' => 'array',
            'details.*.product_id' => 'required|exists:inventory_products,id',
            'details.*.quantity' => 'required|numeric|min:1|max:1000'
        ], [], [
            'client_id' => 'Cliente',
        ]);

        //Calculando el total de la venta
        $total = 0;
        $productsDetails = [];

        foreach ($request->details as $detail) {
            $productDB = InventoryProduct::find($detail['product_id']);
            if ($detail['quantity'] > $productDB->stock) {
                return response()->json(['errors' => ['not_products' => ["La cantidad del producto \"$productDB->name\" supera la cantidad disponible en stock($productDB->stock)"]]], 422);
            }
            $total += $productDB->price * $detail['quantity'];

            $products[] = [
                'product_id' => $detail['product_id'],
                'product_name' => $productDB->name,
                'price_unit' => $productDB->price,
                'quantity' => $detail['quantity'],
                'price_total' => $productDB->price * $detail['quantity']
            ];
        }

        try {
            DB::beginTransaction();
                //Guardar encabezado detalle de compra
                $sale = Sale::create(['client_id' => $request->client_id, 'total' => $total]);
                //Disminuir cantidades del stock del producto
                foreach ($products as $product) {
                    $productDB = InventoryProduct::find($product['product_id']);
                    $productDB->stock = $productDB->stock - $product['quantity'];
                    $productDB->save();
                }
                //Guardar detalle de compra
                $sale->saleDetails()->createMany($products);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['errors' => ['not_products' => ['Ocurrió un error al intentar guardar']]], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show($sale)
    {
        return Sale::where('id', $sale)->with(['client', 'saleDetails'])->firstOrFail();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();
            foreach ($sale->saleDetails as $saleDetail) {
                $productDB = InventoryProduct::findOrFail($saleDetail['product_id']);
                $productDB->stock = $productDB->stock + $saleDetail->quantity;
                $productDB->save();
            }

            $sale->update(['status' => 'Anulado']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['errors' => ['not_products' => ['Ocurrió un error al intentar eliminar']]], 422);
        }
    }
}
