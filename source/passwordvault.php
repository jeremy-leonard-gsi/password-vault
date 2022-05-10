<?php

if($site->request->method=="POST"){
	switch($site->request->action) {
		case "getPwvAccountJSON":
			echo json_encode($pwv->getAccountInfo($site->request->post->accountid)[0]);
			exit;
			break;
		case 'getPwvPasswordHistoryJSON':
			echo json_encode($pwv->getPasswordHistory($site->request->post->accountid));
			exit;
			break;
		case 'addAccount':
			$pwv->addAccount($site->request->post->system,
									$site->request->post->accountName,
									$site->request->post->accountNotes,
									$site->request->post->accountPassword,
									$_SESSION["username"],
									$site->request->post->url);
			break;
		case 'updateAccount':
			$pwv->updateAccount($site->request->post->accountId,
									$site->request->post->system,
									$site->request->post->accountName,
									$site->request->post->accountNotes,
									$site->request->post->accountPassword,
									$_SESSION["username"],
									$site->request->post->url);
			if(isset($site->request->apikey)) exit;
			break;
		case 'updatePassword':
			$pwv->addPassword($site->request->post->accountId,
									$site->request->post->accountPassword,
									$_SESSION["username"]);
			if(isset($site->request->apikey)) exit;
			break;
		case 'deleteAccount':
			$pwv->deleteAccount($site->request->post->accountId);
			break;
		case 'import':
			if(isset($_FILES) 
				AND is_array($_FILES) 
				AND isset($_FILES['CSVFile'])
				AND $_FILES['CSVFile']['type']=='text/csv'
				AND $_FILES['CSVFile']['error']==0
				AND $_FILES['CSVFile']['size'] > 0
			) {
				$pwv->importCSV($_FILES['CSVFile']);
			}
	}
}
if(isset($site->request->get->export)){
	$export = $pwv->exportCSV($site->request->get->export);
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="passwords-export.csv"');
	echo $export;
	exit;	
}
$site->passwords = $pwv->getAccounts();
include('header.php');
include('menu.php');
	?>
	<div class="container-fluid">
		<div class="card mt-2" id="customerInfo-Id">
			<div class="card-header"><?=$site->system[0]['companyName']??''?></div>
			<div class="card-body px-1">
				<div class="row no-gutters border-bottom bg-white">
					<div class="col col-2 text-nowrap d-none d-md-block" id="searchSystemDropdownId">
						<strong>System</strong>
						<button class="btn btn-sm pl-0 material-icons-outlined md-18" data-toggle="dropdown" title="Search Systems">search</button>
						<div class="dropdown-menu mx-2 px-2">
							<input id="searchSystemId" class="form-control bg-white" type="search" placeholder="filter" onchange="Filter(this,'system');">
						</div>
					</div>						
					<div class="col col-5 col-md-3 text-nowrap" id="searchAccountNameDropdownId">
						<strong>Account Name</strong>
						<button class="btn btn-sm pl-0 material-icons-outlined md-18" data-toggle="dropdown" title="Search Accountnames">search</button>
						<div class="dropdown-menu mx-2 px-2">
							<input id="searchAccountNameId" class="form-control bg-white" type="search" placeholder="filter" onchange="Filter(this,'accountName');">
						</div>
					</div>						
					<div class="col col-5 col-md-3 pl-1 text-nowrap" id="searchPasswordDropdownId">
						<strong>Password</strong>
						<!-- <button class="btn btn-sm pl-0 material-icons-outlined md-18" data-toggle="dropdown" title="Search Passwords">search</button> -->
						<div class="dropdown-menu mx-2 px-2">
							<input id="searchPasswordId" class="form-control bg-white" type="search" placeholder="filter" onchange="Filter(this,'password');">
						</div>
					</div>						
					<div class="col col-3 text-nowrap d-none d-md-block" id="searchAccountNotesDropdownId">
						<strong>Notes</strong>
						<button class="btn btn-sm pl-0 material-icons-outlined md-18" data-toggle="dropdown" title="Search Notes">search</button>
						<div class="dropdown-menu mx-2 px-2">
							<input id="searchAccountNotesId" class="form-control bg-white" type="search" placeholder="filter" onchange="Filter(this,'accountNotes');">
						</div>
					</div>						
						<div class="col col-2 col-md-1">
							<div class="btn-group mb-1 float-right" role="group">
								<button class="btn btn-light material-icons-outlined md-18" title="Add Account" data-action="addAccount" data-accountid="" data-toggle="modal" data-target="#addEditAccount">add_box</button>
								<a class="btn btn-light material-icons-outlined md-18" title="Export to CSV" href="?export=null" target="_blank">arrow_circle_down</a>
								<button class="btn btn-light material-icons-outlined md-18" title="Import from CSV" data-action="import" data-toggle="modal" data-target="#importFromCSV">arrow_circle_up</button>
							</div>
						</div>
				</div>
				<div class="pwvData striped striped-hover">
						<?php
							foreach($site->passwords as $key => $password){
								?>
								<div class="row m-0 border-bottom text-nowrap">
									<?php
									if(is_null($password['url']) OR $password['url']=='') {
										echo '<div class="col cursor-copy col-2 text-truncate d-none d-md-block" data-fieldName="system" id="system'.$key.'" onclick="advancedCopyTo(\'accountName<?=$key?>\');" title="Click to copy to your clipboard.">'.$password["system"].'</div>';
									}else{
										echo '<a href="'.$password['url'].'" target="_blank" class="col col-2 text-truncate d-none d-md-block" data-fieldName="system" id="system'.$key.'" title="Click to go to the URL: '.$password['url'].'">'.$password["system"].'</a>';
									}
									?>
									<div class="col col-5 col-md-3 cursor-copy text-truncate" data-fieldName="accountName" id="accountName<?=$key?>" onclick="advancedCopyTo('accountName<?$key?>');" title="Click to copy to your clipboard."><?=$password["accountName"]?></div>
									<div class="col col-6 col-md-3">
										<div class="input-group">
											<input type="text" data-fieldName="password" class="form-control form-control-sm pwvPassword" value="<?=$password["password"]?>" id="passwordId-<?=$password["passwordId"]?>" readonly>
											<div class="input-group-append">
											<button class="btn btn-outline-secondary btn-sm material-icons-outlined md-dark md-18" type="button" onclick="copyto('passwordId-<?=$password["passwordId"]?>');" title="Copy Password to clipboard">file_copy</button>
											</div>
										</div>
									</div>	
									<div class="col cursor-copy col-3 text-truncate d-none d-md-block" data-fieldName="accountNotes" id="accountNotes<?=$key?>" onclick="advancedCopyTo('accountNotes<?=$key?>');" title="Click to copy to your clipboard."><?=base64_decode($password["accountNotes"])?></div>
									<div class="col col-1 pl-0 pl-md-4">
										<button class="btn material-icons-outlined md-18 px-0 px-md-3" title="Show Details" data-accountid="<?=$password["accountId"]?>" data-toggle="modal" data-target="#accountInfo">info</button>											
									</div>
								</div>
								<?php
							}
						?>
				</div>
			</div>

			</div>
		</div>
	</div>

	<div class="modal" id="accountInfo" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Account Info</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php
						$fields = [
							'system'=>'System',
							'url'=>'URL',
							'accountName'=>'Account Name',
							'accountCreated'=>'Created Date',
							'accountCreatedBy'=>'Created By',
							'accountModified'=>'Modified Date',
							'accountModifiedBy'=>'Modified By',
							'accountNotes'=>'Notes',
							'password'=>'Password<button id="showPasswordHistory-Id" class="btn btn-small material-icons-outlined md-18" type="link" title="View Password History" data-accountid="" data-toggle="modal" data-target="#passwordHistoryModal">history</button>',
							'passwordCreated'=>'Date Password Created',
							'passwordCreatedBy'=>'Password Created By'
							];
						foreach($fields as $field => $label){
						?>
					<div class="row">
						<div class="col col-12 font-weight-bold"><?=$label?></div>								
					</div>
					<div class="row">
						<div class="col col-1"></div>
						<div class="col col-11" id="accountInfo-<?=$field?>-Id"></div>
					</div>
					<?php
					}
					?>
				</div>
				<div class="modal-footer">
					<form method="post" action="">
						<input type="hidden" name="action" value="deleteAccount">
						<input type="hidden" name="customerName" value="<?=$site->request->post->systemName??''?>">
						<input type="hidden" id="deleteAccountId-Id" name="accountId" value="">
						<button class="btn btn-danger" type="submit">Delete</button>
					</form>
					<button class="btn btn-secondary" id="editAccountButton-Id" type="button" data-action="editAccount" data-accountid="" data-toggle="modal" data-target="#addEditAccount" data-dismiss="modal">Edit Account</button>
					<button class="btn btn-primary" type="button" data-dismiss="modal">Close</button>							
				</div>
			</div>
		</div>
	</div>
	<div class="modal" id="passwordHistoryModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Password History</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				<div id="passwordHistoryList-Id" class="striped striped-hover"></div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" type="button" data-dismiss="modal">Close</button>							
				</div>
			</div>
		</div>
	</div>
	<div class="modal" id="importFromCSV" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Import From CSV</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" enctype="multipart/form-data" action="<?=$_SERVER["REQUEST_URI"] ?>">
						<div class="custom-file">
							<input type="hidden" name="action" value="import">
							<input type="file" class="custom-file-input" id="importCSVFile" name="CSVFile">
							<label class="custom-file-label" for="importCSVFile">Choose File</label>
						</div>
						<button type="submit" class="btn btn-primary float-right mt-2">Import</button>
					</form>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" type="button" data-dismiss="modal">Close</button>							
				</div>
			</div>
		</div>
	</div>
	<div class="modal" id="addEditAccount" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Account Info</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
      	<form method="post" action="">
		      <div class="modal-body">
	      		<input id="addEditActionId" name="action" type="hidden" value="addAccount">
	      		<input id="accountId-Id" name="accountId" type="hidden">
	      		<input id="customerId" name="customerId" type="hidden" value="<?=$site->system[0]['companyId']??''?>">
	      		<input id="addEdit-customerName-Id" name="customerName" type="hidden" value="<?=$site->request->post->systemName??''?>">
                        <ul class="nav nav-tabs mb-1" id="EditAccountTabId" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="editaccountinfo-tab" data-toggle="tab" href="#editaccountinfo" role="tab" aria-controls="editaccountinfo" aria-selected="true">Account Info</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="editaccesscontrol-tab" data-toggle="tab" href="#editaccesscontrol" role="tab" aria-controls="editaccesscontrol" aria-selected="false">Access Control</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="addedit-tabs">
                            <div class="tab-pane fade show active" id="editaccountinfo" role="tabpanel" aria-labeledby="editaccountinfo-tab">
	      		<div class="form-group row">
	      			<label class="col-sm-3 col-form-label col-form-label-sm" for="system-Id">System</label>
	      			<div class="col-sm-9">
			      		<input class="form-control" id="system-Id" name="system" type="text" autocomplete="off" required>
			      	</div>
	      		</div>
	      		<div class="form-group row">
	      			<label class="col-sm-3 col-form-label col-form-label-sm" for="url-Id">URL</label>
	      			<div class="col-sm-9">
			      		<input class="form-control" id="url-Id" name="url" type="url" autocomplete="off">
			      	</div>
	      		</div>
	      		<div class="form-group row">
	      			<label class="col-sm-3 col-form-label col-form-label-sm" for="accountName-Id">Account Name</label>
	      			<div class="col-sm-9">
		      			<input class="form-control" id="accountName-Id" name="accountName" type="text" autocomplete="off" required>
		      		</div>
	      		</div>
	      		<div class="form-group row">
	      			<label class="col-sm-3 col-form-label col-form-label-sm" for="password-Id">Password</label>
	      			<div class="col-sm-9">
	      				<div class="input-group">
		      				<input class="form-control" id="password-Id" name="accountPassword" type="text" autocomplete="off" required>
		      				<div class="input-group-append dropdown">
			      				<!-- <button class="input-group-text" type="button" data-toggle="dropdown">generate</button> -->
			      				<div class="dropdown-menu p-2">
			      					<div class="form-group row">
											<label class="col-form-label">Length</label>
											<div class="col">
												<input class="form-control" type="number" value="8" size="2" >						      					
											</div>
			      					</div>
			      					<div class="form-check">
											<input class="form-check-input" id="upper-Id" type="checkbox" checked>						      					
											<label for="upper-Id">Include Upper</label>
			      					</div>
			      					<!-- <button class="btn btn-primary float-right" type="button">Generate</button> -->
			      				</div>
		      				</div>
	      				</div>
		      		</div>
	      		</div>
	      		<div class="form-group row">
	      			<label class="col-sm-3 col-form-label col-form-label-sm" for="accountNotes-Id">Account Notes</label>
	      			<div class="col-sm-9">
		   	   		<textarea class="form-control" id="accountNotes-Id" name="accountNotes"></textarea>
		      		</div>
	      		</div>
                        </div>
                            <div class="tab-pane fade" id="editaccesscontrol" role="tabpanel" aria-labeledby="editaccesscontrol-tab">
                                <div id="editaccesscontrol-groups"></div>
                                <hr>
                                <p>Members of the group, <?=$site->config->globalAdminGroupDN?> can always see all passwords.</p><p>If no groups are checked on this screen only members of this admin groups will be able to see this account.</p>
                            </div>
                        </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		        <button type="submit" class="btn btn-primary">Save</button>
		      </div>
      	</form>
	    </div>
	  </div>
	</div>
	<script>
	$( "#searchAccountNameDropdownId" ).on('shown.bs.dropdown',function (){$( "#searchAccountNameId" ).focus();});	
	$( "#searchPasswordDropdownId" ).on('shown.bs.dropdown',function (){$( "#searchPasswordId" ).focus();});	
	$( "#searchSystemDropdownId" ).on('shown.bs.dropdown',function (){$( "#searchSystemId" ).focus();});	
	$( "#searchAccountNotesDropdownId" ).on('shown.bs.dropdown',function (){$( "#searchAccountNotesId" ).focus();});
	</script>
	<?php
	include('footer.php');
