@extends('layouts.app')

@section('title', 'Remaining Medication')

@section('content')
    <form action="{{ route('duration.remain.update', $patient->id) }}" method="post">
        @csrf
        @method('PATCH')
        <h3 class="mb-4"><span class="small"><i class="fa-solid fa-prescription text-white bg-secondary p-2"></i></span> {{ $patient->name }}</h3>
        <h4>処方日数と残薬数の一括変更</h4>
        <table class=" table table-hover table-sm text-center">
            <thead class="table-warning">
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
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $prescription->medication->name }}{{ $prescription->medication->form }}{{ $prescription->medication->strength }}</td>
                        <td>{{ floatval($prescription->breakfast) }}</td>
                        <td>{{ floatval($prescription->lunch) }}</td>
                        <td>{{ floatval($prescription->dinner) }}</td>
                        <td>{{ floatval($prescription->bedtime) }}</td>
                        <td>
                            <div class="input-group">
                                <input type="number" name="duration[{{ $prescription->id }}]" value="{{ $prescription->duration ?? '0' }}" id="duration-{{ $prescription->id }}" class="form-control mx-auto" style="width: 10px">
                                <span class="input-group-text text-muted">日</span>
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
        <button type="submit" class="btn btn-warning px-5">変更</button>
        <a href="{{ route('prescription.create', $patient->id) }}" class="btn btn-outline-warning px-5">戻る</a>
    </form>
@endsection

