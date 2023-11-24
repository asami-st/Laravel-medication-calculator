{{-- add-medication --}}
<div class="modal fade" id="add-medication">
    <div class="modal-dialog">
        <form action="{{ route('medication.store') }}" method="post">
            @csrf
            <div class="modal-content border-info">
                <div class="modal-header border-info">
                    <h4 class="modal-title text-info">
                        <i class="fa-solid fa-pills"></i> 医薬品名追加
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" name="medication_name" class="form-control" placeholder="ロキソニン">
                        <select class="form-select" name="medication_form">
                            <option value="" hidden>剤形</option>
                            <option value="錠">錠</option>
                            <option value="OD錠">OD錠</option>
                            <option value="カプセル">カプセル</option>
                          </select>
                        <input type="text" name="medication_strength" class="form-control" placeholder="60mg">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info btn-sm">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

