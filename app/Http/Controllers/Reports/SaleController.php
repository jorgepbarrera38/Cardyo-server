<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:reports.show');
    }

    public function getSales (Request $request) {
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

        if ($request->boolean('status')) {
            $sales = Sale::with(['client'])->whereBetween('created_at', [$request->dateInitial, $dateFinal])
                                            ->orderBy('created_at', 'asc')->paginate(8);
            $total = Sale::whereBetween('created_at', [$request->dateInitial, $dateFinal])->sum('total');
            return [
                'sales' => $sales,
                'total' => $total,
            ];
        } else {
            $sales = Sale::with(['client'])->whereBetween('created_at', [$request->dateInitial, $dateFinal])->where('status', 'Confirmado')
                        ->orderBy('created_at', 'asc')->paginate(8);
            $total = Sale::whereBetween('created_at', [$request->dateInitial, $dateFinal])->where('status', 'Confirmado')->sum('total');
            return [
                'sales' => $sales,
                'total' => $total,
            ];

        }

    }
}
