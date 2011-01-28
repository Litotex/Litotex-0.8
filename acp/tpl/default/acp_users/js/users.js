
var iLastUserTabIndex = 0;

function savePermissions(oButton){

	if(!oButton || !oButton.parentNode){
		return false;
	}

	var oForm = oButton.parentNode;
	var sParam = "";

	if(oForm){
		sParam = $(oForm).serialize();
	}

	$.ajax({
		type: "POST",
		url: 'index.php?package=acp_permissions&action=save',
		data: sParam,
		dataType: 'json',
		success: function(aMsg){
			$('#acp_users_menue').tabs( "load", iLastUserTabIndex );
		}
	 });
	 return false;
}

function accessUser(sTitle, iUserId){
	
	// Remove last User Edit Tab
	if(iLastUserTabIndex > 0){
		$('#acp_users_menue').tabs( "remove", iLastUserTabIndex );
	}

	$('#acp_users_menue').tabs("add","index.php?package=acp_permissions&associateType=1&associateID="+iUserId, sTitle);

	var iIndex = $('#acp_users_menue').tabs( "length" );
	iIndex = iIndex - 1;


	$('#acp_users_menue').tabs( "select", iIndex );

	iLastUserTabIndex = iIndex;

}

// Add New Tab vor Edit User
function editUser(sTitle, iUserId){

	// Remove last User Edit Tab
	if(iLastUserTabIndex > 0){
		$('#acp_users_menue').tabs( "remove", iLastUserTabIndex );
	}

	$('#acp_users_menue').tabs("add","index.php?package=acp_users&action=edit&id="+iUserId, sTitle);
	
	var iIndex = $('#acp_users_menue').tabs( "length" );
	iIndex = iIndex - 1;
	
	$('#acp_users_menue').tabs( "select", iIndex );

	iLastUserTabIndex = iIndex;
}

// Delete User if Confirmed
function delUser(iUserId){

	var sParam = 'id='+iUserId;

	if(confirm('Sind Sie sicher?')){
		$.ajax({
			type: "POST",
			url: "index.php?package=acp_users&action=del",
			data: sParam,
			dataType: 'json',
			success: function(aMsg){
				$('#acp_users_menue').tabs( "load", 0 );
			}
		 });
	}

}

function banUser(iUserId){

	var sParam = 'id='+iUserId;

	$.ajax({
		type: "POST",
		url: "index.php?package=acp_users&action=ban",
		data: sParam,
		dataType: 'json',
		success: function(aMsg){
			$('#acp_users_menue').tabs( "load", 0 );
		}
	 });
	 
}

function unbanUser(iUserId){

	var sParam = 'id='+iUserId;

	$.ajax({
		type: "POST",
		url: "index.php?package=acp_users&action=unban",
		data: sParam,
		dataType: 'json',
		success: function(aMsg){
			$('#acp_users_menue').tabs( "load", 0 );
		}
	 });

}

// Save Userdata
function saveUser(oButton, iUserId){
	
	if(!oButton || !oButton.parentNode){
		return false;
	}

	var oForm = oButton.parentNode;
	var sParam = "";
	
	if(oForm){
		sParam = $(oForm).serialize();
	}
	var sGroups = $('#user_groups_'+iUserId).sortable( "serialize");

	sParam += '&'+sGroups;

	$.ajax({
		type: "POST",
		url: "index.php?package=acp_users&action=save",
		data: sParam,
		dataType: 'json',
		success: function(aMsg){
			userRequestCallback(aMsg);
		}
	 });
	
	return false;
	
}

function userRequestCallback(aMsg){
		
	if(aMsg.errors && aMsg.errors.length > 0){
		
		var sError = '';
		$.each(aMsg.errors, function(key, sMsg){
			sError += sMsg+'<br/>';
		});

		$('#errorDiv').html(sError);
		$('#errorDiv').show();
		$('#successDiv').hide();
		
	} else if(aMsg.message) {
		
		$('#successDiv').html(aMsg.message);
		$('#errorDiv').hide();
		$('#successDiv').show();
		
	}
	
	if(aMsg.task){
		switch (aMsg.task) {
			case 'resetFields':
				resetUserFields();
				break;
		}
	}

}

// Reset Fields for new Inputs
function resetUserFields(){
	
	$.each($('#saveUser :input'), function(key, oElement){
		if(
			oElement.type != 'checkbox' &&
			oElement.type != 'hidden'
		){
			oElement.value = '';
		} else if(oElement.type != 'checkbox'){
			oElement.checked = false;
		}
	});
	
}

function addUserField(sType){

	if(sType == undefined){
		var sType = 'input';
	}

	var sName = $('#new_field_key').val();
	
	var iOptional = 0;
	var iDisplay = 0;
	var iEditable = 0;

	if($('#new_field_optional').attr('checked')){
		iOptional = 1;
	}
	
	if($('#new_field_display').attr('checked')){
		iDisplay = 1;
	}

	if($('#new_field_editable').attr('checked')){
		iEditable = 1;
	}

	var sParam = '&type='+sType+'&name='+sName+'&optional='+iOptional+'&display='+iDisplay+'&editable='+iEditable

	$.ajax({
		type: "POST",
		url: "index.php?package=acp_users&action=addField",
		data: sParam,
		dataType: 'json',
		success: function(aMsg){
			$('#acp_users_menue').tabs( "load", 2 );
		}
	 });

}

function deleteUserField(iFieldId, sQuestion){
	var sParam = '&field_id='+iFieldId;
	if(confirm(sQuestion)){
		$.ajax({
			type: "POST",
			url: "index.php?package=acp_users&action=delField",
			data: sParam,
			dataType: 'json',
			success: function(aMsg){
				$('#acp_users_menue').tabs( "load", 2 );
			}
		 });
	}
}


function startGroupAssignments(iUserId){

	$( "ul.droptrue_"+iUserId ).sortable({
		connectWith: "ul"
	});

	$( "#user_groups_"+iUserId+", #all_groups_"+iUserId ).disableSelection();

}




