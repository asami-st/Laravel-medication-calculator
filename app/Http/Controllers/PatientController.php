<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    private $patient;

    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
    }

    // 患者一覧表示
    public function index()
    {
        $all_patients = $this->patient->where('user_id', Auth::user()->id)->get();

        return view('patients.index')
                ->with('all_patients', $all_patients);
    }

    // 患者追加
    public function store(Request $request)
    {
        $request->validate(
            [
                'patient_name' => 'required|max:15'
            ],
            [
                'patient_name.required' => '患者名を入力してください',
                'patient_name.max'      =>  '患者名は１５字以内で入力してください'
            ]
        );
        $this->patient->name = $request->patient_name;
        $this->patient->user_id = Auth::user()->id;
        $this->patient->save();

        return redirect()->back();
    }
}
