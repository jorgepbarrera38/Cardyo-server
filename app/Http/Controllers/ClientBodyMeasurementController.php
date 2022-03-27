<?php

namespace App\Http\Controllers;

use App\Models\ClientBodyMeasurement;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientBodyMeasurementController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:client_body_measurements.store')->only('store');
        $this->middleware('hasPermission:client_body_measurements.update')->only(['edit', 'update']);
        $this->middleware('hasPermission:client_body_measurements.delete')->only('destroy');
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
            'height' => 'required|numeric|min:1|max:300',
            'weight' => 'required|numeric|min:1|max:300',
            'biceps' => 'required|numeric|min:1|max:300',
            'forearms' => 'required|numeric|min:1|max:300',
            'shoulders' => 'required|numeric|min:1|max:300',
            'chest' => 'required|numeric|min:1|max:300',
            'waist_width' => 'required|numeric|min:1|max:300',
            'width_at_navel_level' => 'required|numeric|min:1|max:300',
            'buttocks_or_hip' => 'required|numeric|min:1|max:300',
            'thigh' => 'required|numeric|min:1|max:300',
            'calves' => 'required|numeric|min:1|max:300',
        ], [], [
            'date' => 'Fecha',
            'height' => 'Altura',
            'weight' => 'Peso',
            'biceps' => 'Bíceps',
            'forearms' => 'Antebrazos',
            'shoulders' => 'Hombros',
            'chest' => 'Pecho',
            'waist_width' => 'Anchura cintura',
            'width_at_navel_level' => 'Anchura a nivel del ombligo',
            'buttocks_or_hip' => 'Glúteos o cadera',
            'thigh' => 'Muslo',
            'calves' => 'Pantorrillas',
        ]);

        $client->bodyMeasurements()->create($data);
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
            'height' => 'required|numeric|min:1|max:300',
            'weight' => 'required|numeric|min:1|max:300',
            'biceps' => 'required|numeric|min:1|max:300',
            'forearms' => 'required|numeric|min:1|max:300',
            'shoulders' => 'required|numeric|min:1|max:300',
            'chest' => 'required|numeric|min:1|max:300',
            'waist_width' => 'required|numeric|min:1|max:300',
            'width_at_navel_level' => 'required|numeric|min:1|max:300',
            'buttocks_or_hip' => 'required|numeric|min:1|max:300',
            'thigh' => 'required|numeric|min:1|max:300',
            'calves' => 'required|numeric|min:1|max:300',
        ], [], [
            'date' => 'Fecha',
            'height' => 'Altura',
            'weight' => 'Peso',
            'biceps' => 'Bíceps',
            'forearms' => 'Antebrazos',
            'shoulders' => 'Hombros',
            'chest' => 'Pecho',
            'waist_width' => 'Anchura cintura',
            'width_at_navel_level' => 'Anchura a nivel del ombligo',
            'buttocks_or_hip' => 'Glúteos o cadera',
            'thigh' => 'Muslo',
            'calves' => 'Pantorrillas',
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
