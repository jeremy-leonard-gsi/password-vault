<?php
?>
<header class="position-sticky" style="top: 0; z-index: 1">
	<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
		<a class="navbar-brand">
			<img src="images/GSI-G.png" alt="Gracon Logo" width="30" height="30" class="d-inline-block align-top">
		Gracon
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<?php
				foreach($site->modules as $module){
					switch($module->type) {
						case 'tab':
							echo "<li class=\"nav-item\">\n<a class=\"nav-link";
							if($site->request->module==$module->name) echo " active";
							echo "\" href=\"$module->name\" title=\".$module->description.\">$module->title</a></li>\n";
							break;
						case 'dropdown':
							echo "<li class=\"nav-item dropdown\">\n\t<a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"#navbarDropdown$module->name\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">$module->title</a>\n";
							echo "<div class=\"dropdown-menu\" aria-labeledby=\"navbarDropdown$module->name\" style=\"z-index: 100000; background-color: white;\">\n";
							foreach($site->{$module->name} as $item){
								echo "\t<a class=\"dropdown-item\" href=\"$item->url\" target=\"_blank\">$item->label</a>\n";
							}
							echo "</div>\n";
							echo "</li>\n";
							break;
					}
				}
				?>
			</ul>		
			<form class="form-inline" method="post" action="/customerInfo" id="customerSearchFormId">
				<input class="form-control mr-sm-2"" type="search" aria-label="Search" placeholder="Account Search" id="customerSearchId" list="accounts" name="customerName" onchange="$( '#customerSearchFormId' ).submit();">
				<datalist id="accounts">
					<?php
					foreach($tp->getAccounts() AS $account){
						echo "<option>".$account["AccountName"]."</option>\n";
					}
					?>
				</datalist>
<!--				<button class="btn btn-outline-info my-2 my-sm-0" type="submit">Search</button>-->
			</form>
			<div class="dropdown">
				<button class="btn pl-2 material-icons-outlined md-36 md-light md-inactive" data-toggle="dropdown" type="button">account_circle</button>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="/changepass" class="dropdown-item">Change Password</a>
					<div class="dropdown-divider"></div>
					<form method="post" action="/login">
						<input type="hidden" name="action" value="logout">
						<button class="dropdown-item" type="submit">Logout</button>
					</form>
				</div>			
			</div>
		</div>
	</nav>
</header>
<div></div>
