
var iLastUserTabIndex = 0;


function editOption(sTitle,iOptionId){

	// Remove last User Edit Tab
	if(iLastUserTabIndex > 0){
		$('#acp_users_menue').tabs( "remove", iLastUserTabIndex );
	}

	$('#acp_users_menue').tabs("add","index.php?package=acp_options&action=edit&id="+iOptionId, sTitle);
	
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





