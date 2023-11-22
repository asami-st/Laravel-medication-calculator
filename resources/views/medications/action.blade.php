{{-- edit-medication --}}
<div class="modal fade" id="edit-medication-{{ $medication->id }}">
    <div class="modal-dialog">
        <form action="{{ route('medication.update', $medication->id) }}" method="post">
            @csrf
            @method('PATCH')
            <div class="modal-content border-warning">
                <div class="modal-header border-warning">
                    <h4 class="modal-title text-warning">
                        <i class="fa-solid fa-prescription-bottle-medical"></i> Edit Medication
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" name="medication_name" class="form-control" value="{{ $medication->name }}">
                        <select class="form-select" name="medication_form">
                            <option value="" hidden>Form</option>
                            <option value="tablets"{{ $medication->form == 'tablets' ? 'selected' : '' }}>Tablets</option>
                            <option value="pill"{{ $medication->form == 'pill' ? 'selected' : '' }}>Pills</option>
                            <option value="capsule" {{ $medication->form == 'capsule' ? 'selected' : '' }}>Capsule</option>
                          </select>
                        <input type="text" name="medication_strength" class="form-control" value="{{ $medication->strength }}">
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- delete-medication --}}
<div class="modal fade" id="delete-medication-{{ $medication->id }}">
    <div class="modal-dialog">
        <form action="{{ route('medication.destroy', $medication->id) }}" method="post">
            @csrf
            @method('DELETE')
            <div class="modal-content border-danger">
                <div class="modal-header border-danger">
                    <h4 class="modal-title text-danger">
                        <i class="fa-solid fa-circle-exclamation"></i> Delete Medication
                    </h4>
                </div>
                <div class="modal-body">
                    <h6>Are you sure you want to delete <span class="fw-bold">{{ $medication->name }}{{ $medication->form }}{{ $medication->strength }}</span> ?</h6>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
