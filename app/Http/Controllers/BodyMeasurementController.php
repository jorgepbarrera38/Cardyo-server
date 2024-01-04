<?php

namespace App\Http\Controllers;

use App\Models\BodyMeasurement;
use Illuminate\Http\Request;

class BodyMeasurementController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:body_measurements.index')->only('index');
        $this->middleware('hasPermission:body_measurements.store')->only('store');
        $this->middleware('hasPermission:body_measurements.update')->only(['edit', 'update']);
        $this->middleware('hasPermission:body_measurements.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');
        return BodyMeasurement::search($search)->orderBy('name', 'asc')->paginate(8);
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
            'name' => 'required|max:50|unique:body_measurements,name',
        ], [], [
            'name' => 'Nombre',
        ]);

        BodyMeasurement::create($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BodyMeasurement  $bodyMeasurement
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, BodyMeasurement $bodyMeasurement)
    {
        return $bodyMeasurement;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BodyMeasurement  $bodyMeasurement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BodyMeasurement $bodyMeasurement)
    {
        $data = $request->validate([
            'name' => 'required|max:50|unique:body_measurements,name,' . $bodyMeasurement->id,
        ], [], [
            'name' => 'Nombre',
        ]);

        $bodyMeasurement->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BodyMeasurement  $bodyMeasurement
     * @return \Illuminate\Http\Response
     */
    public function destroy(BodyMeasurement $bodyMeasurement)
    {
        $bodyMeasurement->delete();
    }
}