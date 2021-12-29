<?php

use web\libs\Session;

if(Session::_info($data['info_key'])){
?>
    <div class="alert alert-dismissible alert-danger">
        <button class="btn-close" data-bs-dismiss="alert"></button>
        <?= Session::_info($data['info_key'])?>
    </div>

<?php
}

