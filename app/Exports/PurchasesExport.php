<?php

namespace App\Exports;

use App\Models\InventoryPurchase;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Log;

class PurchasesExport implements FromView
{

    public function __construct ($dateInitial, $dateFinal, $type, $status) {
        $this->dateInitial = $dateInitial;
        $this->dateFinal = $dateFinal;
        $this->type = $type;
        $this->status = $status;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        if (json_decode($this->status)) {
            $purchases = InventoryPurchase::with(['provider', 'purchaseDetails.product'])->whereBetween('created_at', [$this->dateInitial, $this->dateFinal])
                                            ->orderBy('created_at', 'asc')->get();
        } else {
            $purchases = InventoryPurchase::with(['provider', 'purchaseDetails.product'])->whereBetween('created_at', [$this->dateInitial, $this->dateFinal])->where('status', 'Confirmado')
                        ->orderBy('created_at', 'asc')->get();

        }

        if ($this->type == 'summarized') {
            return view('reports.purchases', ['purchases' => $purchases]);                                        
        } else {
            return view('reports.purchases_detailed', ['purchases' => $purchases]);                                        
        }
        
    }
}
