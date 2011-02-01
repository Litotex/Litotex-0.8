var iTplModDropableCount = 0;

function startDropable(){
	
	$.each($( ".tplmods_droppable" ), function(key, oElement){
		oElement.id = 'tplmod_droppable_'+iTplModDropableCount;
		$(oElement).draggable();
		iTplModDropableCount++;
	});
	
}
