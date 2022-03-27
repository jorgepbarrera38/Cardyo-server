<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use Illuminate\Http\Request;

class InventoryCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:inventory_categories.index')->only('index');
        $this->middleware('hasPermission:inventory_categories.store')->only('store');
        $this->middleware('hasPermission:inventory_categories.update')->only(['edit', 'update']);
        $this->middleware('hasPermission:inventory_categories.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return InventoryCategory::orderBy('name', 'asc')->paginate(8);
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
            'name' => 'required|max:30|unique:inventory_categories,name',
        ], [], [
            'name' => 'Nombre',
        ]);

        InventoryCategory::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryCategory  $inventoryCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryCategory $inventoryCategory)
    {
        return $inventoryCategory;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryCategory  $inventoryCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryCategory $inventoryCategory)
    {
        $data = $request->validate([
            'name' => 'required|max:30|unique:inventory_categories,name,' . $inventoryCategory->id,
        ], [], [
            'name' => 'Nombre',
        ]);

        $inventoryCategory->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryCategory  $inventoryCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryCategory $inventoryCategory)
    {
        $inventoryCategory->delete();
    }
}
