<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    private $medication;

    public function __construct(Medication $medication)
    {
        $this->medication = $medication;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_medications = $this->medication->all();
        return view('medications.index')
                ->with('all_medications', $all_medications);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->medication->name = $request->medication_name;
        $this->medication->form = $request->medication_form;
        $this->medication->strength = $request->medication_strength;

        $this->medication->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Medication $medication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medication $medication)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $medication = $this->medication->findOrFail($id);

        $medication->name = $request->medication_name;
        $medication->form = $request->medication_form;
        $medication->strength = $request->medication_strength;
        $medication->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->medication->destroy($id);

        return redirect()->back();
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        if (strlen($query) < 3) {
            return response()->json([]);
        }
        $medications = $this->medication->where('name', 'like', '%' . $query . '%')
                                        ->orWhere('form', 'like', '%' . $query . '%')
                                        ->orWhere('strength', 'like', '%' . $query . '%')
                                        ->get();

        $suggestions = $medications->map(function ($medication) {
            return [
                'label' => $medication->name . ' ' . $medication->form . ' ' . $medication->strength,
                'value' => $medication->id
            ];
        });

        return response()->json($suggestions);
    }
}
