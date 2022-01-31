<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientMembership;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AssistanceController extends Controller
{
    public function getActiveMemberships (Request $request) {

        $clientExists = Client::where('identification', $request->identification)->exists();

        if (!$clientExists) {
            return response()->json(['errors' => ['message' => ['El cliente no existe']]], 422);
        }

        $now = Carbon::now()->startOfDay()->toDateTimeString();

        $clientHasActiveMemberships = Client::whereHas('memberships', function (Builder $query) use ($now) {
                        $query->where('date_start', '<=', $now)
                                ->where('date_finish', '>=', $now);
                    })->where('identification', $request->identification)->exists();

        if (!$clientHasActiveMemberships) {
            return response()->json(['errors' => ['message' => ['El cliente no tiene membresías activas']]], 422);
        }

        return Client::with(['memberships' => function ($query) use ($now) {
            $query->where('date_start', '<=', $now)
                    ->where('date_finish', '>=', $now);
        }])->where('identification', $request->identification)
        ->first(['id', 'identification', 'full_names', 'photo']);

    }
}
