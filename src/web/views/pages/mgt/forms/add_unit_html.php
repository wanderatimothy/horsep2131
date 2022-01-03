<div id="createUnit" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-uppercase text-white">Add Unit</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <form action="<?=app_url('api/property/' . $data['property'] . '/unit' )?>" id="units_form">
                <div id="units_form_feedback"></div>
                <div class="row justify-content-between border-bottom border-secondary container ">
                    <div class="col-md-3 col-lg-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="unit_auto_generate"  name="auto_generate">
                            <label class="form-check-label" for="show_autogenerate_trigger">use Auto Generate</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3"  id="u_start_index">
                        <div class="form-group  d-flex align-items-center " >
                            <label class="form-label  mx-1" for="start_index">Start Index</label>
                            <input type="number" min="1" max="500"  name="start_index" id="start_index" class="form-control form-control-sm" placeholder=" e.g (1) "   >
                            <span id="start_index_error" class="text-danger error-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3" id="u_number_to_generate" >
                        <div class="form-group  d-flex align-items-center ">
                            <label class="form-label  mx-1" for="number_to_generate">No.</label>
                            <input type="number" min="2" max="200" value="2" name="number_to_create" class="form-control form-control-sm" id="">
                        </div>
                    </div>
                </div>
                <h4>Characteristics</h4>
                <div class="row mt-2">
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="label">Label Prefix</label>
                        <input type="text" id="u_label_prefix" class="form-control" name="prefix" placeholder="Prefix">
                        <span id="prefix_error" class="text-danger error-feedback "></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="floor">Floor</label>
                        <select name="floor_id" id="floor_selection" class="form-select form-control">
                        </select>
                        <span id="floor_id_error" class="text-danger error-feedback"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="rent amount">Rent Amount</label>
                        <input type="number" class="form-control" min="0" max="10000000" name="rent_amount" placeholder="rent_amount">
                        <span id="rent_amount_error" class="text-danger error-feedback"></span>

                    </div>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="rooms">Rooms</label>
                        <input type="number" value="1" min="1" max="15" class="form-control" name="rooms" placeholder="rooms">
                        <span id="rooms_error" class="text-danger error-feedback"></span>
                    </div>
                </div>
                <div class="row mt-2">
                   <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="Allowed occupants">Occupants Allowed</label>
                        <input type="number" class="form-control" name="allowed_occupants" placeholder="Sharing Tenants">
                        <span id="allowed_occupants_error" class="text-danger error-feedback"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="self_contained">Self Contained</label>
                        <select name="self_contained"  class="form-select form-control">
                            <option value="1" selected >Yes</option>
                            <option value="0">No</option>
                        </select>
                        <span id="self_contained_error" class="text-danger error-feedback"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="facility_number">Facilities (Bathrooms & Toilets)</label>
                        <input type="number" class="form-control" name="facilities" placeholder="facilities">
                        <span id="facilities_error" class="text-danger error-feedback"></span>

                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-8 col-sm-12 col-lg-8">
                        <label for="description">Description</label>
                        <span id="description_error" class="text-danger error-feedback"></span>
                        <textarea name="description" placeholder="description..." class="form-control" maxlength="200" id="" cols="30" rows="5"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                     <button type="submit" id="u_submit_btn" class="btn btn-dark  w-75">Save <i class="bi bi-save"></i> </button>
                </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-info btn-sm" data-bs-dismiss="modal">close</button>
            </div>
        </div>
    </div>
</div>