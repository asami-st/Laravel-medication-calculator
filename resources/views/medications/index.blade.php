
@extends('layouts.app')

@section('title', '医薬品一覧')

@section('content')
    <div class="row mb-3">
        <div class="col-sm-auto">
            <h1>医薬品一覧</h1>
        </div>
        <div class="col-sm-auto">
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#add-medication"><i class="fa-solid fa-pills"></i> 追加</button>
        </div>
        @include('medications.create')
    </div>
    <table class=" table table-hover table-responsive text-center">
        <thead class="table-info">
            <tr>
                <th>{{-- id --}}</th>
                <th>医薬品名</th>
                <th>更新日</th>
                <th>{{-- buttons --}}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($all_medications as $medication)
                <tr>
                    <td>{{ $medication->id }}</td>
                    <td>{{ $medication->name }}{{ $medication->form }}{{ $medication->strength }}</td>
                    <td>{{ $medication->updated_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-medication-{{ $medication->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-medication-{{ $medication->id }}"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
                @include('medications.action')
            @empty
                <tr>
                    <td colspan="4">医薬品が登録されていません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
