<div id="createTenant" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-uppercase text-white">Add Tenant</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">           
            <form action="<?=app_url('api/tenant' )?>" id="tenants_form" >
                <div id="tenants_form_feedback"></div>                
                <div class="row mt-2">
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="unit">Tenant Names</label>
                        <input type="text" id="tenant_names" class="form-control" name="tenant_names" placeholder="Tenant Names">
                        <span id="tenant_names_error" class="text-danger error-feedback"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="gender">Gender</label>
                        <select name="gender"  class="form-select form-control">
                            <option value="MALE" selected >MALE</option>
                            <option value="FEMALE"  >FEMALE</option>
                            <option value="CONFIDENTIAL"  >CONFIDENTIAL</option>
                            <option value="CONFIDENTIAL"  >N/A</option>
                        </select>
                        <span id="gender_error" class="text-danger error-feedback"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="contact">Contact</label>
                        <input type="text" class="form-control" name="contact" placeholder="Phone Number">
                        <span id="contact_error" class="text-danger error-feedback"></span>

                    </div>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="email">Email</label>
                        <input type="email"  class="form-control" name="email" placeholder="Tenant's Email">
                        <span id="email_error" class="text-danger error-feedback"></span>
                    </div>
                </div>
                <div class="row mt-2">
                 <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="unit">Unit</label>
                        <select name="unit_id"  id="units_select" class="form-select form-control">
                            <option value="" selected  >Choose</option>
                        </select>
                        <span id="unit_id_error" class="text-danger error-feedback"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="next_due_date">Next Due Date</label>
                        <input type="date" class="form-control" name="next_due_date" >
                        <span id="next_due_date_error" class="text-danger error-feedback"></span>
                    </div>
                   <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="population">Population (e.g Family size,business size) </label>
                        <input type="number" class="form-control" name="population" placeholder="Number">
                        <span id="population_error" class="text-danger error-feedback"></span>
                    </div>
                    
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="pets">Pets Population</label>
                        <input type="number" class="form-control" value="0" name="pets_population" placeholder="Number">
                        <span id="pets_population_error" class="text-danger error-feedback"></span>
                    </div>
                </div>
                <div class="row mt-2">

                <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="entry_date">Entry Date</label>
                        <input type="date" class="form-control"  name="entry_date" >
                        <span id="entry_date_error" class="text-danger error-feedback"></span>
                    </div>
                </div>
                <h5 class="border-bottom">Photo</h5>
                    <div class="row align-items-start">
                        <div class="col-md-4 col-lg-3">
                            <label for="tenants photo">Upload Tenants Photo</label>
                            <input type="file" class="form-control" name="tenant_photo" id="tenant_photo_upload"  >
                            <span id="tenant_photo_error"  class="text-danger error-feedback"></span>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <div class="p-1" id="upload_preview_area"></div>
                        </div>
                    </div>
                <h5 class="border-bottom">Emergency Information</h5>
                <div class="row my-2">
                    <div class="col-md-4 col-lg-4 col-sm-6 col-lg-3">
                        <label for="emergency_person">Emergency Person</label>
                        <input type="text" id="emergency_person" class="form-control" name="emergency_person" placeholder="Emergency person name">
                        <span id="emergency_person_error" class="text-danger error-feedback"></span>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-6 col-lg-3">
                        <label for="emergency_contact">Emergency Contact</label>
                        <input type="text" id="emergency_contact" class="form-control" name="emergency_contact" placeholder="Phone Number">
                        <span id="emergency_contact_error" class="text-danger error-feedback"></span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-bottom">
                    <h5>Custom Fields</h5>
                    <button type="button" data-target-form="tenant" id="tenant_form_add_custom_field" class="btn btn-sm btn-dark" >Add</button>
                </div>
                <hr>
                <div id="tenant_form_custom_field_container"></div>   

                <div class="d-flex justify-content-center align-items-center">
                     <button type="submit" id="t_submit_btn" class="btn btn-dark  w-75">Save <i class="bi bi-save"></i> </button>
                </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-info btn-sm" data-bs-dismiss="modal">close</button>
            </div>
        </div>
    </div>
</div>