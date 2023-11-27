<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicationController extends Controller
{
    private $medication;

    public function __construct(Medication $medication)
    {
        $this->medication = $medication;
    }

    // 医薬品一覧
    public function index()
    {
        $all_medications = $this->medication->where('user_id',  Auth::user()->id)->get();
        return view('medications.index')
                ->with('all_medications', $all_medications);
    }

    // 医薬品追加
    public function store(Request $request)
    {
        $request->validate([
            'medication_name' => 'required|min:1|max:30',
            'medication_form' => 'required',
            'medication_strength' => 'required',
        ]);
        $this->medication->name = $request->medication_name;
        $this->medication->form = $request->medication_form;
        $this->medication->strength = $request->medication_strength;
        $this->medication->user_id = Auth::user()->id;

        $this->medication->save();

        return redirect()->back();
    }

    // 医薬品修正
    public function update(Request $request, $id)
    {
        $medication = $this->medication->findOrFail($id);
        $medication->name = $request->medication_name;
        $medication->form = $request->medication_form;
        $medication->strength = $request->medication_strength;
        $medication->save();

        return redirect()->back();
    }

    // 医薬品削除
    public function destroy($id)
    {
        $this->medication->destroy($id);

        return redirect()->back();
    }

    // 医薬品検索（３文字検索）
    public function search(Request $request)
    {
        $query = $request->input('search');
        if (strlen($query) < 3) {
            return response()->json([]);
        }
        $medications = $this->medication->where('user_id', Auth::user()->id)
                                        ->where(function ($q) use ($query) {
                                            $q->where('name', 'like', '%' . $query . '%')
                                            ->orWhere('form', 'like', '%' . $query . '%')
                                            ->orWhere('strength', 'like', '%' . $query . '%');
        })
        ->get();
        $suggestions = $medications->map(function ($medication) {
            return [
                'label' => $medication->name . $medication->form . $medication->strength,
                'value' => $medication->id
            ];
        });

        return response()->json($suggestions);
    }
}
