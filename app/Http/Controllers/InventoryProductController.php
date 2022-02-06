<?php

namespace App\Http\Controllers;

use App\Models\InventoryProduct;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return InventoryProduct::with('category')->orderBy('name', 'asc')->paginate(8);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataToCreateProduct()
    {
        $inventoryCategory = InventoryCategory::orderBy('name', 'asc')->get();

        return [
            'categories' => $inventoryCategory
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
        $data = $request->validate([
            'name' => 'required|max:40|unique:inventory_products,name',
            'inventory_category_id' => 'required|exists:inventory_categories,id',
            'description' => 'required|max:500',
            'price' => 'required|numeric|min:0|max:10000000',
            'stock_min' => 'required|numeric|min:0|max:100000',
            'stock_max' => 'required|numeric|min:0|max:100000',
        ], [], [
            'name' => 'Nombre',
            'inventory_category_id' => 'Categoría',
            'description' => 'Descripción',
            'price' => 'Precio de venta',
            'stock_min' => 'Stock mínimo',
            'stock_max' => 'Stock máximo',
        ]);

        $data['stock'] = 0;

        if ($request->hasFile('photo')) {
            $data['photo'] = Storage::putFile('inventories/products', $request->file('photo'));

        }

        InventoryProduct::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryProduct  $inventoryProduct
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryProduct $inventoryProduct)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryProduct  $inventoryProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryProduct $inventoryProduct)
    {
        return $inventoryProduct;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryProduct  $inventoryProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryProduct $inventoryProduct)
    {
        $data = $request->validate([
            'name' => 'required|max:40|unique:inventory_products,name,' . $inventoryProduct->id,
            'inventory_category_id' => 'required|exists:inventory_categories,id',
            'description' => 'required|max:500',
            'price' => 'required|numeric|min:0|max:10000000',
            'stock_min' => 'required|numeric|min:0|max:100000',
            'stock_max' => 'required|numeric|min:0|max:100000',
        ], [], [
            'name' => 'Nombre',
            'inventory_category_id' => 'Categoría',
            'description' => 'Descripción',
            'price' => 'Precio de venta',
            'stock_min' => 'Stock mínimo',
            'stock_max' => 'Stock máximo',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = Storage::putFile('clients/profile', $request->file('photo'));
            if (Storage::exists($inventoryProduct->photo))  {
                Storage::delete($inventoryProduct->photo);
            }
        }

        $inventoryProduct->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryProduct  $inventoryProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryProduct $inventoryProduct)
    {
        if (Storage::exists($inventoryProduct->photo))  {
            Storage::delete($inventoryProduct->photo);
        }
        $inventoryProduct->delete();
    }
}
