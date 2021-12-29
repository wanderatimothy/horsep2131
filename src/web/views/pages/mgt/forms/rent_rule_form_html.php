<div class="modal fade" id="rental_rules_form" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-uppercase text-white">Add Rent Rule</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rent_rule_form" action="<?= app_url('api/rent-rule') ?>">
                    <div id="rent_rule_form_feedback"></div>
                    <div class="row mb-1">
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="rule title">Title</label>
                            <input type="text" maxlength="100" class="form-control" name="rule_title" placeholder="Title">
                            <span id="rule_title_error" class="text-danger error-feedback"></span>
                        </div>
                        <div class="col-md-4 col-lg-6 form-group">
                            <label class="form-label">Definition</label>
                            <span id="rule_definition_error" class="text-danger error-feedback"></span>
                            <textarea rows="2" maxlength="200" class="form-control" name="rule_definition"></textarea>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="payment period">Payment Schedule</label>
                            <select class="form-control form-select" name="payment_period">
                                <?php
                                foreach ($data['periods'] as $period) :
                                ?>
                                    <option <?= $period == "30 Days" ? "selected" : '' ?> value="<?= $period ?>"><?= $period ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                            <span id="payment_period_error" class="text-danger error-feedback"></span>
                        </div>
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="grace period">Grace Period</label>
                            <select class="form-control form-select" name="grace_period">
                                <option value="">choose period</option>
                                <?php
                                foreach ($data['periods'] as $period) :
                                ?>
                                    <option value="<?= $period ?>"><?= $period ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                            <span id="grace_period_error" class="text-danger error-feedback"></span>
                        </div>
                        <input type="checkbox" name="onPayment" class="d-none" checked>
                    </div>

                    <div class="d-flex justify-content-center align-items-center">
                        <button type="submit" id="rr_submit_btn" class="btn btn-dark  w-75">Save <i class="bi bi-save"></i> </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-info btn-sm" data-bs-dismiss="modal">close</button>
            </div>
        </div>
    </div>
</div>


<!-- onboarding rules -->

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="on-boarding-rule">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-uppercase text-white">Add Onboarding Rule</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="on_boarding_rule_form" action="<?= app_url('api/on-boarding-rule') ?>">
                    <div id="on_boarding_rule_form_feedback"></div>
                <div class="row mb-1">
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label" for="rule title">Title</label>
                            <input type="text" required maxlength="100" class="form-control" name="rule_title" placeholder="Title">
                            <span id="o_rule_title_error" class="text-danger error-feedback"></span>
                        </div>
                        <div class="col-md-4 col-lg-6 form-group">
                            <label class="form-label">Definition</label>
                            <span id="rule_description_error" class="text-danger error-feedback"></span>
                            <textarea rows="2" maxlength="200" required class="form-control" name="rule_description"></textarea>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-md-4 col-lg-4 form-group">
                            <label class="form-label">Enforcement</label>
                            <select class="form-control form-select" name="enforcement">
                                <?php
                                foreach ($data['enforcements'] as $enforcement) :
                                ?>
                                    <option <?= $enforcement == "strict" ? "selected" : '' ?> value="<?= $enforcement ?>"><?= $enforcement ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                            <span id="enforcement_error" class="text-danger error-feedback"></span>
                        </div>

                        <div class="col-md-6 col-lg-6 col-sm-12 form-group d-flex">
                            <label class="form-label me-1">Has Payment Implication</label>
                            <div class="form-check mx-1">
                                <input type="radio" name="paymentImplication" value="1" class="form-check-input">
                                <label for="payment involved" class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check mx-1">
                                <input type="radio" name="paymentImplication" value="0" class="form-check-input" checked>
                                <label for="payment involved" class="form-check-label">No</label>
                            </div>
                        </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 form-group d-flex">
                        <label class="form-label me-1">Allow Reason </label>
                        <span class="form-text">(Applicable to relaxed rules )<br />Prompts the user to enter why the rule is being broken. </span>
                        <div class="form-check mx-1">
                            <input type="radio" name="has_reason" value="1" class="form-check-input">
                            <label for="payment involved" class="form-check-label">Yes</label>
                        </div>
                        <div class="form-check mx-1">
                            <input type="radio" name="has_reason" value="0" class="form-check-input" checked>
                            <label for="payment involved" class="form-check-label">No</label>
                        </div>
                    </div>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <button type="submit" id="rr_submit_btn" class="btn btn-dark  w-75">Save <i class="bi bi-save"></i> </button>
            </div>


            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn bg-gradient-info btn-sm" data-bs-dismiss="modal">close</button>
        </div>
    </div>
</div>
</div>