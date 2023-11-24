@include('prescriptions.pack.select')

<div class="container">
    <div class="row">
    <form action="{{ route('revised.remain.update', $patient->id) }}" method="post">
        @csrf
        @method('PATCH')
        <h3 class="mt-5">一包化可能な最大日数: <span class="text-danger">{{ $min_duration }}</span> 日分</h3>
        <table class=" table table-hover table-sm text-center w-75">
            <thead class="table-danger">
                <tr>
                    <th></th>
                    <th>医薬品名</th>
                    <th>朝</th>
                    <th>昼</th>
                    <th>夕</th>
                    <th>寝る前</th>
                    <th>処方日数</th>
                    <th>総使用数</th>
                    <th>残薬数</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prescriptions as $prescription)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $prescription->medication->name }}{{ $prescription->medication->form }}{{ $prescription->medication->strength }}</td>
                        <td>{{ floatval($prescription->breakfast) }}</td>
                        <td>{{ floatval($prescription->lunch) }}</td>
                        <td>{{ floatval($prescription->dinner) }}</td>
                        <td>{{ floatval($prescription->bedtime) }}</td>
                        <td class="fw-bold">{{ $min_duration }}</td>
                        <td class="fw-bold">{{ $revised_medications[$prescription->id]['total_required_dose'] ?? "0" }}</td>
                        <td class="fw-bold text-danger">
                            {{ $revised_medications[$prescription->id]['revised_remaining_medications'] }}
                            <input type="hidden" name="remaining_quantities[{{ $prescription->id }}]" value="{{ $revised_medications[$prescription->id]['revised_remaining_medications'] }}">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No Prescriptions yet.</td>
                    </tr>
                @endforelse
                <tr>
                    <td></td>
                    <td class="fw-bold">合計:</td>
                    <td  class="fw-bold">{{ $total_dose['breakfast'] }}</td>
                    <td  class="fw-bold">{{ $total_dose['lunch'] }}</td>
                    <td  class="fw-bold">{{ $total_dose['dinner'] }}</td>
                    <td  class="fw-bold">{{ $total_dose['bedtime'] }}</td>
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
