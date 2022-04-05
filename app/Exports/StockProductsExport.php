<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\InventoryProduct;

class StockProductsExport implements FromView
{
    public function __construct ($type) {
        $this->type = $type;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        if ($this->type == 'productsWithLittleStock') {
            $products = InventoryProduct::whereColumn('stock', '<', 'stock_min')->get();
        } else {
            $products = InventoryProduct::whereColumn('stock', '>', 'stock_max')->get();
        }

        return view('reports.stock_products', ['products' => $products, 'type' => $this->type]);
    }
}
