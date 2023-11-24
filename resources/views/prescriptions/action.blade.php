{{-- edit-prescription --}}
<div class="modal fade" id="edit-prescription-{{ $prescription->id }}">
    <div class="modal-dialog">
        <form action="{{ route('prescription.update', $prescription->id) }}" method="post">
            @csrf
            @method('PATCH')
            <div class="modal-content border-warning">
                <div class="modal-header border-warning">
                    <h4 class="modal-title text-warning">
                        <i class="fa-solid fa-prescription-bottle-medical"></i> 処方修正
                    </h4>
                </div>
                <div class="modal-body">
                    <h3>{{ $prescription->medication->name}}{{$prescription->medication->form}}{{$prescription->medication->strength}}</h3>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text">用法用量</span>
                                <input type="number" name="new_breakfast" id="new-breakfast" class="form-control" min="0" value="{{ $prescription->breakfast }}">
                                <input type="number" name="new_lunch" id="new-lunch" class="form-control" min="0" value="{{ $prescription->lunch }}">
                                <input type="number" name="new_dinner" id="new-dinner" class="form-control" min="0"  value="{{ $prescription->dinner }}">
                                <input type="number" name="new_bedtime" id="new-bedtime" class="form-control" min="0"  value="{{ $prescription->bedtime }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="new-duration" class="form-label">処方日数</label>
                            <div class="input-group">
                                <input type="number" name="new_duration" id="new-duration" class="form-control" min="0"  value="{{ $prescription->duration }}">
                                <span class="input-group-text">日</span>
                            </div>
                        </div>
                        <div class="col">
                            <label for="new-remaining-quantity" class="form-label">残薬数</label>
                            <input type="number" name="new_remaining_quantity" id="new-remaining-quantity" class="form-control" min="0"  value="{{ $prescription->remaining_quantity }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-dismiss="modal">戻る</button>
                    <button type="submit" class="btn btn-warning btn-sm">修正</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- delete-prescription --}}
<div class="modal fade" id="delete-prescription-{{ $prescription->id }}">
    <div class="modal-dialog">
        <form action="{{ route('prescription.destroy', $prescription->id) }}" method="post">
            @csrf
            @method('DELETE')
            <div class="modal-content border-danger">
                <div class="modal-header border-danger">
                    <h4 class="modal-title text-danger">
                        <i class="fa-solid fa-circle-exclamation"></i> 処方削除
                    </h4>
                </div>
                <div class="modal-body">
                    <h6><span class="fw-bold">{{ $prescription->medication->name }}{{ $prescription->medication->form }}{{ $prescription->medication->strength }}</span> を削除しますか？</h6>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal">戻る</button>
                    <button type="submit" class="btn btn-danger btn-sm">削除</button>
                </div>
            </div>
        </form>
    </div>
</div>
