<?php

namespace App\Http\Controllers;

use App\Models\Turn;
use Illuminate\Http\Request;

class TurnController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:turns.index')->only('index');
        $this->middleware('hasPermission:turns.store')->only('store');
        $this->middleware('hasPermission:turns.update')->only(['edit', 'update']);
        $this->middleware('hasPermission:turns.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');
        return Turn::search($search)->orderBy('name', 'asc')->paginate(8);
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
            'name' => 'required|max:50|unique:turns,name',
            'time_start' => 'required',
            'time_finish' => 'required'
        ], [], [
            'name' => 'Nombre',
            'time_start' => 'Hora inicial',
            'time_finish' => 'Hora final',
        ]);

        Turn::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Turn  $turn
     * @return \Illuminate\Http\Response
     */
    public function edit(Turn $turn)
    {
        return $turn;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Turn  $turn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Turn $turn)
    {
        $data = $request->validate([
            'name' => 'required|max:50|unique:turns,name,' . $turn->id,
            'time_start' => 'required',
            'time_finish' => 'required'
        ], [], [
            'name' => 'Nombre',
            'time_start' => 'Hora inicial',
            'time_finish' => 'Hora final',
        ]);

        $turn->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Turn  $turn
     * @return \Illuminate\Http\Response
     */
    public function destroy(Turn $turn)
    {
        $turn->delete();
    }
}