<?php

namespace App\Exports;

use App\Models\ClientMembershipAssistance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AssistancesExport implements FromView
{

    public function __construct ($dateInitial, $dateFinal) {
        $this->dateInitial = $dateInitial;
        $this->dateFinal = $dateFinal;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $assistances = ClientMembershipAssistance::with(['clientMembership.client', 'clientMembership.membershipType', 'turn'])
                                        ->whereBetween('date', [$this->dateInitial, $this->dateFinal])
                                        ->orderBy('date', 'asc')->get();
        
        return view('reports.assistances', ['assistances' => $assistances]);                                        
    }
}
