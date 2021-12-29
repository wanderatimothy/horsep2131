<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'];  ?> </title>
     <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="<?= _asset('assets/css/nucleo-icons.css')?>" rel="stylesheet" />
  <link href="<?= _asset('assets/css/nucleo-svg.css')?>" rel="stylesheet" />
  <link href="<?= _asset('assets/css/bootstrap-icons.css')?>" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="<?= _asset('assets/css/nucleo-svg.css')?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="<?=_asset('assets/css/soft-ui-dashboard.css?v=1.0.3')?>" rel="stylesheet" />
</head>
<body class="g-sidenav-show  bg-gray-100">
    <?php
    // loads the views and templates 
    // debug_dump([$viewData , $data]);
     _template($viewData['view_name'] , $viewData['view_data'] ) ; 
     ?>
    <!-- bootstrap scripts -->
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?= _asset('assets/js/soft-ui-dashboard.js')?>"></script></body>
</html>