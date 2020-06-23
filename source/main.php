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
	header("Location: https://pwvault.centrawellness.org".$_SERVER["REQUEST_URI"]);
}


$pwv = new Passwordvault($site->config);

if($site->config->debug) {
	echo "<!--\n";
	echo "_GET\n";
	print_r($_GET);
	echo "_POST\n";
	print_r($_POST);
	echo "_SERVER\n";
	print_r($_SERVER);
	echo "_SESSION\n";
	print_r($_SESSION);
	echo "Site\n";
	print_r($site);
	echo "-->\n";
}
//Force login if not already authenticated.

if(isset($_SESSION["authenticated"])!=true OR $_SESSION["authenticated"]!=true) {
	$module='login';
}else{
	$module=$site->request->module;
}

switch($module) {
	case 'login':
		include('login.php');
		break;
	case '':
	case 'passwordvault':
		include('passwordvault.php');
		break;
	default:
		include('unknownmodule.php');
}

?>
