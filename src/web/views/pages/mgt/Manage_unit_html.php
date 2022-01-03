<?php

use web\libs\Session;

$vm = $data["vm"];  ?>
<?php _template('_sidebar', ['active' => 'properties']); ?>
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <!-- Navbar -->
    <?php _template('_system_navbar'); ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">

        <div class="row justify-content-between align-items-start">
            <div class="col-md-4 col-sm-6 col-lg-3 my-2">
                <h4 class="mb-1"><?= $vm->details->label ?></h4>
                <h6 class="text-uppercase">Block : </h6>
                <h6 class="<?= $vm->details->floor_name ==  'N/A' ? "d-none"  : '' ?>">Floor : <?= $vm->details->floor_name ?></h6>
                <h6 class="text-uppercase"> <a href="<?= app_url('property/' . $vm->property->id) ?>" class="link link primary fw-bold"><?= $vm->property->property_label ?></a></h6>
            </div>
            <div class="col-md-8 col-sm-12 col-lg-9 d-flex justify-content-end">
                <?php

                // debug_dump([$vm]);

                if ($vm->details->number_of_occupants  <  $vm->details->occupants_limit) :

                ?>
                    <button type="button" id="t_add_btn" class="  btn bg-gradient-success btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#createTenant">New Tenant</button>
                <?php
                endif;
                ?>
                <button type="button" id="manager_assign_manager_btn" class="btn bg-gradient-info btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#assignManager">Assign To Task</button>
                <button type="button" id="unit_delete_btn" class="btn bg-gradient-danger btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#deleteProperty">Delete</button>
                <button type="button" id="data_reload_button" class="btn bg-gradient-secondary btn-sm mx-1"><i class="bi bi-arrow-repeat"></i>Reload</button>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Add Ons</h5>
                    </div>
                    <div class="card-body px-0 pb-3">
                        <!-- <div class="table-responsive"> -->
                        <table id="addons-table" class="table text-center align-items-center mb-2">

                        </table>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Tenants</h5>
                    </div>
                    <div class="card-body px-0 pb-3">
                        <!-- <div class="table-responsive"> -->
                        <table id="tenants-table" class="table text-center align-items-center mb-2">
                        </table>
                        <!-- </div> -->
                    </div>
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Settings</h5>
                    </div>
                    <div class="card-body px-0 pb-3">

                        <form id="unit_settings_form" action="<?= app_url("api/unit/" . $vm->details->id . "/update") ?>">
                            <div class="row p-3">
                                <div class="col-lg-3 col-md-3 col-sm-6 form-group">
                                    <label for="unit_Label">Unit Label</label>
                                    <input type="text" name="label" class="form-control" value="<?= $vm->details->label ?>" minlength="1" required aria-required="true">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 form-group">
                                    <label for="unit_Block">Block</label>
                                    <select name="block_id" class="form-control form-select">
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 form-group">
                                    <label for="unit_Floor">Floor</label>
                                    <select name="floor_id" class="form-control form-select" id="floors_select"></select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 form-group">
                                    <label for="unit_Label">Rent Amount</label>
                                    <input type="number" name="rent_amount" class="form-control" value="<?= $vm->details->rent_amount ?>" aria-required="true">
                                </div>
                            </div>
                            <div class="row mt-1 p-3">
                                <div class="col-lg-3 col-md-3 col-sm-6 form-group">
                                    <label for="Rooms">Rooms </label>
                                    <input type="number" name="rooms" class="form-control" value="<?= $vm->details->rooms ?>" aria-required="true">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 form-group">
                                    <label for="Facilities">Facilities <a href="<?= app_url('help/units/#settings') ?>" target="_blank" class="text-info ms-2"> Toilets and bathrooms.</a> </label>
                                    <input type="number" name="facilities" class="form-control" value="<?= $vm->details->occupants_limit ?>" aria-required="true">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 form-group">
                                    <label for="self_contained">Self Contained</label>
                                    <select name="self_contained" class="form-select form-control">
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                    <span id="self_contained_error" class="text-danger error-feedback"></span>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 form-group">
                                    <label for="occupants_limit">Allowed Occupants <a href="<?= app_url('help/units/#settings') ?>" target="_blank" class="text-info ms-2"> Tenants Limit.</a> </label>
                                    <input type="number" name="occupants_limit" class="form-control" value="<?= $vm->details->occupants_limit ?>" aria-required="true">
                                </div>
                            </div>
                            <h5 class="my-1 border-bottom px-3 ">Rules</h5>

                            <div class="row p-3 align-items-center">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="security_deposit" name="security_deposit">
                                        <label class="form-check-label" for="security_deposit">Turn on Security Deposit</label>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-8 col-sm-12 d-flex form-group p-3">
                                    <label for="security_deposit_amount">Security Deposit</label>
                                    <input type="number" name="security_deposit" class="form-control" value="<?= $vm->details->security_deposit_amount  ?>" aria-required="true">
                                </div>
                            </div>
                            <div class="row p-3 align-items-center">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="entry_rent_deposit" name="rent_deposit_before_entry"  <?= $vm->details->rent_deposit_before_entry ? "checked" : "" ?>   >
                                        <label class="form-check-label" for="rent_deposit_before_entry">Turn On Entry Deposit</label>
                                    </div>
                                </div>

                                <div class="col-lg-5 col-md-8 col-sm-12 d-flex form-group p-3">
                                    <label for="cycles_to_pay_before_entry">Rent Cycles To Pay Before Entry </label>
                                    <input type="number" name="cycles_to_pay_before_entry" class="form-control" value="<?= $vm->details->cycles_to_pay_before_entry ?>" aria-required="true" min="1" max="100">
                                </div>
                            </div>
                            <div class="form-group p-3">
                                <label for="description">Description</label>
                                <textarea name="description" rows="5" class="form-control"><?= $vm->details->description ?></textarea>
                            </div>
                            <div class="row justify-content-center mt-2">
                                <button type="submit" class="btn btn-dark col-lg-3 col-md-4">Update</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>


        <?php _template('_system_footer');  ?>
    </div>
</main>
<div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
        <i class="fa fa-cog py-2"> </i>
    </a>
    <div class="card shadow-lg ">
        <div class="card-header pb-0 pt-3 ">
            <div class="float-start">
                <h5 class="mt-3 mb-0">Notifications</h5>
                <p>Send Email.</p>
            </div>

            <!-- End Toggle Button -->
        </div>
        <hr class="horizontal dark my-1">
        <div class="card-body pt-sm-3 pt-0">


        </div>
    </div>
</div>


<?php _template('_core_scripts'); debug_dump([$_SESSION]);   ?>


