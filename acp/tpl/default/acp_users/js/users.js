function saveUser(){
	
	var oForm = $('#saveUser');
	var sParam = "";
	
	if(oForm){
		sParam = oForm.serialize();
	}
	
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