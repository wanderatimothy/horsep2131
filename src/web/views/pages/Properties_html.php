<?php

use web\libs\Session;  ?>
<?php _template('_sidebar', ['active' => 'properties']); ?>
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <!-- Navbar -->
    <?php _template('_system_navbar'); ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4 " style="height: fit-content;">
        <div class=" d-flex justify-content-center align-items-center">
            <button type="button" id="p_add_btn" class="btn bg-gradient-info btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#createProperty">Add Property</button>
            <button type="button" class="btn bg-gradient-info btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#createLandlord">Add Landlord</button>
            <button type="button" id="view-landlords-list" class="btn bg-gradient-info btn-sm mx-1 fw-bold" data-bs-toggle="modal" data-bs-target="#viewLandlords"><i class="bi-eye"></i><span class="ms-1">Landlords</span>  </button>
        </div>
        <div class="row my-4">
            <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                            <div class="col-lg-6 col-7">
                                <h6>Properties</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-check text-info" aria-hidden="true"></i>
                                    <span class="font-weight-bold ms-1" id="properties-table-counter">0</span> Managed.
                                </p>
                            </div>
                    </div>
                    <div class="card-body px-0 ">
                        <div class="table-responsive">
                            <table id="properties-table" class="table text-center ">
                               <!-- property table -->
                            </table>
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



<!-- modal -->
<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="createLandlord" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-uppercase text-white">Add Landlord</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="landlord_form" method="POST" action="<?= app_url('api/landlord') ?>">
                    <div id="landlord_form_feedback"></div>
                    <div class="row justify-content-around">
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="name">Landlord's Name</label>
                            <input type="text" name="names" class="form-control rounded-5 " id="l_name" placeholder="Names">
                            <span id="names_error" class="text-danger error-feedback"></span>
                        </div>
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="name">Contact</label>
                            <input type="text" name="contact" class="form-control rounded-5 " id="l_contact" placeholder="Contact">
                            <span id="contact_error" class="text-danger error-feedback"></span>
                        </div>
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="name">Landlord's Email</label>
                            <input type="email" name="email" class="form-control rounded-5 " id="l_email" placeholder="Email">
                            <span id="email_error" class="text-danger error-feedback"></span>

                        </div>
                    </div>
                    <div class="row justify-content-around my-2">
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="commission">Commission (%) </label>
                            <input type="number" name="commission" class="form-control rounded-5 " id="l_commission" min="4" max="100" placeholder="commission (%)">
                            <span id="commission_error" class="text-danger error-feedback"></span>

                        </div>
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="name">Tenants</label>
                            <input type="number" value="0" name="tenants" class="form-control rounded-5 " id="l_tenants" placeholder="tenants">
                            <span id="tenants_error" class="text-danger error-feedback"></span>
                        </div>
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="name">Expected Collection</label>
                            <input type="number" name="collection_expected" class="form-control rounded-5 " id="l_exp_collection" placeholder="Expected Amount">
                            <span id="collection_expected_error" class="text-danger error-feedback"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" id="l_submit_btn" class="btn btn-dark  w-75">Save <i class="bi bi-save"></i> </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-info btn-sm" data-bs-dismiss="modal">close</button>
            </div>
        </div>
    </div>
</div>
<!-- modal -->

<!-- property modal -->
<div class="modal" data-bs-backdrop="static" data-bs-keyboard="false" id="createProperty" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable  modal-lg">
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
                    <div class="row justify-content-around">
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
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="location">Rent Amount</label>
                            <input type="number" name="rent_amount" min="0" max="5000000"  class="form-control rounded-5" id="p_rent" placeholder="Rent Amount">
                            <span id="rent_amount_error" class="text-danger error-feedback"></span>
                        </div>
                    </div>   
                    <?php _template('pages.mgt.components.custom_field' , $data); ?>
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

<!-- landlords view -->
<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" id="viewLandlords">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-uppercase text-white">Landlords</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
             <ul id="landlords-list" class="list-group">

             </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-info btn-sm" data-bs-dismiss="modal">close</button>
            </div>
        </div>
    </div>
</div>

<!-- delete-modal -->
<div class="modal fade" id="delete-property" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered ">
        <form  class="modal-content" id="property_deletion_form" method="POST">
            <div id="property_deletion_form_feedback"></div>
            <div class="modal-header bg-gradient-warning">
               <h5>Delete Property</h5>
            </div>
            <div class="modal-body">
                <p class="text-center py-3 fw-bold text-muted">Are you sure about this property ?.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="submit" class="btn btn-dark btn-sm" data-bs-dismiss="modal">Delete</button>
                <button type="button" class="btn bg-gradient-info btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- landlords view -->


<?php _template("_core_scripts") ?>

<script>
    
    $(document).ready(function() {
        var _token = "<?= Session::get('_TOKEN')?>";
        var base_url = "<?=app_url('')?>";
        var property_types = new Select('property-types',{
            key:'name',
            value:'id'
        },{
            url: base_url + "api/types",
            token:_token
        })
        var select_landlord = new Select('select-landlord',{
            key:'names',
            value:'id'
        },{
            url: base_url + "api/landlords",
            token:_token
        })
        var properties_table = new Table(
         [
             {
                 title:'No',
                 name:'id',
                 data:(data,index)=>{return`${++index}`}
             },
             {
                title:'Property Name',
                name:'property_label',
                data:null
            },
            {
                title:'Location',
                name:'location',
                data:null},
            {
                title:'Action',
                name:'',
                data:(data)=>{
                
                 return `<div class="dropdown">
                                <button data-bs-toggle="dropdown" type="button" class="btn btn-sm btn-info">menu</button>
                                <div class="dropdown-menu">
                                    <div class="dropdown-item">
                                      <a href="${base_url}property/${data.id}" > <i class="bi bi-gear-fill"></i> Manage </a>
                                    </div>
                                    <div class="dropdown-item">
                                      <a href=""><i class="bi bi-pencil-square text-info"></i> <span class="ms-1">Edit</span></a>
                                   </div>
                                   <div class="dropdown-item">
                                     <a href="javascript:void;" data-bs-toggle="modal" data-bs-target="#delete-property" data-delete="${data.id}" data-object-type="property" class="delete-btn" ><i class="bi bi-trash text-danger me-1"></i>Delete</a>
                                 </div>
                            </div>
                        </div>
                     `
                }
             }
         ],
        'properties-table',
        {
            url:base_url+'api/properties',
            token:_token 
        },function(){
            document.querySelectorAll('.delete-btn').forEach((node)=>{
                node.addEventListener('click',e=>{
                    let el = e.target
                    // console.log(e)
                    let id = el.getAttribute("data-delete")
                    let type = el.getAttribute("data-object-type")
                    if(type == 'property'){
                        DeleteAction({
                            action:`${base_url}api/property/${id}/delete`,
                            delete_form : 'property_deletion_form'
                        })
                    }

                })
            })
        })
        
        property_types.init()
        select_landlord.init();
        properties_table.init();
        select_landlord.useDataSetToDoSomething(function(dataSet){
           var el = 'landlords-list';
           var root_element = document.getElementById(el)
           RemoveAllChildren(root_element)
           if(dataSet.length == 0){
            let component = new Component({
                template : (data)=>{
                    return `<div class="p-4 text-center">No Landlords </div>`
                },
                data:null
            })
            root_element.appendChild(component.create())
            
           }else{
               dataSet.forEach((item , index) => {
                   let component = new Component({
                       template : (data)=>{
                           return `
                           <li class="list-group-item border-0 d-flex p-4 mb-2 mt-3 bg-gray-100 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="mb-3 text-sm">${data.names}</h6>
                                <span class="mb-2 text-xs">Phone: <span class="text-dark font-weight-bold ms-sm-2">${data.contact}</span></span>
                                <span class="mb-2 text-xs">Email Address: <span class="text-dark ms-sm-2 font-weight-bold">${data.email}</span></span>
                                <span class="text-xs">Commission: <span class="text-dark ms-sm-2 font-weight-bold">${data.managers_commission} %</span></span>
                            </div>
                            <div class="ms-auto text-end">
                                <a class="btn btn-link text-danger text-gradient px-3 mb-0" href="javascript:;"><i class="far fa-trash-alt me-2"></i>Delete</a>
                                <a class="btn btn-link text-dark px-3 mb-0" href="javascript:;"><i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit</a>
                            </div>
                        </li>
                                           
                           `
                       },
                       data : item
                   })

                   root_element.appendChild(component.create())
               })
           }
           
        })
        
        new ApplicationForm({
            element : 'landlord_form',
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
                'Authentication':_token
            },
            callbacks:{
                success:(data)=>{
                    select_landlord.update()
                },
                error:(error)=>console.log(error)
            }
        })

        new ApplicationForm({
            element : 'property_form',
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
                'Authentication':_token
            },
            callbacks:{
                success:(data)=>{
                    properties_table.update()
                },
                error:(error)=>console.log(error)
            }
        })
               
        // property deletion

        new ApplicationForm({
            element : 'property_deletion_form',
            loader : '',
            messages:{
                success : {
                    expectedStatusCode : 200,
                    text: ''
                },
                failure: 'Operation was unsuccessful.'
            },
            headers:{
                'Authentication':_token
            },
            callbacks:{
                success:(data)=>{
                    $.notify("Property was deleted successfully","success")
                    properties_table.update()
                },
                error:(error)=>console.log(error)
            }
        })
    });
</script>