<?php
include 'header.php';
include 'menu.php';

//print_r(array_diff((array)$site->config, $_CONFIG));

echo '<table class="table">';
foreach($site->config as $key => $value){
    if($key != 'db'){
       echo sprintf('<tr><th>%s</th><td>%s</td></tr>',$key,$value);
    }
}
echo '</table>';

include 'footer.php';