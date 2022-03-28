<?php

namespace App\Http\Controllers;

use App\Models\MembershipType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MembershipTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:membership_types.index')->only('index');
        $this->middleware('hasPermission:membership_types.store')->only('store');
        $this->middleware('hasPermission:membership_types.update')->only(['edit', 'update']);
        $this->middleware('hasPermission:membership_types.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');
        return MembershipType::search($search)->orderBy('name', 'asc')->paginate(8);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:60|unique:membership_types,name',
            'type' => 'required|in:Meses,Días',
            'quantity' => 'required|numeric|min:1|max:1000',
            'price' => 'required|numeric|min:1|max:10000000',
        ], [], [
            'name' => 'Nombre',
            'type' => 'Tipo',
            'quantity' => 'Cantidad',
            'price' => 'Precio',
        ]);

        MembershipType::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MembershipType  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(MembershipType $membership_type)
    {
        $membershipTypeUsed = $membership_type->memberships()->exists();
        return [
            'membership_type' => $membership_type,
            'membershipTypeUsed' => $membershipTypeUsed,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MembershipType  $membership_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MembershipType $membership_type)
    {
        $data = $request->validate([
            'name' => 'required|max:60|unique:membership_types,name,' . $membership_type->id,
            'type' => 'required|in:Meses,Días',
            'quantity' => 'required|numeric|min:1|max:1000',
            'price' => 'required|numeric|min:1|max:10000000',
        ], [], [
            'name' => 'Nombre',
            'type' => 'Tipo',
            'quantity' => 'Cantidad',
            'price' => 'Precio',
        ]);

        $membershipTypeUsed = $membership_type->memberships()->exists();

        if ($membershipTypeUsed) {
            $membership_type->update($request->only(['price']));    
        } else {
            $membership_type->update($data);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MembershipType  $membership_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(MembershipType $membership_type)
    {
        $membership_type->delete();
    }
}