<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientMembershipAssistance;

class AssistanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:reports.show');
    }

    public function getAssistances (Request $request) {
        $request->validate([
            'dateInitial' => 'required|date',
            'dateFinal' => 'required|date|after_or_equal:dateInitial'
        ], [
            'dateFinal.after_or_equal' => 'La fecha final debe ser superior a la fecha inicial'
        ], [
            'dateInitial' => 'Fecha inicial',
            'dateFinal' => 'Fecha final'
        ]);



        return ClientMembershipAssistance::with(['clientMembership.client', 'clientMembership.membershipType', 'turn'])
                                        ->whereBetween('date', [$request->dateInitial, $request->dateFinal])->orderBy('date', 'asc')->paginate(8);
    }
}
