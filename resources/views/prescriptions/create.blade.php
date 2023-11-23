@extends('layouts.app')

@section('title', 'Prescription')

@section('content')
    @if (!$all_medications->isEmpty())
        <form action="{{ route('prescription.store', $patient->id) }}" method="post">
            @csrf
            <h3>ID:{{ $patient->id }} {{ $patient->name }}</h3>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="medication-select" class="form-label">医薬品名</label>
                    <input class="form-control mb-3" id="medication-search" type="text" placeholder="Search medications">
                    <input type="hidden" id="medication-id" name="medication_id">

                    {{-- <div id="search-results" class="list-group position-absolute" style="display:none; z-index: 1000;"></div>
                    <select name="medication" id="medication-select" class="form-select medication-select">
                            <option value=""hidden>Select Medication</option>
                        @foreach ($all_medications as $medication)
                            <option value="{{ $medication->id }}">{{ $medication->name}} {{$medication->form}} {{$medication->strength}} </option>
                        @endforeach
                    </select> --}}
                </div>
                <div class="col-5">
                    <div class="row">
                        <div class="col">
                            <label for="duration" class="form-label">処方日数</label>
                            <div class="input-group">
                                <input type="number" name="duration" id="duration" class="form-control" min="0" placeholder="duration">
                                <span class="input-group-text">日</span>
                            </div>
                        </div>
                        <div class="col">
                            <label for="remaining-quantity" class="form-label">残薬数</label>
                            <input type="number" name="remaining_quantity" id="remaining-quantity" class="form-control" min="0" placeholder="remaining quantity">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <div class="input-group">
                        <span class="input-group-text">用法用量</span>
                        <input type="number" name="breakfast" id="breakfast" class="form-control" min="0" placeholder="朝">
                        <input type="number" name="lunch" id="lunch" class="form-control" min="0" placeholder="昼">
                        <input type="number" name="dinner" id="dinner" class="form-control" min="0" placeholder="夕">
                        <input type="number" name="bedtime" id="bedtime" class="form-control" min="0" placeholder="寝る前">
                    </div>
                </div>
                <div class="col-5">
                    <button type="submit" class="btn btn-secondary w-100">Add</button>
                </div>
            </div>
        </form>

        <table class=" table table-hover table-sm text-center mt-5">
            <thead class="table-secondary">
                <tr>
                    <th></th>
                    <th>医薬品名</th>
                    <th>朝</th>
                    <th>昼</th>
                    <th>夕</th>
                    <th>寝る前</th>
                    <th>処方日数</th>
                    <th>残薬数</th>
                    <th>{{-- For Button --}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patient->prescriptions as $prescription)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $prescription->medication->name }}{{ $prescription->medication->form }}{{ $prescription->medication->strength }}</td>
                        <td>{{ floatval($prescription->breakfast) }}</td>
                        <td>{{ floatval($prescription->lunch) }}</td>
                        <td>{{ floatval($prescription->dinner) }}</td>
                        <td>{{ floatval($prescription->bedtime) }}</td>
                        <td>{{ $prescription->duration }} <span class="text-muted">日</span></td>
                        <td>{{ $prescription->remaining_quantity  ?? '0' }}</td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-prescription-{{ $prescription->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-prescription-{{ $prescription->id }}"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                        @include('prescriptions.action')
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No Prescriptions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('duration.remain.enter', $patient->id) }}" class="btn btn-warning">処方日数・残薬数一括修正</a>
        <a href="{{ route('adjust', $patient->id) }}" class="btn btn-danger">残薬調整後</a>
        <a href="{{ route('pack.show', $patient->id) }}" class="btn btn-primary">一包化</a>

    @else
        <div class="text-center">
            <h2>No medications yet.</h2>
            <a href="{{ route('medication.index') }}" class="">Add</a>
        </div>
    @endif

    <script src="{{ asset('build/assets/search-fed54d2e.js') }}"></script>
@endsection
