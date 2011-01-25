function checkboxes_uncheckAll(items){
    for (i = 0; i < items.length; i++)
	items[i].checked = false ;
}
function checkboxes_checkAll(items){
    for (i = 0; i < items.length; i++)
	items[i].checked = true ;
}
function checkbox_checkItems(items, ctrl){
    for (i = 0; i < items.length; i++)
	if(items[i].checked == false){ ctrl.attr('checked', false); return; }
    ctrl.attr('checked', true);
}
function checkbox_allItemsSelected(items){
    for (i = 0; i < items.length; i++)
	if(items[i].checked == false){ return false; }
    return true;
}
