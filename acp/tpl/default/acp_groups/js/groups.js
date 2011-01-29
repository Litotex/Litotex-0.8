
var iLastGroupTabIndex = 0;

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
			$('#acp_groups_menue').tabs( "load", iLastGroupTabIndex );
		}
	 });
	 return false;
}

function accessGroup(sTitle, iGroupId){
	
	// Remove last Group Edit Tab
	if(iLastGroupTabIndex > 0){
		$('#acp_groups_menue').tabs( "remove", iLastGroupTabIndex );
	}

	$('#acp_groups_menue').tabs("add","index.php?package=acp_permissions&associateType=2&associateID="+iGroupId, sTitle);

	var iIndex = $('#acp_groups_menue').tabs( "length" );
	iIndex = iIndex - 1;


	$('#acp_groups_menue').tabs( "select", iIndex );

	iLastGroupTabIndex = iIndex;

}

// Add New Tab vor Edit Group
function editGroup(sTitle, iGroupId){

	// Remove last Group Edit Tab
	if(iLastGroupTabIndex > 0){
		$('#acp_groups_menue').tabs( "remove", iLastGroupTabIndex );
	}

	$('#acp_groups_menue').tabs("add","index.php?package=acp_groups&action=edit&id="+iGroupId, sTitle);
	
	var iIndex = $('#acp_groups_menue').tabs( "length" );
	iIndex = iIndex - 1;
	
	$('#acp_groups_menue').tabs( "select", iIndex );

	iLastGroupTabIndex = iIndex;
}

// Delete Group if Confirmed
function delGroup(iGroupId){

	var sParam = 'id='+iGroupId;

	if(confirm('Sind Sie sicher?')){
		$.ajax({
			type: "POST",
			url: "index.php?package=acp_groups&action=del",
			data: sParam,
			dataType: 'json',
			success: function(aMsg){
				$('#acp_groups_menue').tabs( "load", 0 );
			}
		 });
	}

}

// Save Groupdata
function saveGroup(oButton, iGroupId){
	
	if(!oButton || !oButton.parentNode){
		return false;
	}

	var oForm = oButton.parentNode;
	var sParam = "";
	
	if(oForm){
		sParam = $(oForm).serialize();
	}
	
	var sGroups = encodeURIComponent($('#groups_userlist_'+iGroupId).val()) ;

	sParam += '&users='+sGroups;

	$.ajax({
		type: "POST",
		url: "index.php?package=acp_groups&action=save",
		data: sParam,
		dataType: 'json',
		success: function(aMsg){
			groupRequestCallback(aMsg);
		}
	 });
	
	return false;
	
}

function groupRequestCallback(aMsg){
		
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
				resetGroupFields();
				break;
		}
	}

}

// Reset Fields for new Inputs
function resetGroupFields(){
	
	$.each($('#saveGroup :input'), function(key, oElement){
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




