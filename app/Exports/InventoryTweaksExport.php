<?php

namespace App\Exports;

use App\Models\ClientMembershipAssistance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\InventoryTweak;

class InventoryTweaksExport implements FromView
{

    public function __construct ($dateInitial, $dateFinal, $type) {
        $this->dateInitial = $dateInitial;
        $this->dateFinal = $dateFinal;
        $this->type = $type;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $inventoryTweaks = InventoryTweak::with(['tweakDetails.product'])->whereBetween('created_at', [$this->dateInitial, $this->dateFinal])
                                            ->orderBy('created_at', 'asc')->get();
        
        if ($this->type == 'summarized') {
            return view('reports.inventory_tweaks', ['inventoryTweaks' => $inventoryTweaks]);
        } else {
            return view('reports.inventory_tweaks_detailed', ['inventoryTweaks' => $inventoryTweaks]);
        }

    }
}
