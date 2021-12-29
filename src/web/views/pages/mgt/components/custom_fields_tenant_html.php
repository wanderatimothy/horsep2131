
 <?php


if(count($data['custom_tenant_fields'])):

?>
<hr class="bg-gradient-info mt-2">
<h5>Custom fields</h5>
<div class="form-check d-none">
    <input type="checkbox" class="form-check-input " readonly   name="has_custom_fields" checked>
    <label for="has custom fields" class="form-check-label">Custom fields on</label>
</div>
<?php

// group fields 
$rows = [];
$counter = 1;
for($i = 0; $i <count($data['custom_tenant_fields']); $i++):
    $rows['row_'.$counter][] = $data["custom_tenant_fields"][$i];
    if(($i+1) % 3 == 0){
        $counter ++;
    }
    
endfor;


foreach($rows as $rowIndex => $rowValue):

    echo '<div class="row my-1">'; //opens the row

    for($x=0; $x < count($rowValue); $x++):

        ?>

        <div class="form-group col-g-4 col-md-4 col-sm-12">
            <label class="form-label" for="<?=$rowValue[$x]->name?>"><?=$rowValue[$x]->name?></label>
            <input required type="<?=$rowValue[$x]->type?>" class="form-control" name="<?=strtolower(str_replace(' ','_', $rowValue[$x]->name))?>"  >
        </div>
        
        
        <?php
    endfor;

    echo "</div>"; //closes the row

endforeach;


endif;