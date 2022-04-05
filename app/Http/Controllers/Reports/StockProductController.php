<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryProduct;
use App\Exports\StockProductsExport;
use Maatwebsite\Excel\Facades\Excel;

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

    public function getStockProductsInExcel (Request $request) {
        $request->validate([
            'type' => 'required',
        ], [], [
            'type' => 'Tipo de consulta',
        ]);

        return Excel::download(new StockProductsExport($request->type), 'getStockProducts.xlsx');
    }
}
