@extends('layouts.app')

@section('title', '処方')

@section('content')
    @if ($all_medications->count() > 0)
        <form action="{{ route('prescription.store', $patient->id) }}" method="post">
            @csrf
            <h3 class="mb-4"><span class="small"><i class="fa-solid fa-prescription text-white bg-secondary p-2"></i></span> {{ $patient->name }}</h3>
            <div class="row mb-3">
                <div class="col-sm-6 mb-0">
                    <label for="medication-select" class="form-label">医薬品名</label>
                    <input class="form-control mb-3" id="medication-search" type="text" placeholder="医薬品名検索">
                    <input type="hidden" id="medication-id" name="medication_id">
                    @error('medication_id')
                        <p class="text-danger small">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-sm-5">
                    <div class="row">
                        <div class="col-sm mb-3">
                            <label for="duration" class="form-label">処方日数</label>
                            <div class="input-group">
                                <input type="number" name="duration" id="duration" class="form-control" min="0" placeholder="処方日数入力" value="{{ old('duration') }}">
                                <span class="input-group-text">日</span>
                            </div>
                            @error('duration')
                                <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-sm mb-0">
                            <label for="remaining-quantity" class="form-label">残薬数</label>
                            <input type="number" name="remaining_quantity" id="remaining-quantity" class="form-control" min="0" placeholder="残薬数入力" value="{{ old('remaining_quantity') }}">
                        </div>
                        @error('remaining_quantity')
                            <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-6 mb-3">
                    <div class="input-group">
                        <span class="input-group-text">用法用量</span>
                        <input type="number" name="breakfast" id="breakfast" class="form-control" min="0" placeholder="朝" value="{{ old('breakfast') }}">
                        <input type="number" name="lunch" id="lunch" class="form-control" min="0" placeholder="昼" value="{{ old('lunch') }}">
                        <input type="number" name="dinner" id="dinner" class="form-control" min="0" placeholder="夕" value="{{ old('dinner') }}">
                        <input type="number" name="bedtime" id="bedtime" class="form-control" min="0" placeholder="寝る前" value="{{ old('bedtime') }}">
                    </div>
                    @if($errors->hasAny(['breakfast', 'lunch', 'dinner', 'bedtime']))
                        <p class="text-danger small">全ての服用時点に数値を入力してください。</p>
                    @endif
                </div>
                <div class="col-sm-5 mb-3">
                    <button type="submit" class="btn btn-secondary w-100">追加</button>
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
                        <td class="">
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-prescription-{{ $prescription->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-prescription-{{ $prescription->id }}"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                        @include('prescriptions.action')
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">処方がありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('duration.remain.edit', $patient->id) }}" class="btn btn-warning">処方日数・残薬数一括変更</a>
        <a href="{{ route('adjust.select', $patient->id) }}" class="btn btn-success">残薬調整</a>
        <a href="{{ route('pack.select', $patient->id) }}" class="btn btn-primary">一包化</a>
    @else
        <div class="text-center mt-5">
            <h2>医薬品が登録されていません</h2>
            <a href="{{ route('medication.index') }}" class="">追加する</a>
        </div>
    @endif
    <script src="{{ asset('build/assets/search-c4fe4d11.js') }}"></script>
@endsection
