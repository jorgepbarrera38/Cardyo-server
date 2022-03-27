<?php

namespace App\Http\Controllers;

use App\Models\InventoryProvider;
use Illuminate\Http\Request;

class InventoryProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:inventory_providers.index')->only('index');
        $this->middleware('hasPermission:inventory_providers.store')->only('store');
        $this->middleware('hasPermission:inventory_providers.update')->only(['edit', 'update']);
        $this->middleware('hasPermission:inventory_providers.delete')->only('destroy');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return InventoryProvider::orderBy('name', 'asc')->paginate(8);
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
            'code' => 'required|max:30|unique:inventory_providers,code',
            'name' => 'required|max:90|unique:inventory_providers,name',
        ], [], [
            'code' => 'Código',
            'name' => 'Nombre',
        ]);

        InventoryProvider::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryProvider  $inventoryProvider
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryProvider $inventoryProvider)
    {
        return $inventoryProvider;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryProvider  $inventoryProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryProvider $inventoryProvider)
    {
        $data = $request->validate([
            'code' => 'required|max:30|unique:inventory_providers,code,' . $inventoryProvider->id,
            'name' => 'required|max:90|unique:inventory_providers,name,' . $inventoryProvider->id,
        ], [], [
            'code' => 'Código',
            'name' => 'Nombre',
        ]);

        $inventoryProvider->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryProvider  $inventoryProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryProvider $inventoryProvider)
    {
        $inventoryProvider->delete();
    }
}
