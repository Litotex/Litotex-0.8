
var iLastUserTabIndex = 0;

// Delete News if Confirmed
function delNews(NewsId){

	var sParam = 'id='+NewsId;

	if(confirm('Sind Sie sicher?')){
		$.ajax({
			type: "POST",
			url: "index.php?package=acp_news&action=delete",
			data: sParam,
			dataType: 'json',
			success: function(aMsg){
				$('#acp_news_menue').tabs( "load", 0 );
			}
		 });
	}

}
// SetNews active
function set_aktive(NewsId){

	var sParam = 'id='+NewsId;

	if(confirm('Sind Sie sicher?')){
		$.ajax({
			type: "POST",
			url: "index.php?package=acp_news&action=activate",
			data: sParam,
			dataType: 'json',
			success: function(aMsg){
				$('#acp_news_menue').tabs( "load", 0 );
			}
		 });
	}

}

// SetNews deactive
function set_deaktive(NewsId){
	var sParam = 'id='+NewsId;

	if(confirm('Sind Sie sicher?')){
		$.ajax({
			type: "POST",
			url: "index.php?package=acp_news&action=deactivate",
			data: sParam,
			dataType: 'json',
			success: function(aMsg){
				$('#acp_news_menue').tabs( "load", 0 );
			}
		 });
	}
}

// allow comments
function set_comments(NewsId){
	var sParam = 'id='+NewsId;

	if(confirm('Sind Sie sicher?')){
		$.ajax({
			type: "POST",
			url: "index.php?package=acp_news&action=allow_comments",
			data: sParam,
			dataType: 'json',
			success: function(aMsg){
				$('#acp_news_menue').tabs( "load", 0 );
			}
		 });
	}
}
//forbid comments
function set_nocomments(NewsId){
	var sParam = 'id='+NewsId;

	if(confirm('Sind Sie sicher?')){
		$.ajax({
			type: "POST",
			url: "index.php?package=acp_news&action=forbid_comments",
			data: sParam,
			dataType: 'json',
			success: function(aMsg){
				$('#acp_news_menue').tabs( "load", 0 );
			}
		 });
	}
}


function editNews(sTitle,iNewsId){

	// Remove last User Edit Tab
	if(iLastUserTabIndex > 0){
		$('#acp_news_menue').tabs( "remove", iLastUserTabIndex );
	}

	$('#acp_news_menue').tabs("add","index.php?package=acp_news&action=edit&id="+iNewsId, sTitle);
	
	var iIndex = $('#acp_news_menue').tabs( "length" );
	iIndex = iIndex - 1;
	
	$('#acp_news_menue').tabs( "select", iIndex );

	iLastUserTabIndex = iIndex;
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






