<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:company.index')->only(['show', 'store']);
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
            'name' => 'required|max:100',
        ], [], [
            'name' => 'Nombre',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = Storage::putFile('company', $request->file('photo'));

            if (Company::exists()) {
                if (Storage::exists(Company::first()->photo))  {
                    Storage::delete(Company::first()->photo);
                }
            }
        }


        Company::updateOrCreate([], $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return Company::first();
    }
}
