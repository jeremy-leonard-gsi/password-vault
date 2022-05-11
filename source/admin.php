<?php
include 'header.php';
include 'menu.php';

echo '<table class="table">';
foreach($site->config as $key => $value){
    if($key != 'db'){
       echo sprintf('<tr><th>%s</th><td>%s</td></tr>',$key,$value);
    }
}
echo '</table>';

include 'footer.php';