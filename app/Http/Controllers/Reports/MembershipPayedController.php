<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientMembership;
use App\Exports\MembershipsPayedExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class MembershipPayedController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:reports.show');
    }

    public function getMembershipsPayed (Request $request) {
        $request->validate([
            'dateInitial' => 'required|date',
            'dateFinal' => 'required|date|after_or_equal:dateInitial'
        ], [
            'dateFinal.after_or_equal' => 'La fecha final debe ser superior a la fecha inicial'
        ], [
            'dateInitial' => 'Fecha inicial',
            'dateFinal' => 'Fecha final'
        ]);

        $dateFinal = Carbon::createFromDate($request->dateFinal)->addHours(23)->addMinutes(59)->addSeconds(59)->toDateTimeString();

        return ClientMembership::with(['client', 'membershipType', 'turn'])
                                        ->whereBetween('created_at', [$request->dateInitial, $dateFinal])->orderBy('created_at', 'asc')->paginate(8);
    }

    public function getMembershipsPayedInExcel (Request $request) {
        $request->validate([
            'dateInitial' => 'required|date',
            'dateFinal' => 'required|date|after_or_equal:dateInitial'
        ], [
            'dateFinal.after_or_equal' => 'La fecha final debe ser superior a la fecha inicial'
        ], [
            'dateInitial' => 'Fecha inicial',
            'dateFinal' => 'Fecha final'
        ]);

        $dateFinal = Carbon::createFromDate($request->dateFinal)->addHours(23)->addMinutes(59)->addSeconds(59)->toDateTimeString();

        return Excel::download(new MembershipsPayedExport($request->dateInitial, $dateFinal), 'MembershipsPayedExport.xlsx');
    }
}
