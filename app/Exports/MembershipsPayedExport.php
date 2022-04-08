<?php

namespace App\Exports;

use App\Models\ClientMembership;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MembershipsPayedExport implements FromView
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
        $membershipsPayed = ClientMembership::with(['client', 'membershipType', 'turn'])
                                            ->whereBetween('created_at', [$this->dateInitial, $this->dateFinal])
                                            ->orderBy('created_at', 'asc')->get();
        
        return view('reports.memberships_payed', ['membershipsPayed' => $membershipsPayed]);                                        
    }
}
