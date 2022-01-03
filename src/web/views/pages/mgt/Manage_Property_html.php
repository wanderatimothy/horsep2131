<?php

use web\libs\Session;

$vm = $data['vm'];

_template('_sidebar' , ['active' => 'properties']); ?>
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <!-- Navbar -->
    <?php _template('_system_navbar'); ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="row justify-content-between align-items-end">
            <div class="col-md-4 col-sm-6 col-lg-3 my-2">
                <h4 class="text-uppercase"><?=$vm->details->property_label?></h4>
            </div>
            <div class="col-md-8 col-sm-12 col-lg-9 d-flex justify-content-end">
                 <button type="button" id="u_add_btn" class="btn bg-gradient-info btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#createUnit">Add Unit</button>
                 <button type="button" id="t_add_btn" class="btn bg-gradient-info btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#createTenant">Add Tenant</button>
                 <button type="button" id="p_assign_manager_btn" class="btn bg-gradient-info btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#assignManager">assign manager</button>
                 <button type="button" id="p_delete_btn" class="btn bg-gradient-danger btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#deleteProperty">Delete</button>
                 <button type="button" id="data_reload_button" class="btn bg-gradient-secondary btn-sm mx-1" ><i class="bi bi-arrow-repeat"></i>Reload</button>
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
                                        shs <?=$vm->details->rent_amount?>
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
                                    <p class="fw-bold text-muted ps-1 h5" id="tenants-table-counter"></p>

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
                                    <p class="fw-bold text-muted ps-1 h5" id="units-table-counter"></p>
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
                        <div class="col-lg-6 col-7">
                            <h5>Units</h5>
                        </div>  
                    </div>
                    <div class="card-body px-0 pb-3">
                        <div class="table-responsive">
                            <table id="units-table" class="table align-items-center mb-2 ">
                                
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

<?php
 _template('pages.mgt.forms.add_tenant');

 _template('pages.mgt.forms.add_unit',['property' => $vm->details->id]);

 _template('_core_scripts'); 
 ?>

<script>

    var token = "<?= Session::get("_TOKEN")?>"
    var base_url = "<?=app_url("")?>";
    var property = <?= $vm->details->id ?>
</script>
<script>
    $(document).ready(()=>{

    const tenantTable = new Table(
        [
            {
                title : 'No.',
                name:'id',
                data:(data,index) => ++ index
                
            },
            {
                title:'Name',
                name : 'names',
                data:null
            },
            {
                title:'Contact',
                name:'contact',
                data:null,
            },
            {
                title:'Rental Unit',
                name:'label',
                date:null
            },
            {
                title:'Action',
                name:'',
                data:(data,index) => {
                    return `<div class="dropdown">
                                <button data-bs-toggle="dropdown" type="button" class="btn btn-sm btn-info">menu</button>
                                <div class="dropdown-menu">
                                    <div class="dropdown-item">
                                      <a href="${base_url}api/tenant/${data.id}" > <i class="bi bi-eye me-1"></i> View </a>
                                    </div>
                                    <div class="dropdown-item">
                                      <a href=""><i class="bi bi-pencil-square text-info"></i> <span class="ms-1">Edit</span></a>
                                   </div>
                                   <div class="dropdown-item">
                                     <a href="javascript:void;" data-bs-toggle="modal" data-bs-target="#delete-tenant" data-delete="${data.id}" data-object-type="tenant" class="delete-btn" ><i class="bi bi-trash text-danger me-1"></i>Delete</a>
                                 </div>
                            </div>
                        </div>
                     `
                }
            }
        ],
        "tenants-table",
        {
            token :token,
            url : base_url + 'api/property/'+ property + '/tenants'
        },
        null
    )

    const unitsTable = new Table(
        [
            
            {
                title : 'No.',
                name:'id',
                data:(data,index) => ++ index
                
            },
            {
                title : 'Label',
                name:'label',
                data:null,
            },
            {
                title : 'Floor',
                name:'floor_name',
                data:null,         
            },
            {
                title : 'Status',
                name:'id',
                data: (data , index ) => {
                    
                    let { occupants_limit , number_of_occupants , is_occupied } = data
                    
                    if( occupants_limit ==  1  && is_occupied == 1 ) return `<span class="text-success fw-bold ">Occupied</span>`

                    if(occupants_limit > 1  && number_of_occupants != 0 && (number_of_occupants != occupants_limit) ) return `<span class="text-info fw-bold ">Occupied with ${ occupants_limit - number_of_occupants}  ${ (occupants_limit - number_of_occupants) ==  1  ? 'vacancy' : 'vacancies' } </span>`

                    if(occupants_limit == 1 && is_occupied == 0 ) return `<span class="text-warning fw-bold ">Vacant</span>`
                   
                    if(occupants_limit > 1  && number_of_occupants == 0 ) return `<span class="text-warning fw-bold ">Vacant</span>`

                }
                
            },

            {
                title : 'Rent Amount',
                name :'rent_amount',
                data:(data,index) => (data.rent_amount).toLocaleString('en-us')
            },
            {
                title: 'Action',
                name:'',
                data: (data,index) => {
                    return `<div class="dropdown">
                                <button data-bs-toggle="dropdown" type="button" class="btn btn-sm btn-info">menu</button>
                                <div class="dropdown-menu">
                                    <div class="dropdown-item">
                                      <a href="${base_url}property/${property}/unit/${data.id}" > <i class="bi bi-gear me-1"></i>Manage</a>
                                    </div>
                                   <div class="dropdown-item">
                                     <a href="javascript:void;" data-bs-toggle="modal" data-bs-target="#delete-tenant" data-delete="${data.id}" data-object-type="tenant" class="delete-btn" ><i class="bi bi-trash text-danger me-1"></i>Delete</a>
                                 </div>
                            </div>
                        </div>
                     `
                }
            }


        ],
        'units-table',
        {
            token :token,
            url : base_url + 'api/property/'+ property + '/units'
        },
        (data) => {
            // use dataset to create select options
            let el = document.getElementById('units_select')
            RemoveAllChildren(el)
            if(data.length == 0 ){
                let option = document.createElement('option')
                    option.value = row.id
                    option.textContent = 'No units available'
                    el.appendChild(option) 
                    return
            }
            data.forEach((row) => {
                if( row.occupants_limit > row.number_of_occupants )
                {   let option = document.createElement('option')
                    option.value = row.id
                    option.textContent = row.label
                    el.appendChild(option)
                }
            })
        }
    )

    var floorOptions = new Select('floor_selection', {
        key : 'floor_name',
        value: 'id'
    },{
        token : token,
        url : base_url + 'api/floors'
    })

    // floor initialization
    floorOptions.init()

    // table initialization
    
    unitsTable.init()


    tenantTable.init()


    // table initialization

    // unit form configuration
        new ApplicationForm({
            element : 'units_form',
            loader : ()=>{
                return `<div class="d-flex align-items-center">
                            <strong>Processing....</strong>
                            <div class="spinner-border ms-auto text-info" role="status" aria-hidden="true" ></div>
                        </div>
                        `
            },
            messages:{
                success : {
                    expectedStatusCode : 201,
                    text: `<div class="alert alert-success alert-dismissible" role="alert">
                                 <strong>Success!</strong> operation was successful
                                 <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
                            </div>`
                },
                failure: 'Operation was unsuccessful.'
            },
            headers:{
                'Authentication':token
            },
            callbacks:{
                success:(data)=>{
                    unitsTable.update()
                },
                error:(error)=>console.log(error)
            }
        })
               
    // unit form configuration

    // tenant form configuration
        new ApplicationForm({
            element : 'tenants_form',
            loader : ()=>{
                return `<div class="d-flex align-items-center">
                            <strong>Processing....</strong>
                            <div class="spinner-border ms-auto text-info" role="status" aria-hidden="true" ></div>
                        </div>
                        `
            },
            messages:{
                success : {
                    expectedStatusCode : 201,
                    text: `<div class="alert alert-success alert-dismissible" role="alert">
                                 <strong>Success!</strong> operation was successful
                                 <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
                            </div>`
                },
                failure: 'Operation was unsuccessful.'
            },
            headers:{
                'Authentication':token
            },
            callbacks:{
                success:(data)=>{
                    tenantTable.update()
                    unitsTable.update()
                },
                error:(error)=>console.log(error)
            }
        })
               
    // tenant form configuration

    // Reload action 

    document.getElementById('data_reload_button').addEventListener('click',(event) => {
        if(window.confirm('This action will result into reloading of all data')){
            unitsTable.update();
            tenantTable.update();
        }
    })

    // data reload action

     //custom field creator

     new customFieldCreator({
            triggerElement:'tenant_form_add_custom_field',
            containerElement:'tenant_form_custom_field_container'
        })

    // custom field creator

    // units auto generate 
    var toggler = document.getElementById('unit_auto_generate')
    toggler.addEventListener('click',event => {
        let start_index = document.getElementById('start_index')
        if(toggler.checked){
            start_index.removeAttribute("disabled")
        }else{
            start_index.setAttribute('disabled',true)
        }
    })

    // units auto generate



})

</script>

