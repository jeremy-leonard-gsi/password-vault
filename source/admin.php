<?php
include 'header.php';
include 'menu.php';

print_r($site->config);

echo '<table class="table">';

foreach($site->config as $key => $value){
    if(!in_array($key, $site->config->hiddenFields)){
       echo sprintf('<tr><th>%s</th><td>%s</td></tr>',$key,$value);
    }
}
echo '</table>';

include 'footer.php';