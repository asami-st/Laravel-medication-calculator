
@extends('layouts.app')

@section('title', 'Index')

@section('content')
    <div class="row mb-3">
        <div class="col-auto">
            <h1>患者一覧</h1>
        </div>
        <div class="col">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-patient"><i class="fa-solid fa-user-plus"></i> Add</button>
        </div>
    </div>
    {{-- Modal --}}
    @include('patients.create')

    <table class=" table table-hover text-center w-75">
        <thead class="table-primary">
            <tr>
                <th></th>
                <th>患者名</th>
                <th>更新日</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($all_patients as $patient)
                <tr>
                    <td>{{ $patient->id }}</td>
                    <td><a href="{{ route('prescription.create', $patient->id) }}" class="text-dark">{{ $patient->name }}</a></td>
                    <td>{{ $patient->updated_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No Patients yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
