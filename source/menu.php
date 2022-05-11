<?php
?>
<header class="position-sticky" style="top: 0; z-index: 1">
	<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
		<a class="navbar-brand">
		<img src="<?=$site->config->logoURI?>" alt="Logo" width="30px" height="30px" class="d-inline-block align-top">
			<?=$site->config->title?>	
		</a>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0"><li class="navbar-item"></li></ul>
		</div>
		<span class="d-sm-none d-md-inline "><?=$_SESSION['fullname'] ?></span>
		<div class="dropdown">
			<button class="btn pl-2 material-icons-outlined md-36 md-light md-inactive" data-toggle="dropdown" type="button">account_circle</button>
			<div class="dropdown-menu dropdown-menu-right">
                               <a class="dropdown-item" href="<?=$site->config->base?>/">Password Vault</a>
                                <?php
                                    if(in_array($site->config->globalAdminGroupDN, $_SESSION['groups'])){
                                        echo '<a class="dropdown-item" href="'.$site->config->base.'/admin">Admin</a>';
                                    }
                                    ?>
				<form method="post" action="<?=$site->config->base?>/login">
					<input type="hidden" name="action" value="logout">
					<button class="dropdown-item" type="submit">Logout</button>
				</form>
			</div>			
		</div>				
	</nav>
</header>
