<?php

namespace App\Http\Controllers;

use App\Models\ClientMembershipAssistance;
use App\Models\ClientMembership;
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

        $totalAssistsToday = ClientMembershipAssistance::whereBetween('date', [$dateInitial, $dateFinish])->count();

        $attendanceExpectedToday = ClientMembership::where('date_start', '<=', $dateInitial)
                                                    ->where('date_finish', '>=', $dateInitial)
                                                    ->count();

        return [
            'totalAssistsToday' => $totalAssistsToday,
            'attendanceExpectedToday' => $attendanceExpectedToday,
        ];
    }
}
