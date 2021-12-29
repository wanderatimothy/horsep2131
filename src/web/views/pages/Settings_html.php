<?php

use web\libs\Session;

_template('_sidebar', ['active' => 'settings']); ?>
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <!-- Navbar -->
    <?php _template('_system_navbar'); ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <h4>Settings</h4>
        <ul class="nav nav-tabs  bg-transparent text-center">
            <li class="nav-item">
                <button class="nav-link link-info fw-bold active" data-bs-toggle="tab" role="tab" aria-selected="true" data-bs-target="#account_tab">Account</button>
            </li>
            <li class="nav-item">
                <button class="nav-link link-info fw-bold " data-bs-toggle="tab" role="tab" aria-selected="true" id="p-settings-btn" data-bs-target="#property_tab">Property</a>
            </li>
            <li class="nav-item">
                <button class="nav-link link-info fw-bold " data-bs-toggle="tab" role="tab" aria-selected="true" id="r-rules-btn" data-bs-target="#units_tab">Rent Rules</button>
            </li>
            <li class="nav-item">
                <button class="nav-link link-info fw-bold " data-bs-toggle="tab"  role="tab" aria-selected="true" id="t-settings-btn"  data-bs-target="#tenants_tab">Tenants</button>
            </li>
            <li class="nav-item">
                <button class="nav-link link-info fw-bold " data-bs-toggle="tab" role="tab" aria-selected="true" data-bs-target="#reports_tab">Reports</button>
            </li>
            <li class="nav-item">
                <button class="nav-link link-info fw-bold " data-bs-toggle="tab" role="tab" aria-selected="true" data-bs-target="#notifications_tab">Notifications</button>
            </li>
        </ul>

        <div class="tab-content" id="settings-tab-content">
            <div class="tab-pane fade-show active" id="account_tab">
                <div class="row  justify-content-between">
                    <div class="form-side col-lg-9 col-md-9 col-sm-7">
                        <form action="<?=app_url('api/account')?>" id="account_settings_form" class="p-2">
                            <h5>Account</h5>
                            <div id="account_settings_form_feedback"></div>
                            <div class="row">
                                <div class="form-group mb-1 col-md-6 col-lg-6 col-sm-12">
                                    <label class="form-label" for="business_name">Business Name</label>
                                    <input id="b_name" type="text" placeholder="Business Name" name="business_name" class="form-control">
                                    <span id="business_name_error" class="text-danger error-feedback"></span>
                                </div>
                                <div class="form-group mb-1 col-md-6 col-lg-6 col-sm-12">
                                    <label class="form-label" for="business_contact">Business Contact</label>
                                    <input id="b_contact" type="text" placeholder="Business Contact" name="business_contact" class="form-control">
                                    <span id="business_contact_error" class="text-danger error-feedback"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-1 col-md-6 col-lg-6 col-sm-12">
                                    <label class="form-label" for="business_email">Business Email</label>
                                    <input id="b_email" type="email" placeholder="Business Email" name="business_email" class="form-control">
                                    <span id="business_email_error" class="text-danger error-feedback"></span>
                                </div>
                                <div class="form-group mb-1 col-md-6 col-lg-6 col-sm-12">
                                    <label class="form-label" for="business_address">Business Address</label>
                                    <input id="b_address" type="text" placeholder="Business Address" name="business_address" class="form-control">
                                    <span id="business_address_error" class="text-danger error-feedback"></span>
                                </div>
                            </div>
                            <div class="row align-items-start">
                                <div class="form-group mb-1 col-md-6 col-lg-6 col-sm-12">
                                    <label class="form-label" for="business_email">Brand Logo</label>
                                    <input type="file" placeholder="Business Logo " name="business_logo" id="business_logo_upload" class="form-control">
                                    <span id="business_logo_error" class="text-danger error-feedback"></span>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div id="business_logo_preview" class="p-4">
                                        <span class="text-muted text-center p-3">No Logo</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-1 col-md-6 col-lg-6 col-sm-12">
                                    <label class="form-label" for="business_address">Business Address</label>
                                    <input id="b_website_url" type="url" placeholder="https://yourwebsite.com " name="website_url" class="form-control">
                                    <span id="website_url_error" class="text-danger error-feedback"></span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between pt-3 ">
                                <button type="submit" class="btn btn-secondary col-4">Save</button>
                                <button type="reset" class="btn btn-warning col-4">Reset</button>
                            </div>
                        </form>
                    </div>
                    <div class="info-side col-lg-3 col-md-3 col-sm-4  card p-2">
                        <h5 class="text-center my-1">Info</h5>
                        <div class="card-body">
                            <div id="account-info-side" class="">

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="property_tab">
            <h5>Custom Data Fields</h5>
                <p>These are custom data entry fields on your Properties.<br/> 
                These fields will be added onto the form to capture your property details.</p>
                <button type="button" data-bs-toggle="modal"  data-model="property"  data-bs-target="#fields_form" class="btn btn-sm btn-dark add-field">Add</button>
                <div class="card col-sm-12 col-lg-10 col-md-12">
                    <div class="card-body">
                        <table class="table align-items-center mb-2">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>                                   
                                </tr>
                            </thead>
                            <tbody id="pf_table">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="units_tab">
                <div class="container">
                <h5>On Boarding Rule</h5>
                <p class="p-1">
                    These are rules or standards to be enforced while onboarding a tenant.
                    <button type="button" data-bs-target="#on-boarding-rule" data-bs-toggle="modal" class="btn btn-sm btn-dark">Add</button>
                </p>
                <div class="card col-md-12 col-lg-12 col-sm-12">
                        <div class="card-body">
                        <table class="table align-items-center mb-2">
                            <thead>
                                <tr>
                                   <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                                   <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Definition</th>
                                   <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Enforcement</th>
                                   <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Involves Payment</th>
                                   <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>                               
                                </tr>
                            </thead>
                            <tbody id="or-table">

                            </tbody>
                        </table>

                        </div>
                    </div>
                <hr class="bg-gradient-info">
                    <h5>Rent Rules</h5>
                    <p class="text-muted">
                        These are rent payment rules.You can set the length of your payment schedule to a more flexible one that meets your business's needs.
                    </p>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#rental_rules_form" class="btn btn-dark btn-sm">create rule</button>
                    <div class="card col-md-12 col-lg-12 col-sm-12">
                        <div class="card-body">
                        <div class="table-responsive">
                        <table class="table align-items-center mb-2">
                            <thead>
                                <tr>
                                   <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                                   <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Definition</th>
                                   <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Schedule</th>
                                   <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Grace Period</th>
                                   <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>                               
                                </tr>
                            </thead>
                            <tbody id="r-table">

                            </tbody>
                        </table>
                    </div>

                        </div>
                    </div>
                    

                </div>
            </div>
            <div class="tab-pane" id="tenants_tab">
                <h5>Custom Data Fields</h5>
                <p>These are custom data entry fields on your Tenant.<br/> 
                These fields will be added onto the form to capture your tenant details.</p>
                <button type="button" data-bs-toggle="modal" data-model="tenant"  data-bs-target="#fields_form" class="btn btn-sm btn-dark add-field">Add</button>
                <div class="card col-sm-12 col-lg-10 col-md-12">
                    <div class="card-body">
                        <table class="table align-items-center mb-2">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>                                   
                                </tr>
                            </thead>
                            <tbody id="tf_table">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="report_tab"></div>
            <div class="tab-pane" id="notification_tab"></div>
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
<?php _template('pages.mgt.forms.rent_rule_form' , $data);?>
<?php _template('pages.mgt.forms.add_fields' , ['fields' => $data['fields']]);?>

<?php _template('_core_scripts') ?>

<script>

    $(document).ready(function(){
       var info_side = $("#account-info-side");
       var _token = '<?= Session::get('_TOKEN') ?>'
       function loadInfo(update = false){
           if(update){
               $.notify('Updating account information' , 'success');
           }
           asyncResourcesFetch('<?=app_url('api/details')?>',(data)=>{
               var info = data.data      
               $('#b_name').attr('value',info.business_name);
               $('#b_email').attr('value',info.business_email);
               $('#b_address').attr('value',info.business_address);
               $('#b_contact').attr('value',info.business_contact);
               $('#b_website_url').attr('value',info.website_url);

                var account_form = document.getElementById('account_settings_form');
               var action_url = account_form.getAttribute("action");
               account_form.setAttribute("action",action_url + "/"+info.id)
               var image_url = "<?= from_disk('')?>";
               info_side.empty().html(`
               <div class="text-capitalize d-flex my-1"><i class="bi bi-building text-info"></i><span class="text-muted fw-bold mx-1">${info.business_name? `<h6 class="text-success">${info.business_name}</h6>` : '<span class="text-warning">Your business name</span>'}</span></div>
                <div class=" d-flex my-1"><i class="bi bi-telephone-fill text-info"></i><span class="text-muted fw-bold mx-1">${info.business_contact}</span></div>
                <div class="d-flex my-1"><i class="bi bi-mailbox2 text-info"></i><span class="text-muted fw-bold mx-1">${info.business_email? info.business_email : '' }</span></div>
                <div class="text-lowercase d-flex my-1"><i class="bi bi-pin-map-fill text-info"></i><span class="text-muted fw-bold mx-1">${info.business_address? info.business_address : 'no address'}</span></div>
                <div class="text-lowercase d-flex my-1"><i class="bi bi-globe text-info"></i><span class="text-muted fw-bold mx-1">${info.website_url? info.website_url : ''}</span></div>
                <div class="mt-4 p-1 d-flex justify-content-center align-items-center">
                ${info.business_logo?"<img src="+image_url+ info.business_logo+" alt='logo' class='img'  width='100'  />":"No Brand Logo" }
                </div>
               `)

           },_token)
       }
       function loadOBRs(update = false){
        if(update){
               $.notify('Updating on boarding rules' , 'success');
             }
             asyncResourcesFetch('<?=app_url('api/on-boarding-rules')?>',(data)=>{
               var rows = data.data;
               if(data.length == 0){
                   $('#or-table').empty().append(`<tr><td colspan="4">No Rules to show</td></tr>`)
               }else{
                   $("#or-table").empty();
                   rows.forEach((item,index)=>{
                    $("#or-table").append(`
                        <tr class="text-center">
                            <td class="text-capitalize">${item.rule_title}</td>
                            <td class="col-lg-3 col-md-4">${item.rule_description}</td>
                            <td>${item.enforcement == 'strict' ? `<span class="fw-bolder text-primary">STRICT</span>`:`<span class='text-muted fw-bold' >RELAXED</span>`  }</td>
                            <td>${item.hasPaymentImplication?'yes' : 'No'}</td>
                            <td>
                                <div class="dropdown">
                                <button type="button" data-bs-toggle="dropdown" class="btn  fw-bold bg-gradient-info" ><i class="bi bi-three-dots-vertical"></i></button>
                                    <div class="dropdown-menu">
                                    <div class="dropdown-item">
                                        <a href=""><i class="bi bi-pencil-square text-info"></i> <span class="ms-1">Edit</span></a>
                                    </div>
                                    <div class="dropdown-item">
                                        <a href=""><i class="bi bi-trash text-danger"></i> <span class="ms-1">Delete</span></a>
                                    </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);
                 });
               }
           },_token)


       }
       function loadPCFs(update =false){
            if(update){
               $.notify('Updating Property model fields' , 'success');
             }
            asyncResourcesFetch('<?= app_url('api/property-fields')?>' , (data)=>{
                rows = data.data
                if(rows.length == 0){
                    $('#pf_table').empty().append(`<tr><td colspan="4">No Fields to show</td></tr>`)
                }else{
                    $('#pf_table').empty();
                    rows.forEach((item ,index) => {
                        $('#pf_table').append(`
                            <tr class="text-center">
                            <td class="text-capitalize">${item.name}</td>
                            <td class="text-capitalize">${item.type}</td>
                            <td>
                                <div class="dropdown">
                                <button type="button" data-bs-toggle="dropdown" class="btn  fw-bold bg-gradient-info" ><i class="bi bi-three-dots-vertical"></i></button>
                                    <div class="dropdown-menu">
                                    <div class="dropdown-item">
                                            <a href=""><i class="bi bi-pencil-square text-info"></i> <span class="ms-1">Edit</span></a>
                                    </div>
                                    <div class="dropdown-item">
                                            <a href=""><i class="bi bi-trash text-danger"></i> <span class="ms-1">Delete</span></a>
                                    </div>
                                    </div>
                                </div>
                            </td>
                            </tr>
                        
                        `);
                        
                    })
                }
            },_token)
            
       }

       function loadTCFs(update =false){
            if(update){
               $.notify('Updating tenant model fields' , 'success');
             }
            asyncResourcesFetch('<?= app_url('api/tenant-fields')?>' , (data)=>{
                rows = data.data
                if(rows.length == 0){
                    $('#tf_table').empty().append(`<tr><td colspan="4">No Fields to show</td></tr>`)
                }else{
                    $('#tf_table').empty();
                    rows.forEach((item ,index) => {
                        $('#tf_table').append(`
                            <tr class="text-center">
                            <td class="text-capitalize">${item.name}</td>
                            <td class="text-capitalize">${item.type}</td>
                            <td>
                                <div class="dropdown">
                                <button type="button" data-bs-toggle="dropdown" class="btn  fw-bold bg-gradient-info" ><i class="bi bi-three-dots-vertical"></i></button>
                                    <div class="dropdown-menu">
                                    <div class="dropdown-item">
                                            <a href=""><i class="bi bi-pencil-square text-info"></i> <span class="ms-1">Edit</span></a>
                                    </div>
                                    <div class="dropdown-item">
                                            <a href=""><i class="bi bi-trash text-danger"></i> <span class="ms-1">Delete</span></a>
                                    </div>
                                    </div>
                                </div>
                            </td>
                            </tr>
                        
                        `);
                        
                    })
                }
            },_token)
            
       }
       function loadRules(update = false){
            if(update){
               $.notify('Updating Rules' , 'success');
             }
           asyncResourcesFetch('<?=app_url('api/rent-rules')?>',(data)=>{
               var rows = data.data;
               if(data.length == 0){
                   $('#r-table').empty().append(`<tr><td colspan="4">No Rules to show</td></tr>`)
               }else{
                   $("#r-table").empty();
                   rows.forEach((item,index)=>{
                    $("#r-table").append(`
                        <tr class="text-center">
                            <td class="text-capitalize">${item.rule_title}</td>
                            <td width="400">${item.rule_definition}</td>
                            <td class="fw-bold text-muted" >${item.payment_period}</td>
                            <td class="fw-bold text-primary">${item.grace_period}</td>
                            <td>
                                <div class="dropdown">
                                <button type="button" data-bs-toggle="dropdown" class="btn  fw-bold bg-gradient-info" ><i class="bi bi-three-dots-vertical"></i></button>
                                    <div class="dropdown-menu">
                                    <div class="dropdown-item" >
                                            <a href="#"><i class="bi bi-eye text-info"></i> <span class="ms-1">Applied On</span></a>
                                    </div>
                                    <div class="dropdown-item">
                                            <a href=""><i class="bi bi-pencil-square text-info"></i> <span class="ms-1">Edit</span></a>
                                    </div>
                                    <div class="dropdown-item">
                                            <a href=""><i class="bi bi-trash text-danger"></i> <span class="ms-1">Delete</span></a>
                                    </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);
                 });
               }
           },_token)
       }

       handleClick($('#r-rules-btn'),event=>{
            loadOBRs();
            loadRules();
        })
        handleClick($('#t-settings-btn'),event=>{
            loadTCFs();
        })
        handleClick($('#p-settings-btn'),event=>{
            loadPCFs();
        })
        $('.add-field').on('click',function(e){
            var m = $(this).attr('data-model');
            $('#field-model').attr('value',m);
        })

       loadInfo();

       asyncSendFormData('account_settings_form',(response)=>{
        $('#account_settings_form_feedback').empty().html(`
              <div class="alert alert-success alert-dismissible fade show">
                <strong>Success!</strong> Your account details were updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" ></button>
              </div>
            `)  

           loadInfo();
       },_token)

       asyncSendFormData('rent_rule_form',(response)=>{
        $('#rent_rule_form_feedback').empty().html(`
              <div class="alert alert-success alert-dismissible fade show">
                <strong>Success!</strong> Your rule has been created.
                <button type="button" class="btn-close" data-bs-dismiss="alert" ></button>
              </div>
            `)  
            loadRules(true);
       },_token)

       asyncSendFormData('on_boarding_rule_form',(response)=>{
        $('#on_boarding_rule_form_feedback').empty().html(`
              <div class="alert alert-success alert-dismissible fade show">
                <strong>Success!</strong> Your rule has been created.
                <button type="button" class="btn-close" data-bs-dismiss="alert" ></button>
              </div>
            `)  
        loadOBRs(true);
       },_token)

    //    
    asyncSendFormData('create_field_form',(response)=>{
        $('#create_field_form_feedback').empty().html(`
              <div class="alert alert-success alert-dismissible fade show">
                <strong>Success!</strong> Your fields were created.
                <button type="button" class="btn-close" data-bs-dismiss="alert" ></button>
              </div>
            `)  
            loadTCFs();
            loadPCFs()
       },_token)
    });

</script>