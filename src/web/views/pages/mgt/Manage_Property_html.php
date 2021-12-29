<?php

use web\libs\Session;

_template('_sidebar' , ['active' => 'properties']); ?>
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <!-- Navbar -->
    <?php _template('_system_navbar'); ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="row justify-content-between align-items-end">
            <div class="col-md-4 col-sm-6 col-lg-3 my-2">
                <h4><?= $data['property']->property_label ?></h4>
            </div>
            <div class="col-md-8 col-sm-12 col-lg-9 d-flex justify-content-end">
                 <button type="button" id="u_add_btn" class="btn bg-gradient-info btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#createUnit">Add Unit</button>
                 <button type="button" id="t_add_btn" class="btn bg-gradient-info btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#createTenant">Add Tenant</button>
                 <button type="button" id="p_assign_manager_btn" class="btn bg-gradient-info btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#assignManager">assign manager</button>
                 <button type="button" id="p_delete_btn" class="btn bg-gradient-danger btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#deleteProperty">Delete</button>
                 <button type="button" id="p_reload" class="btn bg-gradient-secondary btn-sm mx-1" ><i class="bi bi-arrow-repeat"></i>Reload</button>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Expectation</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        shs <?=$data["property"]->rent_amount?>
                                        <span class="text-info text-sm font-weight-bolder">0%</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Tenants</p>
                                    <h5 class="font-weight-bolder mb-0" id="t_tile">
                                        0
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Units</p>
                                    <h5 id="p_units_tile" class="font-weight-bolder mb-0">
                                        0
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Vacancies</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        0
                                        <span class="text-info text-sm font-weight-bolder">0</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Tenants</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-3">
                        <!-- <div class="table-responsive"> -->
                            <table class="table text-center align-items-center mb-2">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Unit</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="t_table">

                                </tbody>
                            </table>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h6>Collections overview</h6>
                        <p class="text-sm">
                            <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                            <span class="font-weight-bold">0%</span> this month
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline timeline-one-side">
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="ni ni-person text-danger text-gradient"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">0 percent collected</h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"> 13 DEC 2021</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row  mt-lg-3">
            <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Units</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-3">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-2 ">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Label</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Floor</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rooms</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rent Amount</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Facilities</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Self Contained</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Occupants</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="units_table" >

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <?php _template('_system_footer');  ?>
    </div>
</main>
<div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark  rounded rounded-circle position-fixed px-3 py-2 btn btn-sm btn-info bg-gradient-info">
        +
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

<!-- modals -->
<?php _template('pages.mgt.forms.add_unit',['property' => $data['property']->id])  ?>
<?php _template('pages.mgt.forms.add_tenant' ,$data )  ?>

<!-- modals -->

<?php _template('_core_scripts') ?>

<script>

    $(document).ready(function(){
        
        var addon_btn = $('#u_addon_btn_add');
        var u_add_btn = $("#u_add_btn");
        var u_add_form = $('#u_form');
        var u_floors = $('#u_floors');
        var addon_container = $("#u_addon_container");
        var tenant_photo_upload = $("#tenant_photo_upload");
        var upload_preview = $("#upload_preview_area");

        var rows = 0;
        let _token = '<?= Session::get('_TOKEN') ?>'
        $("#u_start_index").hide();
        $("#u_number_to_generate").hide();

        tenant_photo_upload.on('change',e => {
           var file = e.target.files[0]
           upload_preview.empty();
           upload_preview.append(`<img src="${URL.createObjectURL(file)}"  class="img img-thumbnail" width="200" height="150" />`);
        })

        function loadTenants(update = false){
            if(update){
                $.notify("Updating Tenants information " , 'success')
            }
            asyncResourcesFetch('<?=app_url('api/property/'.$data['property']->id.'/tenants')?>',(data)=>{
            $("#t_table").empty();
            dataRows = data.data;
            if(dataRows.length == 0){
                ("#t_table").append(`<tr><td colspan="4" class="text-center">No tenants on this property</td></tr>`)
            }else{
                $("#t_tile").html(`${dataRows.length}`)
                $('#t_table').empty();
                dataRows.forEach((item,index) => {
                    $("#t_table").append(`
                        <tr class="text-center">
                            <td>${item.names}</td>
                            <td>${item.contact}</td>
                            <td>${item.label}</td>
                            <td>
                            <div class="dropdown">
                               <button type="button" data-bs-toggle="dropdown" class="btn  fw-bold bg-gradient-info" ><i class="bi bi-three-dots-vertical"></i></button>
                                <div class="dropdown-menu">
                                   <div class="dropdown-item" >
                                        <a href="#"><i class="bi bi-eye text-info"></i> <span class="ms-1">View</span></a>
                                   </div>
                                   <div  class="dropdown-item">
                                        <a><i class="bi bi-envelope text-info"></i> <span class="ms-1">Message</span></a>
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
                    `)
                })
            }
        },_token);
        }
        
        function loadUnits(update = false){
            if(update){
                $.notify("Updating units information " , 'success')
            }
            asyncResourcesFetch('<?=app_url('api/property/'.$data['property']->id .'/units')?>',(data)=>{
                $('#units_table').empty();
                $('#t_available_units').empty();
                dataRows = data.data;
                $("#p_units_tile").html(`${dataRows.length}`)
                dataRows.forEach((item , $index)=>{
                    if(item.is_occupied == false){
                        $("#t_available_units").append(`
                             <option value="${item.id}" >${item.label}</option>
                        `);
                    }
                    

                    $('#units_table').append(`
                      <tr class="text-center" >
                        <td>${ ++ $index }</td>
                        <td>${item.label}</td>
                        <td>${item.floor_name}</td>
                        <td>${item.rooms}</td>
                        <td>${item.rent_amount}</td>
                        <td>${item.is_occupied == 0 ? '<span class="fw-bold text-danger">VACANT</span>' : '<span class="fw-bold"> OCCUPIED </span>' }</td>
                        <td>${item.facilities}</td>
                        <td>${item.self_contained ?  'Yes' : 'No'}</td>
                        <td>${item.occupants_limit}</td>
                        <td>
                            <div class="dropdown">
                               <button type="button" data-bs-toggle="dropdown" class="btn  fw-bold bg-gradient-info" ><i class="bi bi-three-dots-vertical"></i></button>
                                <div class="dropdown-menu">
                                   <div class="dropdown-item" >
                                        <a href="property/${item.property_id}unit/${item.id}">Manage</a>
                                   </div>
                                   <div class="dropdown-item">
                                        <a href=""><i class="bi bi-pencil-square text-info"></i> <span class="me-1">Edit</span></a>
                                   </div>
                                   <div class="dropdown-item">
                                        <a href=""><i class="bi bi-trash text-danger"></i> <span class="me-1">Delete</span></a>
                                   </div>
                                </div>
                             </div>
                        </td>
                      </tr>
                    `);
                })
            },_token)
        }
        function Reload(){
          loadTenants(true);
          loadUnits(true);
        }
        
        
        loadTenants();
        loadUnits();

        

        handleClick($('#p_reload'),event=>{
            if(window.confirm("Action causes reloading of the data any unsaved changes will be lost")){
                Reload();
            }
        })
        handleClick(u_add_btn , function(event){
         asyncResourcesFetch('<?=app_url('api/floors')?>' ,(data)=>{
             u_floors.empty();
                 data.data.forEach((item , $index) => {
                  u_floors.append(`<option value ="${item.id}">${item.floor_name}</option>`)    
                 })
         }, _token)
        });
        handleClick($('#u_auto_generate'),e => {
            var checkbox = document.getElementById('u_auto_generate');
            if(checkbox.checked){
                $("#u_start_index").show();
                $("#u_number_to_generate").show();
            }else{
                $("#u_start_index").hide();
                $("#u_number_to_generate").hide();
            } 
        })

        handleClick(addon_btn , function(event){
            var html_template = `
            <div class="row align-items-end " id="row_${++rows}">
                <div class="form-group col-lg-2 col-md-3  px-1">
                    <label for="addon-type" class="form-label" >Addon Type</label>
                    <select class="form-select form-control" name="addon_type_id[]">
                        <option value="" selected >Choose</option>
                        <?php foreach ($data['add_on_types'] as $type ) echo "<option value='".$type->id."'>".$type->type_name."</option>"  ?>
                    </select>
                </div>
                <div class="form-group col-lg-3 col-md-3 px-1">
                    <label for="addon-name" class="form-label" >Addon Name</label>
                    <input type="text" name="addon_name[]" placeholder="i.e electricity , water , garbage ...etc" class="form-control" />
                </div>
                <div class="form-group col-lg-3 col-md-3 px-1">
                    <label for="addon-name" class="form-label" >Addon Charge</label>
                    <input type="number" name="addon_cost[]" min="0" max="10000000" placeholder"Amount" class="form-control" />
                </div>
                <div class="form-group col-lg-2 col-md-2 px-1">
                    <label for="addon-name" class="form-label" >Meter (Optional)</label>
                    <input type="text" name="addon_meter[]" placeholder="i.e electricity , water , router for Bills" class="form-control" />
                </div>
                <button type="button" class="btn mt-2 col-lg-1 col-md-2 btn-sm btn-danger addon-del" data-parent="#row_${rows}"  ><i class="bi bi-trash"></i></button>
            
            </div>
            `
            addon_container.append(html_template);
            handleClick($('.addon-del'),function(event){
            let parent = $(this).attr('data-parent');
            $(parent).remove();
            })   
        })


        asyncSendFormData('u_form',(response)=>{
            $('#u_form_feedback').empty().html(`
              <div class="alert alert-success alert-dismissible fade show">
                <strong>Hurray!</strong> Unit(s) were added successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" ></button>
              </div>
            `)  
            document.getElementById('u_form').reset();
            loadUnits(true);
        },_token  )


        <?php
        // include onboarding rules javascript
        if(count($data['onboarding_rules']) > 0):
        
        ?>
        var rules = <?= json_encode($data['onboarding_rules']) ?>;
        rules.forEach((rule , index)=>{
          if(rule.hasPaymentImplication){
            // register a payment submit handler
             var id =  String(rule.rule_title).toLowerCase().replace(" ","_")
            asyncSendFormData(`${id}_form`,(response)=>{
            $(`${id}_form_feedback`).empty().html(`
              <div class="alert alert-success alert-dismissible fade show">
                <strong>Rule has been satisfied!</strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" ></button>
              </div>
            `)  
            
        },_token);
          }

        });




        <?php
        
        endif;
        // on boarding rules javascript
        ?>
        asyncSendFormData('t_form',(response)=>{
            $('#t_form_feedback').empty().html(`
              <div class="alert alert-success alert-dismissible fade show">
                <strong>Hurray!</strong> Tenant was added successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" ></button>
              </div>
            `)  
            upload_preview.empty();
            loadUnits(true);

        },_token);
     
    });




</script>
