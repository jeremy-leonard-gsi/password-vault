<?php

if(!in_array($site->config->globalAdminGroupDN,$_SESSION['groups'])){
    include('unknownmodule.php');
    exit;
}
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

ksort($keys);
?>
<form method="post">
    <table class="table table-sm">
        <tr><th>Key</th><th>Value</th></tr>
        <?php
            foreach($keys as $key => $value){
               echo sprintf('<tr><th>%s</th><td><input class="form-control" type="text" name="%s" value="%s"></td></tr>',$key,$key,$value);
            }
       ?>
    </table>
</form>
<?php
include 'footer.php';