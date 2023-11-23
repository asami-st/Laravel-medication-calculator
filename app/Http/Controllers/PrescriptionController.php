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
        $request->validate([
            'medication_id' => 'required|unique:prescriptions,medication_id,NULL,id,patient_id,' . $request->patient_id,
        ]);

        $patient = $this->patient->findOrFail($id);

        $this->prescription->medication_id = $request->medication_id;
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

    public function updatePrescription(Request $request, $id)
    {
        $request->validate([
            'new_breakfast' => 'required|min:0|max:15',
            'new_lunch' => 'required|min:0|max:15',
            'new_dinner' => 'required|min:0|max:15',
            'new_bedtime' => 'required|min:0|max:15',
            'new_duration' => 'required|min:0|max:120',
            'new_remaining_quantity' => 'required|min:0|max:120'
        ]);

        $prescription = $this->prescription->findOrFail($id);
        $prescription->breakfast = $request->new_breakfast;
        $prescription->lunch = $request->new_lunch;
        $prescription->dinner = $request->new_dinner;
        $prescription->bedtime = $request->new_bedtime;
        $prescription->duration = $request->new_duration;
        $prescription->remaining_quantity = $request->new_remaining_quantity;
        $prescription->save();

        return redirect()->back();
    }

    public function destroy($id)
    {
        $this->prescription->destroy($id);

        return redirect()->back();
    }


    public function editDurationAndRemainingQuantity($id)
    {
        $patient = $this->patient->findOrFail($id);

        return view('prescriptions.enter')
                ->with('patient', $patient);
    }

    //update duration and remaining quanities / calculate $min_duration, $rivised_medications, $total_dose
    // public function updateAndCalculatePrescriptions(Request $request, $patient_id)
    // {
    //     $patient = $this->patient->findOrFail($patient_id);

    //     $this->updateDuratioin($request, $patient_id);
    //     $this->updateRemainingQuantites($request, $patient_id);

    //     $calculations = $this->calculatePrescriptions($patient_id);
    //     $min_duration = $this->findMinimumDuration($calculations);
    //     $revised_medications = $this->calculateRevisedMedications($patient_id, $min_duration);
    //     $total_dose = $this->calculateTotalDose($patient_id);

    //     return view('prescriptions.calculated_result')
    //                         ->with('patient', $patient)
    //                         ->with('calculations', $calculations)
    //                         ->with('min_duration', $min_duration)
    //                         ->with('revised_medications', $revised_medications)
    //                         ->with('total_dose', $total_dose);
    // }
    //処方日数と残薬量を一括修正
    public function updateDurationAndRemainingQuantites(Request $request, $patient_id){
        $patient = $this->patient->findOrFail($patient_id);

        $this->updateDuratioin($request, $patient_id);
        $this->updateRemainingQuantites($request, $patient_id);

        return redirect()->route('prescription.create', $patient->id);
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
    // 一包化実施
    public function implementUnitDosePackaging($patient_id)
    {
        $patient = $this->patient->findOrFail($patient_id);

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
    // 残薬調整
    public function adjust($patient_id)
    {
        $patient = $this->patient->findOrFail($patient_id);
        return view('prescriptions.adjust')
                ->with('patient', $patient);
    }
    // 残薬調整の計算
    // public function adjustmentOfRemainingMedication(Request $request, $patient_id)
    // {
    //     $patient = Patient::with('prescriptions')->findOrFail($patient_id);
    //     $adjustments = [];

    //     foreach ($patient->prescriptions as $prescription) {
    //         $daily_dosing_frequency = $this->getDailyDosingFrequency($prescription);
    //         $total_daily_dose = ($prescription->breakfast + $prescription->lunch + $prescription->dinner + $prescription->bedtime) * $prescription->duration;
    //         $total_quantity = $total_daily_dose + $prescription->remaining_quantity;
    //         $max_duration = (int)($total_quantity / $daily_dosing_frequency);
    //         $adjust_duration = $max_duration;
    //         if ($max_duration >= $request->needed_duration) {
    //             $adjust_duration = $request->needed_duration - ($max_duration - $request->needed_duration);
    //         }else {
    //             return ['error' => '特定のエラーメッセージ'];
    //         }

    //         $adjustments[$prescription->id] = [
    //             'prescription_id' => $prescription->id,
    //             'adjust_duration' => $adjust_duration
    //         ];
    //     }

    //     return $adjustments;
    // }

    // 残薬調整後の結果
    public function showAdjustments(Request $request, $patient_id)
    {
        // $patient = $this->patient->findOrFail($patient_id);
        // $adjustments = $this->adjustmentOfRemainingMedication($request, $patient_id);
        $patient = Patient::with('prescriptions')->findOrFail($patient_id);
        $needed_duration = $request->needed_duration;
        $adjustments = [];

        foreach ($patient->prescriptions as $prescription) {
            $daily_dosing_frequency = $this->getDailyDosingFrequency($prescription);
            $remaining_duration = (int)($prescription->remaining_quantity /  $daily_dosing_frequency);
            if ($remaining_duration >= $prescription->duration) {
                $adjusted_duration = 0;
                $adjusted_remaining_quantity = $prescription->remaining_quantity - $prescription->duration * $daily_dosing_frequency;
            }elseif($remaining_duration >= 1) {
                $adjusted_duration = $prescription->duration - $remaining_duration;
                $adjusted_remaining_quantity = $prescription->remaining_quantity - ($prescription->duration - $adjusted_duration) * $daily_dosing_frequency;
            }else{
                return redirect()->back()->withErrors(['error' => 'エラーが発生しました']);
            }

            // $total_daily_dose = ($prescription->breakfast + $prescription->lunch + $prescription->dinner + $prescription->bedtime) * $prescription->duration;
            // $total_quantity = $total_daily_dose + $prescription->remaining_quantity;
            // $max_duration = (int)($total_quantity / $daily_dosing_frequency);
            // $adjust_duration = $max_duration;

            // if ($max_duration > $needed_duration * 2 ) {
            //     $adjust_duration = 0;
            //     $adjust_remaining_quantity = $total_quantity - $needed_duration * $daily_dosing_frequency;
            // }else {
            //     if ($max_duration >= $needed_duration) {
            //         $adjust_duration = $needed_duration - ($max_duration - $needed_duration);
            //         $adjust_remaining_quantity = $total_quantity - $adjust_duration * $daily_dosing_frequency;
            //     }else {
            //         return redirect()->back()->withErrors(['error' => '無効な処方日数です']);
            //     }
            // }

            $adjustments[$prescription->id] = [
                'prescription_id' => $prescription->id,
                'adjusted_duration' => $adjusted_duration,
                'adjusted_remaining_quantity' => $adjusted_remaining_quantity
            ];
        }


        return view('prescriptions.adjust-result')
                ->with('adjustments', $adjustments)
                ->with('patient', $patient)
                ->with('needed_duration', $needed_duration);
    }
    // update remaining_quantity( prescriptions table ) after revising
    public function updateRevisedRemaining($request, $patient_id)
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


}