<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientMembership;
use App\Models\ClientMembershipAssistance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AssistanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:assistances.mark_attendance')->only(['getActiveMemberships', 'markAttendance']);
    }

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

        /*if (!$clientHasActiveMemberships) {
            return response()->json(['errors' => ['message' => ['El cliente no tiene membresías activas']]], 422);
        }*/

        $clientWithInfoMemberships = Client::with('memberships.membershipType')
                        ->with('memberships.turn')
                        ->with('memberships.clientMembershipAssistances')
                        ->with(['memberships' => function ($query) use ($now) {
                        $query->where('date_start', '<=', $now)
                              ->where('date_finish', '>=', $now);
                        }])->where('identification', $request->identification)
                       ->first(['id', 'identification', 'full_names', 'photo']);

        return [
            'clientWithInfoMemberships' => $clientWithInfoMemberships,
            'clientHasActiveMemberships' => $clientHasActiveMemberships
        ];

    }

    public function markAttendance (Request $request) {

        $data = $request->validate([
            'client_membership_id' => 'required|exists:client_memberships,id'
        ]);

        $data['date'] = now();

        //Verificar que la programación esté activa
        $dateToday = now()->startOfDay()->toDateTimeString();

        $membership = ClientMembership::where('id', $data['client_membership_id'])
                                        ->where('date_start', '<=', $dateToday)
                                        ->where('date_finish', '>=', $dateToday)
                                        ->first();
        if (!$membership) {
            return response()->json(['errors' => ['memsbership_id' => ['La membresía no se encuentra activa']]], 422);
        }

        //Verificar que no haya marcado la asistencia con anterioridad
        $clientMembershipHasAssistanceToday = ClientMembershipAssistance::where('client_membership_id', $data['client_membership_id'])
                                                                ->where('date', $dateToday)
                                                                ->first();


        if ($clientMembershipHasAssistanceToday) {
            return response()->json(['errors' => ['memsbership_id' => ['Ya marcó asistencia para el día de hoy']]], 422);
        }

        $clientMembership = ClientMembership::where('id', $data['client_membership_id'])->firstOrFail();

        $data['turn_id'] = $clientMembership->turn_id;

        $data['user_create_id'] = auth()->user()->id;

        ClientMembershipAssistance::create($data);
    }
}
