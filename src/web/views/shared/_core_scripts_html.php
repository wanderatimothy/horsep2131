  <!--   Core JS Files   -->
  <script src="<?=_asset('assets/js/core/popper.min.js')?>"></script>
  <script src="<?=_asset('assets/js/core/bootstrap.min.js')?>"></script>
  <script src="<?=_asset('assets/js/plugins/perfect-scrollbar.min.js')?>"></script>
  <script src="<?=_asset('assets/js/plugins/smooth-scrollbar.min.js')?>"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

<script src="<?=_asset('assets/js/jquery.3.4.1.min.js')?>"></script>
<script src="<?=_asset('assets/js/core/notify.js')?>"></script>
<script src="<?=_asset('assets/js/app.js')?>"></script>


<script>
  // functions

  var handleClick = (el,callback) => {
    el.on('click' , callback)
  }
  


  var asyncResourcesFetch = (url ,callback  , token = '') => {
    $.ajax({
      method:'GET',
      url:url,
      contentType:'Json',
      headers:{
        'Authentication':token
      },
      success:callback,
      error:(xhr,status)=>{
        $.notify('error', xhr.responseJSON.data.errors['info'])
        console.log(xhr.responseJSON.data)
      }
    });
  }

 
     
  

</script>