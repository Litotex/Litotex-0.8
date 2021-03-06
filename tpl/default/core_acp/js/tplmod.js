
function startTplMod(){

	if($("#acp_tplmods_container")){
		$("#acp_tplmods_container").show();
	}

	startDragDropSortable();
	
}

function showTplModBox(){

	$("#acp_tplmods_elementbox").dialog({resizable:true, height: 500});

}

function startDragDropSortable(){

	$.each($(".tplmods_dropable"), function (key, oElement){

		$(oElement).sortable({
			connectWith: ".connectedDropable",
			receive: function(event, ui) {
				// ID umschreiben
				var oNewContainer = event.target;
				var oCurrentTplMod = ui.item[0];
				var aData = oNewContainer.id.split('_');
				var aIdData = oCurrentTplMod.id.split('_');
				oCurrentTplMod.id = 'tpl['+aData[3]+']_'+aIdData[1];
			},
			update: function(event, ui) {

				var sParam = ''

				$.each($(".tplmods_dropable"), function (key, oElement){
					sParam += '&'+$(oElement).sortable('serialize');
				});
				$.each($(".acp_tplmods_elementbox"), function (key, oElement){
					sParam += '&'+$(oElement).sortable('serialize');
				});

				
				$.ajax({
					url: "index.php?package=core_acp&action=tplModSave",
					type: "POST",
					data: sParam
				});
			}
		}).disableSelection();
	});

	$.each($(".acp_tplmods_elementbox"), function (key, oElement){
		$( oElement ).sortable({
			helper: 'clone',
			appendTo: 'body',
			connectWith: ".connectedDropable",
			sort: function(event, ui) {
				$("#acp_tplmods_elementbox").dialog('close');
			}
		}).disableSelection();
	});

	
}

function openNewWindow(){ 

	var sAddress = self.location.href;

	oWindow = window.open(sAddress, "TPL Mods");
	oWindow.focus();


}


