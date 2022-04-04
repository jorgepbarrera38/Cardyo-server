<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryTweak;
use Carbon\Carbon;

class InventoryTweakController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:reports.show');
    }

    public function getInventoryTweaks (Request $request) {
        $request->validate([
            'dateInitial' => 'required|date',
            'dateFinal' => 'required|date|after_or_equal:dateInitial',
            //'status' => 'required|boolean',
        ], [
            'dateFinal.after_or_equal' => 'La fecha final debe ser superior a la fecha inicial'
        ], [
            'dateInitial' => 'Fecha inicial',
            'dateFinal' => 'Fecha final'
        ]);

        $dateFinal = Carbon::createFromDate($request->dateFinal)->addHours(23)->addMinutes(59)->addSeconds(59)->toDateTimeString();

        $inventoryTweaks = InventoryTweak::whereBetween('created_at', [$request->dateInitial, $dateFinal])
                                        ->orderBy('created_at', 'asc')->paginate(8);
        return $inventoryTweaks;

    }
}
