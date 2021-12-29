<?php

use web\libs\Session;

if(!is_null(Session::_error($data['error_key']))){
?>

    <span class="text-danger">
    <?= Session::_info($data['error_key'])?>
    </span>

<?php
}

