<?php
$alert = new bsAlert();
$alert->setDismiss(true);
$alert->setContext(bsAlert::DANGER);

switch($site->request->method) {
	case 'POST':
		switch($site->request->action) {
			case 'login':
				switch($site->config->authType) {
					case "LDAP":
						ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
						$ds = ldap_connect($site->config->authLDAPURI);
						ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION,3);
						ldap_set_option($ds,LDAP_OPT_REFERRALS, false);
						ldap_bind($ds,$site->config->authLDAPBindDN,base64_decode($site->config->authLDAPSecret));
						if(ldap_errno($ds)) {
							$alert->setMessage(ldap_error($ds));
						}else{
							$filter = "(&".$site->config->authLDAPFilter.")(".$site->config->authLDAPUserAttribute."=".$site->request->post->username."))";
							$results = ldap_search($ds, $site->config->authLDAPBaseDN,$filter);
							if(ldap_errno($ds)) {
								$alert->setMessage(ldap_error($ds));
							}else{
								$users = ldap_get_entries($ds,$results);
								if(ldap_errno($ds)) {
									$alert->setMessage(ldap_error($ds));
								}else{
									switch($users['count']) {
										case 0:
											$alert->setMessage('User not found or bad password(0)');
											break;
										case 1:
											ldap_bind($ds,$users[0]['dn'],$site->request->post->password);
											if(ldap_errno($ds)) {
												$alert->setMessage('User not found or bad password(3)');
											}else{
												$_SESSION['groups']=array();
												$_SESSION['authenticated']=true;
												$_SESSION['username']=$users[0][$site->config->authLDAPUserAttribute][0];
												$_SESSION['fullname']=$users[0][$site->config->authLDAPFullnameAttribute][0];
												$_SESSION['dn']=$users[0]['dn'];
												$_SESSION['givenname']=$users[0]['givenname'][0];
												$_SESSION['sn']=$users[0]['sn'][0];
												for($g=0;$g<$users[0]['memberof']['count'];$g++){
													$_SESSION['groups'][]=$users[0]['memberof'][$g];										
												}
												switch($site->config->userSource) {
													case 'LDAP':
													case 'ldap':
														
													
														break;
													case 'TP':
													case 'tp':
														$tpRep = $tp->getRepByFirstLast($_SESSION['givenname'],$_SESSION['sn']);
														$_SESSION['RepNumber']=$tpRep[0]['RepNumber'];
														break;
												}
												header("Location: /".$site->request->post->requestedModule);									
											}
											break;
										default:
											$alert->setMessage('User not found or bad password.(2)');
											break;				
									}
								}
							}
						}
						break;
					default:
						$alert->setMessage("Unknown Auth Type Configured");
						$alert->setDismiss(true);
						$alert->setContext(bsAlert::DANGER);
				}
				break;
			case 'logout':
				foreach($_SESSION as $key => $value){
					unset($_SESSION[$key]);	
				}
				$_SESSION['authenticated']=false;
				header("Location: /login");
				break;
		}
			
	case 'GET':
	
include('header.php');

if($site->request->module=='' OR strtolower($site->request->module)=='login') {
	$site->request->module='passwordvault';
}
?>
<header class="position-sticky" style="top: 0; z-index: 1">
	<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
		<a class="navbar-brand">
			<img src="image/logo.png" alt="Centrawellness Logo" width="30px" height="30px" class="d-inline-block align-top">
			Centrawellness Password Database
		</a>
	</nav>
</header>
<div class="container">
	<div class="row">
		<div class="col col-md"></div>
		<div class="col col-auto">
			<div class="card mt-2" style="width: 20em;">
				<div class="card-header">Login</div>
				<div class="card-body">
					<?php if(is_null($alert->getMessage())==false) echo $alert->show(); ?>
					<form method="post" class="form" action="?module=login">
						<input type="hidden" name="requestedModule" value="<?=$site->request->module?>">
						<input type="hidden" name="action" value="login">
						<div class="form-group mb-0">
							<label class="col-form-label-sm mb-0 p-0" for="username-Id">Username</label>
							<input class="form-control form-control-sm" id="username-Id" name="username" type="text" placeholder="username">
						</div>											
						<div class="form-group">
							<label class="col-form-label-sm mb-0 p-0" for="password-Id">Password</label>
							<input class="form-control form-control-sm" id="password-Id" name="password" type="password" autocomplete="off">
						</div>
						<button class="btn btn-primary btn-sm float-right">Login</button>
					</form>				
				</div>			
			</div>		
		</div>
		<div class="col col-md"></div>
	</div>

</div>


<?php
include('footer.php');
}
