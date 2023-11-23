@extends('layouts.app')

@section('title', '残薬調整')

@section('content')
    <form action="{{ route('adjust.show', $patient->id) }}" method="post">
        @csrf
        <div class="row">
            <div class="col-auto">
                <input type="number" name="needed_duration" id="needed-duration" class="form-control">
            </div>
            <div class="col-auto">
                <h1>日分</h1>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-success">調整</button>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
@endsection
