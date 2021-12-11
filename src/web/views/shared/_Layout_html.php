<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'];  ?> </title>
    <!-- bootstrap 5.1  -->
    <!-- on production change to a cdn version of bootstrap -->
    <link rel="stylesheet" href="<?=_asset('libs\bootstrap-5.1\dist\css\bootstrap.min.css') ?>">
    <!-- bootstrap icons -->
    <link rel="stylesheet" href="<?=_asset('libs\bootstrap-5.1\dist\icons\bootstrap-icons.css') ?>">
</head>
<body>
    <?php
    // loads the views and templates 
     _template($name , $data ,$before) ; 
     ?>
    <!-- bootstrap scripts -->
    <script src="<?=_asset('libs\bootstrap-5.1\dist\js\bootstrap.bundle.min.js')?>"></script>
</body>
</html>