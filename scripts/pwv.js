function advancedCopyTo()
{
	copyHolder = document.getElementById('copyHolder');
	copyHolder.value=event.target.innerHTML;	
	copyHolder.style.display='block';
	//copyHolder.focus();
	copyHolder.select();
	try{
		document.execCommand('copy');
	}catch(e){
		console.log(e);
	}
	copyHolder.style.display='none';

	return false;
}
function copyto(id)
{
	document.getElementById(id).focus();
	document.getElementById(id).select();
	try{
		document.execCommand('copy');
	}catch(e){
		console.log(e);
	}
	return false;
}
function Filter(filter,column) {
	filterText = filter.value.toUpperCase();
	$( ".pwvData div.col" ).each(
		function (element) {
			if( $( this ).data('fieldname')==column){
				if ($( this ).text().toUpperCase().indexOf(filterText) >= 0 || filterText==''){
					$( this ).parent().css( "display", "flex");			
				}else{
					$( this ).parent().css( "display", "none");							
				}
			}
		}
	);
	$( this ).parent().dropdown( 'hide' );
}

function getCompany() {
	
}

$( '#accountInfo' ).on( 'show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var accountid = button.data('accountid');
	$( '#editAccountButton-Id' ).data('accountid',accountid);
	$( '#showPasswordHistory-Id' ).data('accountid',accountid);
	$( '#deleteAccountId-Id' ).val(accountid);	
	$.post('', {action: "getPwvAccountJSON",accountid: accountid }).done(function (data) {
		account = JSON.parse(data);
		for (x in account) {
			$( '#accountInfo-'+x+'-Id' ).html(account[x]);
		}
	});
});

$( '#passwordHistoryModal' ).on( 'show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var accountid = button.data('accountid');
	$.post('', {action: "getPwvPasswordHistoryJSON",accountid: accountid }).done(function (data) {
		passwords = JSON.parse(data);
		console.log(passwords);
		$( '#passwordHistoryList-Id' ).empty();
		$( '#passwordHistoryList-Id' ).append('<div class="row"><div class="col col-4"><strong>Password</strong></div><div class="col col-3"><strong>Created By</strong></div><div class="col col-5"><strong>Created Date</strong></div></div>');
		passwords.forEach( function(password){
			$( '#passwordHistoryList-Id' ).append('<div class="row"><div class="col col-4 cursor-copy text-truncate" id="passwordId-'+password.passwordId+'" onclick="advancedCopyTo(\'passwordId-'+password.passwordId+'\');">'+password.password+'</div><div class="col col-3">'+password.passwordCreatedBy+'</div><div class="col col-5">'+password.passwordCreated+'</div></div>');
		});
	});
});

$( '#addEditAccount' ).on( 'show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var accountid = button.data('accountid');
	var action = button.data('action');
	if (action=='addAccount') {
		
	}else{
		$.post('', {action: "getPwvAccountJSON",accountid: accountid }).done(function (data) {
			account = JSON.parse(data);
                        console.log(account);
			$( '#addEditActionId' ).val('updateAccount');
			$( '#accountId-Id' ).val(account.accountId);
			$( '#url-Id' ).val(account.url);
			$( '#system-Id' ).val(account.system);
			$( '#accountName-Id' ).val(account.accountName);
			$( '#password-Id' ).val(account.password);
			$( '#accountNotes-Id' ).val(account.accountNotes);
                        $( '#editaccesscontrol' ).html('');
                        acls='';
                        account.acls.forEach(function(acl) {
                            acls += '<div class="form-check">';
                            acls += '<input class="form-check-input" type="checkbox" name="acl[]" value="' + acl + '" id="'+ acl +'" checked>';
                            acls += '<label class="form-check-label" for="'+ acl +'">'+ acl +'</label>';
                            acls += '</div>';
                        });
                        $( '#editaccesscontrol' ).html(acls);
		});
	}
});
