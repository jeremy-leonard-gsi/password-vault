<?php
$pwv = new Passwordvault($site->config);
switch($site->request->method){
	case "POST":
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
				$pwv->addAccount($site->request->post->customerId,
										$site->request->post->system,
										$site->request->post->accountName,
										$site->request->post->accountNotes,
										$site->request->post->accountPassword,
										$_SESSION["username"]);
				break;
			case 'updateAccount':
				$pwv->updateAccount($site->request->post->accountId,
										$site->request->post->system,
										$site->request->post->accountName,
										$site->request->post->accountNotes,
										$site->request->post->accountPassword,
										$_SESSION["username"]);
				break;
			case 'deleteAccount':
				$pwv->deleteAccount($site->request->post->accountId);
				break;
		}
				$customer = $tp->searchAccounts($site->request->post->customerName);
				$contacts = $tp->getAccountContacts($customer[0]['AccountNumber']);
				$passwords = $pwv->getAccounts($customer[0]['AccountNumber']);
				include('header.php');
				include('menu.php');
				?>
				<div class="container-fluid">
					<div class="card mt-2" id="customerInfo-Id">
						<div class="card-header"><a href="<?=$customer[0]['URL1']?>" target="_blank"><?=$customer[0]['AccountName']?></a><a href="https://tigerpaw.gracon.com/employee/AccountView.aspx?a=<?=$customer[0]['AccountNumber']?>" target="_blank" class="float-right btn btn-sm material-icons-outlined md-18 md-inactive">edit</a></div>
						<div class="card-body px-1">
							<div class="row">
								<div class="col-md-2">
									<div class="nav position-sticky flex-column nav-pills" style="top: 0;"id="v-pills-tab" role="tablist" aria-orientation="vertical">
										<a class="nav-link active" id="customerInfo" data-toggle="tab" href="#info" role="tab" aria-controls="customerInfo">Customer Info</a>	
										<a class="nav-link" id="customerContacts" data-toggle="tab" href="#contacts" role="tab" aria-controls="customerContacts">Contacts</a>	
										<a class="nav-link" id="customerPasswords" data-toggle="tab" href="#passwords" role="tab" aria-controls="customerPasswords">Passwords</a>
<!--										<a class="nav-link" id="customerDocuments" data-toggle="tab" href="#documents" role="tab" aria-controls="customerDocuments">Documentation</a>							-->
									</div>
								</div>
								<div class="col-md-10">
									<div class="tab-content" id="customerContentTab">
										<div class="tab-pane fade show active" role="tabpanel" id="info">
											<div class="row row-cols-1 row-cols-md-2">
												<div class="col mb-4">
													<div class="card">
														<div class="card-header">Address<button class="float-right btn btn-sm material-icons-outlined md-18 md-inactive">file_copy</button></div>
														<div class="card-body">
														<table>
															<tr><td colspan="3"><?=$customer[0]['Address1']?></td></tr>
															<tr><td colspan="3"><?=$customer[0]['Address2']?></td></tr>
															<tr><td><?=$customer[0]['City']?></td><td><?=$customer[0]['State']?></td><td><?=$customer[0]['PostalCode']?></td></tr>								
														</table>
														</div>					
													</div>
												</div>
												<div class="col mb-4">
													<div class="card">
														<div class="card-header">Notes</div>
														<div class="card-body">
														<p><pre><?=$customer[0]['HotNote']?></pre>
														</div>					
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" role="tabpanel" id="contacts">
											<div class="row row-cols-1 row-cols-md-2">
												<?php
												foreach($contacts as $contact){
													$phones = $tp->getContactPhone($contact["ContactNumber"]);
													$emails = $tp->getContactEmail($contact["ContactNumber"]);
													echo "<div class=\"col mb-4\">\n";
													echo "\t<div class=\"card\">\n";
													echo "\t\t<div class=\"card-header\">".$contact["ContactName"]."<a href=\"https://tigerpaw.gracon.com/employee/contactView.aspx?c=".$contact["ContactNumber"]."\" target=\"_blank\" class=\"float-right btn btn-sm material-icons-outlined md-18 md-inactive\">edit</a></div>\n";
													echo "\t\t<div class=\"card-body\">\n";
													echo "<table>\n";
													foreach($phones as $phone){
														echo "<tr><th>Phone:</th><td><a href=\"tel:".$phone['FormattedPhoneNumber']."\">".$phone['FormattedPhoneNumber']."</a></td><td>".$phone['PhoneLocation']."</td></tr>\n";						
													}
													foreach($emails as $email){
														echo "<tr><th>Email:</th><td colspan=\"2\"><a href=\"email:".$email['EmailAddress']."\">".$email['EmailAddress']."</a></td></tr>\n";													
													}
													echo "</table>\n";
													echo "\t\t</div>\n";
													echo "\t</div>\n";
													echo "</div>\n";					
												}
												?>
											</div>
										</div>
										<div class="tab-pane fade" role="tabpanel" id="passwords">
											<div class="row no-gutters border-bottom bg-white">
												<div class="col col-5 col-md-3 text-nowrap" id="searchAccountNameDropdownId">
													<strong>Account Name</strong>
													<button class="btn btn-sm pl-0 material-icons-outlined md-18" data-toggle="dropdown" title="Search Accountnames">search</button>
													<div class="dropdown-menu mx-2 px-2">
														<input id="searchAccountNameId" class="form-control bg-white" type="search" placeholder="filter" onchange="Filter(this,'accountName');">
													</div>
												</div>						
												<div class="col col-5 col-md-3 pl-1 text-nowrap" id="searchPasswordDropdownId">
													<strong>Password</strong>
													<button class="btn btn-sm pl-0 material-icons-outlined md-18" data-toggle="dropdown" title="Search Passwords">search</button>
													<div class="dropdown-menu mx-2 px-2">
														<input id="searchPasswordId" class="form-control bg-white" type="search" placeholder="filter" onchange="Filter(this,'password');">
													</div>
												</div>						
												<div class="col col-2 text-nowrap d-none d-md-block" id="searchSystemDropdownId">
													<strong>System</strong>
													<button class="btn btn-sm pl-0 material-icons-outlined md-18" data-toggle="dropdown" title="Search Systems">search</button>
													<div class="dropdown-menu mx-2 px-2">
														<input id="searchSystemId" class="form-control bg-white" type="search" placeholder="filter" onchange="Filter(this,'system');">
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
															<a class="btn btn-light material-icons-outlined md-18" title="Export to CSV" href="?export=<?=$customer[0]['AccountNumber']?>">save_alt</a>
														</div>
													</div>
											</div>
											<div class="pwvData striped striped-hover">
													<?php
														foreach($passwords as $key => $password){
															?>
															<div class="row m-0 border-bottom text-nowrap">
																<div class="col col-5 col-md-3 cursor-copy text-truncate" data-fieldName="accountName" id="accountName<?=$key?>" onclick="advancedCopyTo('accountName<?$key?>');" title="Click to copy to your clipboard."><?=$password["accountName"]?></div>
																<div class="col col-6 col-md-3">
																	<div class="input-group">
																		<input type="text" data-fieldName="password" class="form-control form-control-sm pwvPassword" value="<?=$password["password"]?>" id="passwordId-<?=$password["passwordId"]?>" readonly>
																		<div class="input-group-append">
																		<button class="btn btn-outline-secondary btn-sm material-icons-outlined md-dark md-18" type="button" onclick="copyto('passwordId-<?=$password["passwordId"]?>');" title="Copy Password to clipboard">file_copy</button>
																		</div>
																	</div>
																</div>	
																<div class="col cursor-copy col-2 text-truncate d-none d-md-block" data-fieldName="system" id="system>?$key?>" onclick="advancedCopyTo('system<?$key?>');" title="Click to copy to your clipboard."><?=$password["system"]?></div>
																<div class="col cursor-copy col-3 text-truncate d-none d-md-block" data-fieldName="accountNotes" id="accountNotes>?$key?>" onclick="advancedCopyTo('accountNotes<?$key?>');" title="Click to copy to your clipboard."><?=$password["accountNotes"]?></div>
																<div class="col col-1 pl-0 pl-md-4">
																	<button class="btn material-icons-outlined md-18 px-0 px-md-3" title="Show Details" data-accountid="<?=$password["accountId"]?>" data-toggle="modal" data-target="#accountInfo">info</button>											
																</div>
															</div>
															<?php
														}
													?>
											</div>
										</div>
										<div class="tab-pane fade" role="tabpanel" id="documents">
											Documents
										</div>
									</div>
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
								<form method="post" action="/customerInfo">
									<input type="hidden" name="action" value="deleteAccount">
									<input type="hidden" name="customerName" value="<?=$site->request->post->customerName?>">
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
				<div class="modal" id="addEditAccount" tabindex="-1" role="dialog">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title">Account Info</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
			      	<form method="post" action="/customerInfo#paswords">
					      <div class="modal-body">
				      		<input id="addEditActionId" name="action" type="hidden" value="addAccount">
				      		<input id="accountId-Id" name="accountId" type="hidden">
				      		<input id="customerId" name="customerId" type="hidden" value="<?=$customer[0]['AccountNumber']?>">
				      		<input id="addEdit-customerName-Id" name="customerName" type="hidden" value="<?=$site->request->post->customerName?>">
				      		<div class="form-group row">
				      			<label class="col-sm-3 col-form-label col-form-label-sm" for="system-Id">System</label>
				      			<div class="col-sm-9">
						      		<input class="form-control" id="system-Id" name="system" type="text" autocomplete="off" required>
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
						      				<button class="input-group-text" type="button" data-toggle="dropdown">generate</button>
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
						      					<button class="btn btn-primary float-right" type="button">Generate</button>
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
		break;
	case 'GET':
		if(isset($site->request->get->export)){
			$export = $pwv->exportCSV($site->request->get->export);
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$tp->getAccount($site->request->get->export)[0]['AccountName'].'-export.csv"');
			echo $export;
			exit;	
		}
		include('header.php');
		include('menu.php');
		?>
		<div class="card">
			<div class="card-header">Customer Information</div>
			<div class="card-body">
				<p>Search for a customer in the search field to display information here.		
			</div>
		</div>	
		<?php	
		include('footer.php');
		break;
}