{{-- edit-prescription --}}
<div class="modal fade" id="edit-prescription-{{ $prescription->id }}">
    <div class="modal-dialog">
        <form action="{{ route('prescription.update', $prescription->id) }}" method="post">
            @csrf
            @method('PATCH')
            <div class="modal-content border-warning">
                <div class="modal-header border-warning">
                    <h4 class="modal-title text-warning">
                        <i class="fa-solid fa-prescription-bottle-medical"></i> Edit Prescription
                    </h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <h3>ID:{{ $patient->id }} {{ $patient->name }}</h3>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="medication-select" class="form-label">Medication</label>
                            <select name="medication" id="medication-select" class="form-select medication-select">
                                    <option value=""hidden>Select Medication</option>
                                @foreach ($all_medications as $medication)
                                    <option value="{{ $medication->id }}">{{ $medication->name}} {{$medication->form}} {{$medication->strength}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text">Dosage</span>
                                <input type="number" name="breakfast" id="breakfast" class="form-control" min="0" placeholder="breakfast">
                                <input type="number" name="lunch" id="lunch" class="form-control" min="0" placeholder="lunch">
                                <input type="number" name="dinner" id="dinner" class="form-control" min="0" placeholder="dinner">
                                <input type="number" name="bedtime" id="bedtime" class="form-control" min="0" placeholder="bedtime">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="duration" class="form-label">Duration</label>
                            <div class="input-group">
                                <input type="number" name="duration" id="duration" class="form-control" min="0" placeholder="duration">
                                <span class="input-group-text">days</span>
                            </div>
                        </div>
                        <div class="col">
                            <label for="remaining-quantity" class="form-label">Remaining Quantity</label>
                            <input type="number" name="remaining_quantity" id="remaining-quantity" class="form-control" min="0" placeholder="remaining quantity">
                        </div>
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

{{-- delete-prescription --}}
<div class="modal fade" id="delete-prescription-{{ $prescription->id }}">
    <div class="modal-dialog">
        <form action="{{ route('prescription.destroy', $prescription->id) }}" method="post">
            @csrf
            @method('DELETE')
            <div class="modal-content border-danger">
                <div class="modal-header border-danger">
                    <h4 class="modal-title text-danger">
                        <i class="fa-solid fa-circle-exclamation"></i> Delete Prescription
                    </h4>
                </div>
                <div class="modal-body">
                    <h6>Are you sure you want to delete <span class="fw-bold">{{ $prescription->medication->name }}{{ $prescription->medication->form }}{{ $prescription->medication->strength }}</span> ?</h6>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
