<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientMembership;
use App\Models\Turn;
use App\Models\MembershipType;
use App\Models\ClientMembershipAssistance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClientMembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function index(Client $client)
    {
        return $client->memberships()->paginate(8);
    }*/

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientMembership  $clientMembership
     * @return \Illuminate\Http\Response
     */
    public function getDataToCreateMembership(ClientMembership $clientMembership)
    {
        $turns = Turn::orderBy('name')->get();
        $membershipTypes = MembershipType::orderBy('name')->get();

        return [
            'turns' => $turns,
            'membershipTypes' => $membershipTypes,
        ];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Client $client, Request $request)
    {
        $data = $request->validate([
            'turn_id' => 'required|exists:turns,id',
            'membership_type_id' => 'required|exists:membership_types,id',
            'quantity' => 'required|numeric|min:1|max:1000',
            'date_start' => 'required|date',
            'observations' => 'nullable|max:500',
        ], [], [
            'turn_id' => 'Turno',
            'membership_type_id' => 'Tipo de membresía',
            'quantity' => 'Cantidad',
            'date_start' => 'Fecha de inicio',
            'observations' => 'Observaciónes',
        ]);

        $membershipType = MembershipType::find($data['membership_type_id']);
        $data['price'] = $membershipType->price;
        $data['total_price'] = $data['price'] * $data['quantity'];

        if ($membershipType->type == 'Meses') {
            $data['date_finish'] = \Carbon\Carbon::createFromDate($data['date_start'])->addMonths($membershipType->quantity * $data['quantity'])->subDays(1);
        } else {
            $data['date_finish'] = \Carbon\Carbon::createFromDate($data['date_start'])->addDays($membershipType->quantity * $data['quantity'])->subDays(1);
        }


        $client->memberships()->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientMembership  $clientMembership
     * @return \Illuminate\Http\Response
     */
    public function show(ClientMembership $clientMembership)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientMembership  $clientMembership
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientMembership $client_membership)
    {
        return $client_membership;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientMembership  $clientMembership
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientMembership $client_membership)
    {
        //Verificando si se encuentra finalizada
        $membershipIsFinished = Carbon::createFromDate($client_membership->date_finish)
                                                ->addHours(23)->addMinutes(59)->addSeconds(59)
                                                ->isPast();

       if ($membershipIsFinished) {
           return response()->json(['errors' => ['message' => ['La membresía no puede actualizar ya que se encuentra finalizada']]], 422);
       }    

        $data = $request->validate([
            'turn_id' => 'required|exists:turns,id',
            'membership_type_id' => 'required|exists:membership_types,id',
            'quantity' => 'required|numeric|min:1|max:1000',
            'date_start' => 'required|date',
            'observations' => 'nullable|max:500',
        ], [], [
            'turn_id' => 'Turno',
            'membership_type_id' => 'Tipo de membresía',
            'quantity' => 'Cantidad',
            'date_start' => 'Fecha de inicio',
            'observations' => 'Observaciónes',
        ]);

        $membershipType = MembershipType::find($data['membership_type_id']);
        $data['price'] = $membershipType->price;
        $data['total_price'] = $data['price'] * $data['quantity'];

        if ($membershipType->type == 'Meses') {
            $data['date_finish'] = \Carbon\Carbon::createFromDate($data['date_start'])->addMonths($membershipType->quantity * $data['quantity'])->subDays(1);
        } else {
            $data['date_finish'] = \Carbon\Carbon::createFromDate($data['date_start'])->addDays($membershipType->quantity * $data['quantity'])->subDays(1);
        }

        $clientMembershipAssistances = ClientMembershipAssistance::where('date', '<', $data['date_start'])->orWhere('date', '>', $data['date_finish'])->exists();
        
        if ($clientMembershipAssistances) {
            return response()->json(['errors' => ['message' => ['La membresía ya tiene asistencias marcadas que se encuentran por fuera del rango de fechas']]], 422);
        }

        $client_membership->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClientMembership  $clientMembership
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientMembership $client_membership)
    {
        //Verificando si se encuentra finalizada
        $membershipIsFinished = Carbon::createFromDate($client_membership->date_finish)
                                                ->addHours(23)->addMinutes(59)->addSeconds(59)
                                                ->isPast();

       if ($membershipIsFinished) {
           return response()->json(['errors' => ['message' => ['La membresía no puede eliminar ya que se encuentra finalizada']]], 422);
       }      

       //Verificando si se encuentra activa y tiene asistencias
       $membershipIsActiveWithAssistances = ClientMembership::has('clientMembershipAssistances')
                                                            ->where('date_start', '<=', Carbon::now()->toDateString())
                                                            ->where('date_finish', '>=', Carbon::now()->toDateString())
                                                            ->where('id', $client_membership->id)
                                                            ->first();
        
        if ($membershipIsActiveWithAssistances) {
            return response()->json(['errors' => ['message' => ['La membresía no se puede eliminar ya que se encuentra activa y tiene asistencias marcadas']]], 422);
        }
       
       $client_membership->delete();
    }

    public function getAsistancesFromMembership(ClientMembership $client_membership)
    {
        return $client_membership->clientMembershipAssistances;
    }
}
