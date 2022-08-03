<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Politically_exposed_person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $persons =  Politically_exposed_person::all();
        return response()->json([
            'politically_exposed_persons' => $persons
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required'
        ]);
        
        return Politically_exposed_person::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Politically_exposed_person  $politically_exposed_person
     * @return \Illuminate\Http\Response
     */
    public function show(Politically_exposed_person $politically_exposed_person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Politically_exposed_person  $politically_exposed_person
     * @return \Illuminate\Http\Response
     */
    public function edit(Politically_exposed_person $politically_exposed_person)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Politically_exposed_person  $politically_exposed_person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Politically_exposed_person $politically_exposed_person)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Politically_exposed_person  $politically_exposed_person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Politically_exposed_person $politically_exposed_person)
    {
        //
    }
}
