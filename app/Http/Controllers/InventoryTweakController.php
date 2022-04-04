<?php

namespace App\Http\Controllers;

use App\Models\InventoryTweak;
use App\Models\InventoryTweakDetail;
use App\Models\InventoryProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class InventoryTweakController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:inventory_tweaks.index')->only('index');
        $this->middleware('hasPermission:inventory_tweaks.store')->only(['store', 'getDataToCreateInventoryTweak']);
        $this->middleware('hasPermission:inventory_tweaks.show')->only('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');
        return InventoryTweak::search($search)->orderBy('id', 'desc')->paginate(8);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataToCreateInventoryTweak()
    {
        $inventoryProducts = InventoryProduct::orderBy('name', 'asc')->get();

        return [
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
            'type' => ['required', Rule::in(['Entrada', 'Salida'])],
            'observations' => 'required|max:500',

            'details' => 'array',

            'details.*.inventory_product_id' => 'required|exists:inventory_products,id',
            'details.*.quantity' => 'required|numeric|min:1|max:1000000',
        ], [], [
            'type' => 'Tipo',
            'observations' => 'Observaciónes',
        ]);

        if ($data['type'] == 'Salida') {
            foreach ($data['details'] as $detail) {
                $productDB = InventoryProduct::findOrFail($detail['inventory_product_id']);
                $stockFinal = $productDB->stock - $detail['quantity'];
                if ($stockFinal < 0) {
                    return response()->json(['errors' => ['not_products' => ["La cantidad del producto \"$productDB->name\" supera la cantidad disponible en stock($productDB->stock)"]]], 422);
                }
            }
        }

        $data['user_create_id'] = auth()->user()->id;

        try {
            DB::beginTransaction();

            $inventoryTweak = InventoryTweak::create($data);
            $inventoryTweak->tweakDetails()->createMany($data['details']);

            foreach ($data['details'] as $detail) {
                $productDB = InventoryProduct::findOrFail($detail['inventory_product_id']);
                if ($data['type'] == 'Entrada') {
                    $productDB->update(['stock' => $productDB->stock + $detail['quantity']]);
                } else {
                    $productDB->update(['stock' => $productDB->stock - $detail['quantity']]);
                }
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
     * @param  \App\Models\InventoryTweak  $inventoryTweak
     * @return \Illuminate\Http\Response
     */
    public function show($inventory_tweak)
    {
        return InventoryTweak::with(['tweakDetails.product'])->where('id', $inventory_tweak)->firstOrFail();
    }
}
