<?php
include 'header.php';
include 'menu.php';

print_r(array_diff($site->config, $_CONFIG));

echo '<table class="table">';
foreach(array_diff($site->config, $_CONFIG) as $key => $value){
       echo sprintf('<tr><th>%s</th><td>%s</td></tr>',$key,$value);
}
echo '</table>';

include 'footer.php';