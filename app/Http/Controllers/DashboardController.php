<?php

namespace App\Http\Controllers;

use App\Models\ClientMembershipAssistance;
use App\Models\ClientMembership;
use App\Models\Company;
use App\Models\InventoryProduct;
use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public Function getData () {
        $dateInitial = Carbon::now()->toDateString();
        $dateFinish = Carbon::createFromDate(Carbon::now()->toDateString())
                                                    ->addHours(23)
                                                    ->addMinutes(59)
                                                    ->addSeconds(59)
                                                    ->addMilliseconds(999)
                                                    ->toDateTimeString();

        $company = Company::first();

        $totalAssistsToday = ClientMembershipAssistance::whereBetween('date', [$dateInitial, $dateFinish])->count();
        
        $attendanceExpectedToday = ClientMembership::where('date_start', '<=', $dateInitial)
                                                    ->where('date_finish', '>=', $dateInitial)
                                                    ->count();
        
        $productsWithStockBelowStockmin = InventoryProduct::whereColumn('stock', '<', 'stock_min')->get(['id', 'name', 'stock_min', 'stock']);
        $productsWithStockOverStockmax = InventoryProduct::whereColumn('stock', '>', 'stock_max')->get(['id', 'name', 'stock_max', 'stock']);

        $totalSalesToday = Sale::whereBetween('created_at', [$dateInitial, $dateFinish])->sum('total');

        return [
            'company' => $company,
            'totalAssistsToday' => $totalAssistsToday,
            'attendanceExpectedToday' => $attendanceExpectedToday,
            'productsWithStockBelowStockmin' => $productsWithStockBelowStockmin,
            'productsWithStockOverStockmax' => $productsWithStockOverStockmax,
            'totalSalesToday' => $totalSalesToday
        ];
    }
}
