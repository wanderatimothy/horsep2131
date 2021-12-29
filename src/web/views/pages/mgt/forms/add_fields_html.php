<div class="modal fade" id="fields_form" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-uppercase text-white">Add Field</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="create_field_form" action="<?=app_url('api/create-field')?>">
                <div id="create_field_form_feedback"></div>
                <div class="row mb-1">
                    <div class="col-md-4 col-lg-4 form-group">
                       <label class="form-label" for="field name">Name</label>
                       <input type="text" maxlength="100" class="form-control" name="name" placeholder="Field Name">
                       <span id="name_error" class="text-danger error-feedback"></span>
                    </div>
                    <div class="col-md-4 col-lg-6 form-group">
                      <label class="form-label">Type</label>
                      <select class="form-control form-select" name="type_id">
                            <?php
                            foreach($data['fields'] as $field):
                                ?>
                                <option <?= $field->type == "text" ? "selected" : '' ?>     value="<?=$field->id?>"><?=$field->type?></option>
                                <?php
                            endforeach;
                            ?>
                        </select>
                      <span id="type_id_error" class="text-danger error-feedback"></span>
                    </div>
                    <div class="col-md-4 col-lg-6 form-group">
                        <label for="model">Model</label>
                        <input type="text" id="field-model" maxlength="100" readonly class="form-control" name="model" value="">
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                     <button type="submit" id="f_submit_btn" class="btn btn-dark  w-75">Save <i class="bi bi-save"></i> </button>
                </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-info btn-sm" data-bs-dismiss="modal">close</button>
            </div>
        </div>
    </div>
</div>