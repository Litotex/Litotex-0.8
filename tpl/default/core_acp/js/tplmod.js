
function startTplMod(){

	$("#acp_tplmods_container").show();

	startDropable();
	
}

function showTplModBox(){

	$("#acp_tplmods_elementbox").dialog({resizable:false});

	startDragable();

}

var iTplModDragableCount = 0;

function startDragable(){

	$.each($(".tplmods_draggable"), function (key, oElement){
		oElement.id = 'tplmod_dragable_'+iTplModDragableCount;
		$(oElement).draggable({
			helper: "clone",
			appendTo: "body",
			start: function(event, ui) {
				$("#acp_tplmods_elementbox").dialog('close');
			}

		});
		iTplModDragableCount++;
	});

}

var iTplModDropableCount = 0;

function startDropable(){
	
	$.each($(".tplmods_droppable"), function (key, oElement){
		oElement.id = 'tplmod_droppable_'+iTplModDropableCount;
		$(oElement).dropable();
		iTplModDropableCount++;
	});
	
}


