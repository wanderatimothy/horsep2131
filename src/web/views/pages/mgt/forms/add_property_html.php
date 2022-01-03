
<!-- property modal -->
<div class="modal" data-bs-backdrop="static" data-bs-keyboard="false" id="createProperty" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable  modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-uppercase text-white">Add Property</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?= app_url('api/property') ?>" method="POST" id="property_form">
                    <div id="property_form_feedback"></div>
                    <div class="row justify-content-around">
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="label">Property Name</label>
                            <input type="text" name="label" class="form-control rounded-5 " id="p_name" placeholder="Label">
                            <span id="label_error" class="text-danger error-feedback"></span>
                        </div>
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="location">Location</label>
                            <input type="text" name="location" class="form-control rounded-5 " id="p_contact" placeholder="Location">
                            <span id="location_error" class="text-danger error-feedback"></span>
                        </div>
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="type">Type</label>
                            <select name="type" class="form-select form-control" id="property-types">
                                <option value="">Choose</option>
                            </select>
                            <span id="type_error" class="text-danger error-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="type">Landlord</label>
                            <select name="landlord_id" class="form-select form-control" id="select-landlord">
                                <option value="">Choose</option>
                            </select>
                            <span id="landlord_id_error" class="text-danger error-feedback"></span>
                        </div>
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="type">Has Units</label>
                            <select name="has_units" class="form-select form-control" id="p_has_units">
                                <option selected value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <span id="has_units_error" class="text-danger error-feedback"></span>
                        </div>
                    </div>
                    <hr class="bg-secondary my-1">
                    <div class="d-flex justify-content-between align-items-bottom">
                        <h5>Custom Fields</h5>
                        <button type="button" id="add_custom_field" class="btn btn-sm btn-dark" >Add</button>
                    </div>
                    <div id="custom_field_container"></div>   
                    <div class="d-flex justify-content-center">
                        <button type="submit" id="p_submit_btn" class="btn btn-dark  w-75">Save <i class="bi bi-save"></i> </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-info btn-sm" data-bs-dismiss="modal">close</button>
            </div>
        </div>
    </div>
</div>
<!-- property modal -->