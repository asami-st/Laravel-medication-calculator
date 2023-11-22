@extends('layouts.app')

@section('title', 'Remaining Medication')

@section('content')
    <form action="{{ route('prescription.update.calculate', $patient->id) }}" method="post">
        @csrf
        @method('PATCH')
        <h3>ID:{{ $patient->id }} {{ $patient->name }}</h3>
        <h1>Enter the Duration and Remaining Medications</h1>
        <table class=" table table-hover table-sm text-center">
            <thead class="table-secondary">
                <tr>
                    <th></th>
                    <th>NAME</th>
                    <th>BREAKFAST</th>
                    <th>LUNCH</th>
                    <th>DINNER</th>
                    <th>BEDTIME</th>
                    <th>DURATION</th>
                    <th>REMAININGS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patient->prescriptions as $prescription)
                    <tr>
                        <td>{{ $prescription->id }}</td>
                        <td>{{ $prescription->medication->name }}{{ $prescription->medication->form }}{{ $prescription->medication->strength }}</td>
                        <td>{{ floatval($prescription->breakfast) }}</td>
                        <td>{{ floatval($prescription->lunch) }}</td>
                        <td>{{ floatval($prescription->dinner) }}</td>
                        <td>{{ floatval($prescription->bedtime) }}</td>
                        <td>
                            <div class="input-group">
                                <input type="number" name="duration[{{ $prescription->id }}]" value="{{ $prescription->duration ?? '0' }}" id="duration-{{ $prescription->id }}" class="form-control mx-auto" style="width: 10px">
                                <span class="input-group-text text-muted">days</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" name="remaining_quantities[{{ $prescription->id }}]" value="{{ $prescription->remaining_quantity ?? '0' }}" id="remaining-quantity-{{ $prescription->id }}" class="form-control mx-auto" style="width: 60px">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No Prescriptions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Calculate</button>
    </form>
@endsection

