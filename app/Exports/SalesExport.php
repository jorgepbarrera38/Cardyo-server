<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesExport implements FromView
{

    public function __construct ($dateInitial, $dateFinal, $status, $type) {
        $this->dateInitial = $dateInitial;
        $this->dateFinal = $dateFinal;
        $this->status = $status;
        $this->type = $type;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        if (json_decode($this->status)) {
            $sales = Sale::with(['client', 'saleDetails'])->whereBetween('created_at', [$this->dateInitial, $this->dateFinal])
                                            ->orderBy('created_at', 'asc')->get();
        } else {
            $sales = Sale::with(['client', 'saleDetails'])->whereBetween('created_at', [$this->dateInitial, $this->dateFinal])->where('status', 'Confirmado')
                        ->orderBy('created_at', 'asc')->get();
        }

        if ($this->type == 'summarized') {
            return view('reports.sales', ['sales' => $sales]);
        } else {
            return view('reports.sales_detailed', ['sales' => $sales]);
        }
        
    }
}
