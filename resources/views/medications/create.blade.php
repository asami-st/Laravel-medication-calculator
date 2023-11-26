{{-- add-medication --}}
<div class="modal fade" id="add-medication">
    <div class="modal-dialog">
        <form action="{{ route('medication.store') }}" method="post">
            @csrf
            <div class="modal-content border-info">
                <div class="modal-header border-info">
                    <h4 class="modal-title text-info">
                        <i class="fa-solid fa-pills"></i> 医薬品追加
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" name="medication_name" class="form-control" placeholder="ロキソニン" required>
                        <select class="form-select" name="medication_form" required>
                            <option value="" hidden>剤形</option>
                            <option value="錠">錠</option>
                            <option value="OD錠">OD錠</option>
                            <option value="カプセル">カプセル</option>
                          </select>
                        <input type="text" name="medication_strength" class="form-control" placeholder="60mg" required>
                        @if($errors->hasAny(['medication_name', 'medication_form', 'medication_strength']))
                            <p class="text-danger small">全ての項目を入力してください。</p>
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-dismiss="modal">戻る</button>
                    <button type="submit" class="btn btn-info btn-sm">追加</button>
                </div>
            </div>
        </form>
    </div>
</div>

