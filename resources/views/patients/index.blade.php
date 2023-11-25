@extends('layouts.app')

@section('title', '患者一覧')

@section('content')
    <div class="row mb-3">
        <div class="col-sm-auto">
            <h1>患者一覧</h1>
        </div>
        <div class="col-sm">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-patient"><i class="fa-solid fa-user-plus"></i> 追加</button>
        </div>
    </div>
    {{-- Modal --}}
    @include('patients.create')

    <table class=" table table-hover table-responsive-sm text-center">

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
                    <td>{{ $patient->updated_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">患者が登録されていません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
