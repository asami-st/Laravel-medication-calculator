{{-- add-medication --}}
<div class="modal fade" id="add-medication">
    <div class="modal-dialog">
        <form action="{{ route('medication.store') }}" method="post">
            @csrf
            <div class="modal-content border-info">
                <div class="modal-header border-info">
                    <h4 class="modal-title text-info">
                        <i class="fa-solid fa-prescription-bottle-medical"></i> Add Medication
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" name="medication_name" class="form-control" placeholder="Loxoprofen">
                        <select class="form-select" name="medication_form">
                            <option value="" hidden>Form</option>
                            <option value="tablets">Tablets</option>
                            <option value="pill">Pills</option>
                            <option value="3">Three</option>
                          </select>
                        <input type="text" name="medication_strength" class="form-control" placeholder="60mg">
                    </div>

                    {{-- <input type="text" name="medication_form" class="form-control" placeholder="tablets"> --}}

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info btn-sm">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

