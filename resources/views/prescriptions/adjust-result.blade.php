@extends('layouts.app')

@section('title', '残薬調整')

@section('content')
    @foreach ($patient->prescriptions as $prescription)
        <p>{{$prescription->medication->name}}</p>
        <p class="fw-bold">{{ $adjustments[$prescription->id]['adjust_duration'] }}</p>
    @endforeach
@endsection

