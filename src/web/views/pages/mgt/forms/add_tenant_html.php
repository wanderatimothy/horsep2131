<div id="createTenant" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-uppercase text-white">Add Tenant</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <!-- rule enforcement -->
            <?php 
            if(count($data['onboarding_rules']) > 0):
                foreach($data['onboarding_rules'] as $rule):
                    if($rule->hasPaymentImplication):
                        // prompt a payment recording fields
                        ?>
                        <form  id="<?= strtolower(str_replace(' ','_',$rule->rule_title))  ?>_form" action="<?=app_url("api/record-payment")?>" class="container p-2 bg-gradient-warning" >
                            <div class="row justify-content-between align-items-end">
                                <div class="col-lg-4 col-lg-6">
                                      <p class="px-2 "><?= ucfirst($rule->rule_description)?></p>
                                </div>
                                <?php
                                
                                    if($rule->enforcement == 'relaxed'):
                                        ?>
                                          <button type="button" class="btn btn-dark  col-lg-2 col-md-3 col-sm-12" >Skip</button>
                                        <?php
                                    endif;
                                ?>
                            </div>
                            <div id="<?= strtolower(str_replace(' ','_',$rule->rule_title))  ?>_form_feedback"></div>
                            <p class="p-3 bg-gradient-success d-none" id="<?= strtolower(str_replace(' ','_',$rule->rule_title))  ?>_payment_reference_no"></p>
                            <div class="row mt-2 align-items-end">
                                <div class="form-group col-lg-3 col-md-3 col-sm-12">
                                    <label class="text-capitalize" for="form-label"><?=$rule->rule_title?></label>
                                    <input type="number" min="0" max="50000000" name="amount_paid" placeholder="Amount" class="form-control" >
                                    <span id="amount_paid_error" class="text-danger error-feedback"></span>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12">
                                    <label class="form-label" for="date">Payment Date</label>
                                    <input type="date"  placeholder="Date" name="payment_date" class="form-control" />
                                    <span id="payment_date_error" class="text-danger error-feedback"></span>

                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12">
                                    <label class="form-label" for="date">Paid By</label>
                                    <input type="text"  placeholder="Names On Receipt" name="paid_by" class="form-control" />
                                    <span id="paid_by_error" class="text-danger error-feedback"></span>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 d-none">
                                    <label class="form-label" for="date">Reason</label>
                                    <input type="text" readonly   placeholder="reason" name="payment_reason" value="<?=$rule->rule_description ?>" class="  form-control" />
                                </div>
                                <button type="submit" class="btn btn-dark  col-lg-2 col-md-3 col-sm-12" >Record Payment</button>
                            </div>
                        </form>
                         
                        <?php
                    else:
                        ?>
                        <div class="container px-2">
                            <div class="row mt-1">
                                <div class="form-group col-lg-3 col-md-3 col-sm-12">
                                    <label class="form-label" for=""><?=$rule->rule_title?></label>
                                    <select name="<?= strtolower(str_replace(' ','_',$rule->rule_title))  ?>" class="form-select form-control" id="">
                                         <option value="1">Yes</option>
                                         <option value="0">No</option>
                                        <?php
                                         if($rule->enforcement == 'relaxed'):
                                         ?>
                                         <option value="2">Skip</option>
                                         <?php
                                         endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                    endif;
                endforeach;
            endif;
            ?>
            <!-- rule enforcement -->
            <form action="<?=app_url('api/tenant' )?>" id="t_form" >
                <div id="t_form_feedback"></div>                
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
                        <select name="unit_id"  id="t_available_units" class="form-select form-control">
                            <option value="" selected  >Choose</option>
                        </select>
                        <span id="unit_id_error" class="text-danger error-feedback"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <label for="population">Next Due Date</label>
                        <input type="date" class="form-control" name="population" placeholder="Number">
                        <span id="population_error" class="text-danger error-feedback"></span>
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
                <?php  _template("pages.mgt.components.custom_fields_tenant",$data)  ?>
                <div id="t_addon_container" class="p-2">

                </div>
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