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
    <?php
        foreach($keys as $key => $value){
            echo addFormElement($key, $value);
        }
   ?>
</form>
<?php
include 'footer.php';

function addFormElement($key,$value){
    $output = <<<END
        <div class="form-group">
            <label for="%sId">%s</label>
            <input class="form-control" type="text" id="%sId" name="%s" value="%s">
        </div>
    END;
    return sprintf($output,$key,$key,$key,$key,$value);
}