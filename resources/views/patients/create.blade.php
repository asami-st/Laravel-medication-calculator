<div class="modal fade" id="add-patient">
    <div class="modal-dialog">
        <form action="{{ route('patient.store') }}" method="post">
            @csrf
            <div class="modal-content border-primary">
                <div class="modal-header border-primary">
                    <h4 class="modal-title text-primary">
                        <i class="fa-solid fa-user-plus"></i> Add
                    </h4>
                </div>
                <div class="modal-body">
                    <input type="text" name="patient_name" class="form-control" placeholder="患者氏名">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>
