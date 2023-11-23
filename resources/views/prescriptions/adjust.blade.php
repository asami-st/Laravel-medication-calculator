@extends('layouts.app')

@section('title', '残薬調整')

@section('content')
    <form action="{{ route('adjust.show', $patient->id) }}" method="post">
        @csrf
        <h3>残薬調整を実施する薬を選択</h3>
        <table class=" table table-hover table-sm text-center w-75">
            <thead class="table-success">
                <tr>
                    <th></th>
                    <th>医薬品名</th>
                    <th>朝</th>
                    <th>昼</th>
                    <th>夕</th>
                    <th>寝る前</th>
                    <th>処方日数</th>
                    <th>残薬数</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patient->prescriptions as $prescription)
                    <tr>
                        <td>
                            <div class="form-check text-start">
                                <input class="form-check-input" name="selected_prescriptions[]" value="{{ $prescription->id }}" type="checkbox" value="" id="select-{{ $prescription->id }}">
                                <label class="form-check-label" for="select-{{ $prescription->id }}">
                                    {{ $loop->iteration }}
                                </label>
                                </div>
                        </td>
                        <td>{{ $prescription->medication->name }}{{ $prescription->medication->form }}{{ $prescription->medication->strength }}</td>
                        <td>{{ floatval($prescription->breakfast) }}</td>
                        <td>{{ floatval($prescription->lunch) }}</td>
                        <td>{{ floatval($prescription->dinner) }}</td>
                        <td>{{ floatval($prescription->bedtime) }}</td>
                        <td>{{ $prescription->duration }}</td>
                        <td>{{ $prescription->remaining_quantity}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No Prescriptions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">残薬調整</button>
        <a href="{{ route('prescription.create', $patient->id) }}" class="btn btn-outline-success">戻る</a>
        @if ($errors->any())
            <div class="alert alert-danger mt-3 w-75">
                    @foreach ($errors->all() as $error)
                        <h6>{{ $error }}</h6>
                    @endforeach
            </div>
        @endif
    </form>

@endsection
