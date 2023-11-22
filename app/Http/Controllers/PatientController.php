<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    private $patient;
    private $medication;

    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
    }

    public function index()
    {
        $all_patients = $this->patient->all();

        return view('patients.index')
                ->with('all_patients', $all_patients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->patient->name = $request->patient_name;
        $this->patient->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
