<?php
include 'header.php';
include 'menu.php';

foreach($site->config as $key => $value){
    if(!is_array($value) AND !in_array($key, $site->config->hiddenFields)){
        $keys[$key] = $value;
    }
}
foreach($site->config->encodedFields as $key){
    $keys[$key]=$site->config->$key;
}

echo '<table class="table">';

foreach($keys as $key => $value){
   echo sprintf('<tr><th>%s</th><td>%s</td></tr>',$key,$value);
}
echo '</table>';

include 'footer.php';