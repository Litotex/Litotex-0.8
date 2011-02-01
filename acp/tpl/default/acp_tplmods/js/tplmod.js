
var iTplModDragableCount = 0;

function startDragable(){
	console.debug($(".tplmods_draggable"));
	$.each($(".tplmods_draggable"), function(key, oElement){
		console.debug(oElement);
		oElement.id = 'tplmod_dragable_'+iTplModDragableCount;
		
		$(oElement).draggable();
		iTplModDragableCount++;
	});
	
}
