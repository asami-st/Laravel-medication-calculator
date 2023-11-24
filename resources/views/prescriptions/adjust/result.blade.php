@extends('layouts.app')

@section('title', '残薬調整')

@section('content')
<div class="container">
    <h3 class="mb-4"><span class="small"><i class="fa-solid fa-prescription text-white bg-secondary p-2"></i></span> <a href="{{ route('prescription.create', $patient->id) }}" class="text-dark text-decoration-none">{{ $patient->name }}</a></h3>
    <h4>元の処方</h4>
    <table class="table table-hover table-sm text-center w-75 mb-5">
        <thead class="table-success">
            <tr>
                <th>
                </th>
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
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $prescription->medication->name }}{{ $prescription->medication->form }}{{ $prescription->medication->strength }}</td>
                    <td>{{ floatval($prescription->breakfast) }}</td>
                    <td>{{ floatval($prescription->lunch) }}</td>
                    <td>{{ floatval($prescription->dinner) }}</td>
                    <td>{{ floatval($prescription->bedtime) }}</td>
                    <td>{{ $prescription->duration }} 日</td>
                    <td>{{ $prescription->remaining_quantity}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No Prescriptions yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <form action="{{ route('adjust.update', $patient->id) }}" method="post">
        @csrf
        @method('PATCH')
        <h4>残薬調整後処方</h4>
        <table class=" table table-hover table-sm text-center w-75">
            <thead class="table-danger">
                <tr>
                    <th></th>
                    <th>医薬品名</th>
                    <th>調整前処方日数</th>
                    <th></th>
                    <th>調整後処方日数</th>
                    <th>調整前残薬数</th>
                    <th></th>
                    <th>調整後残薬数</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prescriptions as $prescription)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $prescription->medication->name }}{{ $prescription->medication->form }}{{ $prescription->medication->strength }}</td>
                        <td>{{ $prescription->duration }} 日</td>
                        <td><i class="fa-solid fa-right-long"></i></td>
                        <td class="fw-bold text-dangera">{{ $adjustments[$prescription->id]['adjusted_duration'] ?? '-'}} 日</td>
                        <td>{{ $prescription->remaining_quantity }}</td>
                        <td><i class="fa-solid fa-right-long"></i></td>
                        <td class="fw-bold">{{ $adjustments[$prescription->id]['adjusted_remaining_quantity']  ?? '-'}}</td>
                        <input type="hidden" name="remaining_quantities[{{ $prescription->id }}]" value="{{ $adjustments[$prescription->id]['adjusted_remaining_quantity']  ?? '' }}">
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No Prescriptions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('adjust.select', $patient->id) }}" class="btn btn-outline-danger">戻る</a>
        <button type="submit" class="btn btn-danger">残薬数保存</button>
    </form>
</div>
@endsection
