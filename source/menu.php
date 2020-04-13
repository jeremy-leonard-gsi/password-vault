<?php
?>
<header class="position-sticky" style="top: 0; z-index: 1">
	<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
<!--		<a class="navbar-brand">
			<img src="images/GSI-G.png" alt="Gracon Logo" width="30" height="30" class="d-inline-block align-top">
		Gracon
		</a>
-->			<form class="form-inline" method="post" action="/customerInfo" id="customerSearchFormId">
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
