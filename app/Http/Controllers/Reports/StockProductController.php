<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryProduct;

class StockProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:reports.show');
    }

    public function getStockProducts (Request $request) {
        $request->validate([
            'type' => 'required',
        ], [], [
            'type' => 'Tipo de consulta',
        ]);

        if ($request->type == 'productsWithLittleStock') {
            $products = InventoryProduct::whereColumn('stock', '<', 'stock_min')->paginate(8);
            $total = InventoryProduct::whereColumn('stock', '<', 'stock_min')->count();
        } else {
            $products = InventoryProduct::whereColumn('stock', '>', 'stock_max')->paginate(8);
            $total = InventoryProduct::whereColumn('stock', '>', 'stock_max')->count();
        }

        return [
            'products' => $products,
            'total' => $total,
        ];
    }
}
