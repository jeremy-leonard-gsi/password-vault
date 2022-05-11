<?php

if(!in_array($site->config->globalAdminGroupDN,$_SESSION['groups'])){
    include('unknownmodule.php');
    exit;
}

if($site->request->method=='POST'){
    foreach($site->request->post->config as $key => $value){
        $site->config->$key=$value;
    }
    $site->config->saveConfig();
    header("Location: ".$_SERVER['REQUEST_URI']);
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
<div class="container">
    <form method="post">
        <?php
            foreach($keys as $key => $value){
                echo addFormElement($key, $value);
            }
       ?>
        <input class="btn btn-primary btn-sm" type="submit" value="Save">
    </form>
</div>
<?php
include 'footer.php';

function addFormElement($key,$value){
    $output = <<<END
        <div class="form-group">
            <label for="%sId">%s</label>
            <input class="form-control form-control-sm" type="text" id="%sId" name="config[%s]" value="%s">
        </div>
    END;
    return sprintf($output,$key,$key,$key,$key,$value);
}