  <section class="min-vh-100 mb-8">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('<?= _asset('/assets/img/curved-images/curved14.jpg')?>');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
            <h1 class="text-white mb-2 mt-5">Welcome!</h1>
            <p class="text-lead text-white">Use these awesome forms to create new account in your project for free.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-2 mb-0">
              <h5 class="mt-1">Register</h5>
            </div>
            <div class="card-body mt-0">
            <?php _template('_form_success' , ['info_key' => 'success'])  ?>
            <?php _template('_form_failed' , ['info_key' => 'failed'])  ?>

              <form method="post" action="<?=app_url('create-account')?>" role="form text-left">
                <div class="mb-3">
                  <input type="text" name="contact" class="form-control" placeholder="contact" aria-label="Contact" aria-describedby="contact-addon">
                  <?php _template('_form_validation_message' , ['error_key' => 'contact'])  ?>
                </div>
                
                <div class="mb-3">
                  <input type="email" name="email" autocomplete="false"   class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                  <?php _template('_form_validation_message' , ['error_key' => 'email'])  ?>
                </div>
                <div class="mb-3">
                  <input type="password" name="password" autocomplete="false" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                  <?php _template('_form_validation_message' , ['error_key' => 'password'])  ?>
                </div>

                <div class="mb-3">
                  <input type="password" name="confirm_password" class="form-control" placeholder="confirm" aria-label="Password" aria-describedby="password-addon">
                  <?php _template('_form_validation_message' , ['error_key' => 'confirm_password'])  ?>
                </div>
                <div class="form-check form-check-info text-left">
                  <input class="form-check-input" required type="checkbox" value="" id="flexCheckDefault" checked>
                  <label class="form-check-label" for="flexCheckDefault">
                    I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign up</button>
                </div>
                <p class="text-sm mt-3 mb-0">Already have an account? <a href="<?= app_url('login') ?>" class="text-dark font-weight-bolder">Sign in</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php _template('_form_footer')  ?>

  <?php _template('_core_scripts') ?>
  
