<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Medication;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    private $prescription;
    private $patient;
    private $medication;

    public function __construct(Prescription $prescription, Patient $patient, Medication $medication)
    {
        $this->prescription = $prescription;
        $this->patient = $patient;
        $this->medication = $medication;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $patient = $this->patient->findOrFail($id);
        $all_medications = $this->medication->all();

        return view('prescriptions.create')
                ->with('patient', $patient)
                ->with('all_medications', $all_medications);
                // ->with('all_prescriptions', $all_prescriptions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $patient = $this->patient->findOrFail($id);

        $this->prescription->medication_id = $request->medication;
        $this->prescription->patient_id = $patient->id;
        $this->prescription->breakfast = $request->breakfast;
        $this->prescription->lunch = $request->lunch;
        $this->prescription->dinner = $request->dinner;
        $this->prescription->bedtime = $request->bedtime;
        $this->prescription->duration = $request->duration;
        $this->prescription->remaining_quantity = $request->remaining_quantity;
        $this->prescription->save();

        return redirect()->back();
    }

    public function editDurationAndRemainingQuantity($id)
    {
        $patient = $this->patient->findOrFail($id);

        return view('prescriptions.enter')
                ->with('patient', $patient);
    }

    //update duration and remaining quanities / calculate $min_duration, $rivised_medications, $total_dose
    public function updateAndCalculatePrescriptions(Request $request, $patient_id)
    {
        $patient = $this->patient->findOrFail($patient_id);

        $this->updateDuratioin($request, $patient_id);
        $this->updateRemainingQuantites($request, $patient_id);

        $calculations = $this->calculatePrescriptions($patient_id);
        $min_duration = $this->findMinimumDuration($calculations);
        $revised_medications = $this->calculateRevisedMedications($patient_id, $min_duration);
        $total_dose = $this->calculateTotalDose($patient_id);

        return view('prescriptions.calculated_result')
                            ->with('patient', $patient)
                            ->with('calculations', $calculations)
                            ->with('min_duration', $min_duration)
                            ->with('revised_medications', $revised_medications)
                            ->with('total_dose', $total_dose);
    }

    // update remaining_quantity( prescriptions table )
    public function updateRemainingQuantites($request, $patient_id)
    {
        $remaining_quantities = $request->remaining_quantities;
        foreach ($remaining_quantities as $prescription_id => $quantity) {
            $prescription = Prescription::where('patient_id', $patient_id)
                                            ->where('id', $prescription_id)
                                            ->first();
            if ($prescription) {
                $prescription->remaining_quantity = $quantity;
                $prescription->save();
            }
        }
    }

    // update duration( prescriptions table )
    public function updateDuratioin($request, $patient_id)
    {
        $duration_array = $request->duration;
        foreach ($duration_array as $prescription_id => $duration) {
            $prescription = Prescription::where('patient_id', $patient_id)
                                            ->where('id', $prescription_id)
                                            ->first();
            if ($prescription) {
                $prescription->duration = $duration;
                $prescription->save();
            }
        }
    }

    // update remaining_quantity( prescriptions table ) after revising
    public function updateRevisedRemaining(Request $request, $patient_id)
    {
        $patient = $this->patient->findOrFail($patient_id);

        $remaining_quantities = $request->remaining_quantities;
        foreach ($remaining_quantities as $prescription_id => $quantity) {
            $prescription = Prescription::where('patient_id', $patient_id)
                                            ->where('id', $prescription_id)
                                            ->first();
            if ($prescription) {
                $prescription->remaining_quantity = $quantity;
                $prescription->save();
            }
        }

        return redirect()->route('prescription.create', $patient->id);
    }

    public function calculateTotalDose($patient_id)
    {
        $total_dose = Prescription::where('patient_id', $patient_id)
                                    ->selectRaw('SUM(breakfast) as breakfast')
                                    ->selectRaw('SUM(lunch) as lunch')
                                    ->selectRaw('SUM(dinner) as dinner')
                                    ->selectRaw('SUM(bedtime) as bedtime')
                                    ->first();
        return  [
                    'breakfast' => $total_dose->breakfast ?? '0',
                    'lunch' => $total_dose->lunch ?? '0',
                    'dinner' => $total_dose->dinner ?? '0',
                    'bedtime' => $total_dose->bedtime ?? '0'
                ];
    }

    public function calculatePrescriptions($id)
    {
        $patient = Patient::with('prescriptions')->findOrFail($id); //$this->patientに変更できる？
        $calculations = [];

        foreach ($patient->prescriptions as $prescription) {
            $daily_dosing_frequency = $this->getDailyDosingFrequency($prescription);
            $total_daily_dose = ($prescription->breakfast + $prescription->lunch + $prescription->dinner + $prescription->bedtime) * $prescription->duration;
            $duration_including_remaining = (int)(($total_daily_dose + $prescription->remaining_quantity) / $daily_dosing_frequency);

            $calculations[] = [
                'prescription_id' => $prescription->id,
                'duration_including_remaining' => $duration_including_remaining
            ];
        }

        return $calculations;
    }

    public function findMinimumDuration($calculations)
    {
        $min_duration = min(array_column($calculations, 'duration_including_remaining'));
        return $min_duration;
    }

    public function calculateRevisedMedications($patient_id, $min_duration)
    {
        $patient = Patient::with('prescriptions')->findOrFail($patient_id);
        $revised_medications = [];

        foreach ($patient->prescriptions as $prescription) {
            $daily_dose = $prescription->breakfast + $prescription->lunch + $prescription->dinner + $prescription->bedtime;
            $total_required_dose = $daily_dose * $min_duration;
            $revised_remaining_medications =  $daily_dose * $prescription->duration + $prescription->remaining_quantity - $total_required_dose;

            $revised_medications[$prescription->id] = [
                'revised_duration' => $min_duration,
                'total_required_dose' => $total_required_dose,
                'revised_remaining_medications' => $revised_remaining_medications
            ];
        }

        return $revised_medications;
    }




    // public function calculatePrescriptions($id)
    // {
    //     $patient = Patient::with('prescriptions')->findOrFail($id);
    //     $calculations = [];

    //     foreach ($patient->prescriptions as $prescription) {
    //         $calculation_results = $this->calculateRevisedDuration($prescription);
    //         $calculations[$prescription->id] = $calculation_results;
    //     }

    //     return $calculations;
    // }

    // public function calculateRevisedDuration($prescription)
    // {
    //     $daily_dosing_frequency = $this->getDailyDosingFrequency($prescription);
    //     $total_daily_dose = ($prescription->breakfast + $prescription->lunch + $prescription->dinner + $prescription->bedtime) * $prescription->duration;
    //     if ($prescription->remaining_quantity >= $total_daily_dose) {
    //         $revised_duration = 0;
    //         $revised_remaining_medications = $prescription->remaining_quantity - $total_daily_dose;
    //     }else {
    //         $revised_total_dose = $total_daily_dose - $prescription->remaining_quantity;          //調整後の総量(処方総量-残薬)
    //         $revised_duration = (int)($revised_total_dose / $daily_dosing_frequency);
    //         $revised_remaining_medications = $revised_total_dose % $daily_dosing_frequency;       //調整後の残薬
    //     }

    //     return [
    //         'revised_duration' => $revised_duration,
    //         'revised_remaining_medications' => $revised_remaining_medications
    //     ];
    // }

    public function getDailyDosingFrequency($prescription)
    {
        $daily_dosing_frequency = 0;
        if ($prescription->breakfast > 0) {
            $daily_dosing_frequency ++;
        }
        if ($prescription->lunch > 0) {
            $daily_dosing_frequency ++;
        }
        if ($prescription->dinner > 0) {
            $daily_dosing_frequency ++;
        }
        if ($prescription->bedtime > 0) {
            $daily_dosing_frequency ++;
        }
        // $daily_dosing_frequency_count = Prescription::selectRaw('SUM(CASE WHEN breakfast IS NOT NULL THEN 1 ELSE 0 END) as breakfast_count')
        //     ->selectRaw('SUM(CASE WHEN lunch IS NOT NULL THEN 1 ELSE 0 END) as lunch_count')
        //     ->selectRaw('SUM(CASE WHEN dinner IS NOT NULL THEN 1 ELSE 0 END) as dinner_count')
        //     ->selectRaw('SUM(CASE WHEN bedtime IS NOT NULL THEN 1 ELSE 0 END) as bedtime_count')
        //     ->first();

        // $breakfast_count = $daily_dosing_frequency_count->breakfast_count;
        // $lunch_count = $daily_dosing_frequency_count->lunch_count;
        // $dinner_count = $daily_dosing_frequency_count->dinner_count;
        // $bedtime_count = $daily_dosing_frequency_count->bedtime_count;

        // $daily_dosing_frequency = $breakfast_count + $lunch_count + $dinner_count + $bedtime_count;

        return $daily_dosing_frequency;
    }

    public function showCalculatedResult($id)
    {
        $patient = $this->patient->findOrFail($id);
        $calculations = session('calculations', []);

        return view('prescriptions.calculated_result')
                    ->with('calculations', $calculations)
                    ->with('patient', $patient);
    }

    /**
     * Display the specified resource.
     */
    public function show(Prescription $prescription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prescription $prescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prescription $prescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prescription $prescription)
    {
        //
    }
}
