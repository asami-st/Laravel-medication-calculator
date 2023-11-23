@include('prescriptions.adjust')
<div class="container">
    <div class="row">

<form action="{{ route('revised.remain.update', $patient->id) }}" method="post">
    @csrf
    @method('PATCH')
    <h3 class="mt-5">処方日数: <span class="text-danger">{{ $needed_duration }}</span> 日分</h3>
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
            @forelse ($patient->prescriptions as $prescription)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $prescription->medication->name }}{{ $prescription->medication->form }}{{ $prescription->medication->strength }}</td>
                    <td class="fw-bold">{{ $prescription->duration }}</td>
                    <td><i class="fa-solid fa-right-long"></i></td>
                    <td class="fw-bold">{{ $adjustments[$prescription->id]['adjusted_duration'] }}</td>
                    <td>{{ $prescription->remaining_quantity }}</td>
                    <td><i class="fa-solid fa-right-long"></i></td>
                    <td>{{ $adjustments[$prescription->id]['adjusted_remaining_quantity'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No Prescriptions yet.</td>
                </tr>
            @endforelse
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <button type="submit" class="btn btn-danger">残薬数保存</button>
</form>
    </div>
</div>
