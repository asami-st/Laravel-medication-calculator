
@extends('layouts.app')

@section('title', 'Index')

@section('content')
    <div class="row mb-3">
        <div class="col-auto">
            <h1>All Medications</h1>
        </div>
        <div class="col">
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#add-medication"><i class="fa-solid fa-prescription-bottle-medical"></i> Add Medication</button>
        </div>
        @include('medications.create')
    </div>
    <table class=" table table-hover text-center w-75">
        <thead class="table-info">
            <tr>
                <th>{{-- id --}}</th>
                <th>NAME</th>
                <th>UPDATED AT</th>
                <th>{{-- buttons --}}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($all_medications as $medication)
                <tr>
                    <td>{{ $medication->id }}</td>
                    <td>{{ $medication->name }}{{ $medication->form }}{{ $medication->strength }}</td>
                    <td>{{ $medication->updated_at }}</td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-medication-{{ $medication->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-medication-{{ $medication->id }}"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
                @include('medications.action')
            @empty
                <tr>
                    <td colspan="4">No Medications yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
