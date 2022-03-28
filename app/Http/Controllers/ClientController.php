<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:clients.index')->only('index');
        $this->middleware('hasPermission:clients.show')->only('show');
        $this->middleware('hasPermission:clients.store')->only('store');
        $this->middleware('hasPermission:clients.update')->only(['edit', 'update']);
        $this->middleware('hasPermission:clients.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Client::orderBy('full_names', 'asc')->paginate(8);
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
            'identification' => 'required|max:20|unique:clients,identification',
            'names' => 'required|max:50',
            'lastname' => 'required|max:50',
            'sex' => 'required|in:Masculino,Femenino',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|max:80',
            'email' => 'nullable|max:80',
            'phonenumber' => 'nullable|max:20'
        ], [], [
            'identification' => 'Identificación',
            'names' => 'Nombres',
            'lastname' => 'Apellidos',
            'sex' => 'Sexo',
            'date_of_birth' => 'Fecha de nacimiento',
            'address' => 'Dirección',
            'email' => 'Email',
            'phonenumber' => 'Teléfono',
        ]);

        $data['full_names'] = $data['names'] . ' ' . $data['lastname'];

        if ($request->hasFile('photo')) {
            $data['photo'] = Storage::putFile('clients/profile', $request->file('photo'));

        }

        Client::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($client)
    {
        return Client::with('memberships.membershipType')
                     ->with('memberships.turn')
                     ->with(['memberships' => function ($query) {
                        $query->orderBy('date_start', 'desc')->get();
                      }])
                     ->with(['bodyMeasurements' => function ($query) {
                        $query->orderBy('date', 'asc')->get();
                      }])
                      ->with('sales.saleDetails:id,sale_id,product_name,quantity,price_unit,price_total')
                      ->with('sales:id,client_id,created_at')
                      ->with(['sales' => function ($query) {
                            $query->orderBy('created_at', 'desc')->get();
                        }])
                     ->where('id', $client)->firstOrFail();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return $client;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'identification' => 'required|max:20|unique:clients,identification,' . $client->id,
            'names' => 'required|max:50',
            'lastname' => 'required|max:50',
            'sex' => 'required|in:Masculino,Femenino',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|max:80',
            'email' => 'nullable|max:80',
            'phonenumber' => 'nullable|max:20'
        ], [], [
            'identification' => 'Identificación',
            'names' => 'Nombres',
            'lastname' => 'Apellidos',
            'sex' => 'Sexo',
            'date_of_birth' => 'Fecha de nacimiento',
            'address' => 'Dirección',
            'email' => 'Email',
            'phonenumber' => 'Teléfono',
        ]);

        $data['full_names'] = $data['names'] . ' ' . $data['lastname'];

        if ($request->hasFile('photo')) {
            $data['photo'] = Storage::putFile('clients/profile', $request->file('photo'));
            if (Storage::exists($client->photo))  {
                Storage::delete($client->photo);
            }
        }

        $client->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        if (Storage::exists($client->photo))  {
            Storage::delete($client->photo);
        }
        $client->delete();
    }
}
