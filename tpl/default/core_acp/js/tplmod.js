
function startTplMod(){

	$("#acp_tplmods_container").show();

	startDragDropSortable();
	
}

function showTplModBox(){

	$("#acp_tplmods_elementbox").dialog({resizable:true, height: 500});

}

function startDragDropSortable(){

	var i = 0;

	$.each($(".tplmods_dropable"), function (key, oElement){

		oElement.id = 'tpl_sortable_'+i;
		i++;

		$( oElement ).sortable({
			connectWith: ".connectedDropable",
			receive: function(event, ui) {
				var oNewContainer = event.target;
				var oCurrentTplMod = ui.item[0];
		
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


