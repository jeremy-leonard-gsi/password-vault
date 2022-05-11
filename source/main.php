<?php
session_start();

include('config.php');
include('class/SiteObject.class.php');
include('class/Config.class.php');
include('class/bootstrap.class.php');
include('class/Request.class.php');
include('class/Site.class.php');
include('class/Passwordvault.class.php');

$site = new Site($_CONFIG);

if($site->config->requireSSL AND $_SERVER["HTTPS"]!="on"){
	header("Location: https://".$_SERVER["HTTP_HOST"]."/".$_SERVER["REQUEST_URI"]);
	if($site->config->debug) {
		error_log("Redirecting for SSL");
	}
}


$pwv = new Passwordvault($site->config);

if($site->config->debug) {
	error_log($_SERVER['REQUEST_URI']);
	error_log($_SERVER['REQUEST_METHOD']);
	error_log("API Key: ".$site->request->apikey??'');
	error_log("Is Valid API Key: ".$site->validateAPI());
}
//Force login if not already authenticated.

if((isset($_SESSION["authenticated"])!=true OR $_SESSION["authenticated"]!=true) AND !isset($site->request->apikey)) {
	if($site->config->debug){
		error_log("Setting module to login.");
	}
	$module='login';
}elseif((isset($_SESSION["authenticated"])==true AND $_SESSION["authenticated"]==true) OR $site->validateAPI()){
	$module=$site->request->module;
}else{
	header('HTTP/1.1 403 Forbidden');
	exit;
}

if($site->config->debug) {
	error_log("Active Module: ".$module);
}

switch($module) {
	case 'login':
		include('login.php');
		break;
	case '':
	case 'passwordvault':
		include('passwordvault.php');
		break;
        case 'admin':
            include ('admin.php');
            break;
	default:
		include('unknownmodule.php');
}

?>
