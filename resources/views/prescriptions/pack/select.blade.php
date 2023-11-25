@extends('layouts.app')

@section('title', '一包化')

@section('content')
    <form action="{{ route('pack.show', $patient->id) }}" method="post">
        @csrf
        <h3 class="mb-4"><span class="small"><i class="fa-solid fa-prescription text-white bg-secondary p-2"></i></span>  <a href="{{ route('prescription.create', $patient->id) }}" class="text-dark text-decoration-none">{{ $patient->name }}</a></h3>
        <h4>一包化する薬を選択</h4>
        <table class=" table table-hover table-sm text-center w-75">
            <thead class="table-primary">
                <tr>
                    <th>
                        <div class="form-check text-start">
                            <input class="form-check-input" type="checkbox" name="master_checkbox" id="master-checkbox">
                            <label class="form-check-label" for="master-checkbox">
                                全選択
                            </label>
                        </div>
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
                        <td>
                            <div class="form-check text-start">
                                <input class="form-check-input child-checkbox" name="selected_prescriptions[]" value="{{ $prescription->id }}" type="checkbox" value="" id="select-{{ $prescription->id }}">
                                <label class="form-check-label" for="select-{{ $prescription->id }}">
                                    {{ $loop->iteration }}
                                </label>
                            </div>
                        </td>
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
                        <td colspan="8">処方がありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('prescription.create', $patient->id) }}" class="btn btn-outline-primary">戻る</a>
        <button type="submit" class="btn btn-primary">一包化</button>
        @if (session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
    </form>
    <script src="{{ asset('build/assets/check-ec65b177.js') }}"></script>
@endsection
