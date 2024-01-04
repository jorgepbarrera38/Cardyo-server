<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientBodyMeasurement;
use App\Models\BodyMeasurement;
use Illuminate\Http\Request;

class ClientBodyMeasurementController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:client_body_measurements.store')->only('store');
        $this->middleware('hasPermission:client_body_measurements.update')->only(['edit', 'update']);
        $this->middleware('hasPermission:client_body_measurements.delete')->only('destroy');
    }

    public function getDataToRegisterBodyMeasurement () {
        return BodyMeasurement::orderBy('name', 'asc')->get();
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
            'date' => 'required|date',
            'body_measurement_id' => 'required|exists:body_measurements,id',
            'value' => 'required|numeric|min:1|max:1000',
        ], [], [
            'date' => 'Fecha',
            'body_measurement_id' => 'Medida corporal',
            'value' => 'Valor',
        ]);

        $client->clientBodyMeasurements()->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientBodyMeasurement  $clientBodyMeasurement
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientBodyMeasurement $client_body_measurement)
    {
        return $client_body_measurement;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientBodyMeasurement  $clientBodyMeasurement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientBodyMeasurement $client_body_measurement)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'body_measurement_id' => 'required|exists:body_measurements,id',
            'value' => 'required|numeric|min:1|max:1000',            
        ], [], [
            'date' => 'Fecha',
            'body_measurement_id' => 'Medida corporal',
            'value' => 'Valor',            
        ]);

        $client_body_measurement->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClientBodyMeasurement  $clientBodyMeasurement
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientBodyMeasurement $client_body_measurement)
    {
        $client_body_measurement->delete();
    }
}
