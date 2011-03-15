{include file=$HEADER}




{literal}
<script type="text/javascript">
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;

jQuery(document).ready(function(){

jQuery("#list3").jqGrid({ 
url:'index.php?package=acp_log_viewer&action=database', 
datatype: "json", 
colNames:['ID','Datum', 'Eintrag'], 
colModel:[ 	{name:'ID',index:'ID', width:60, sorttype:"int"}, 
			{name:'logdate',index:'logdate', width:120, sorttype:"date"}, 
			{name:'message',index:'message', width:780}
			], 
			height:400,
			rowNum:20, 
			rowList:[20,40,60], 
			pager: '#pager3', 
			sortname: 'ID', 
			viewrecords: true, 
			sortorder: "desc", 
			loadonce: true, 
			caption: "SQL ERROR Logging" }); 
});
</script>

{/literal}


<table id="list3"></table> 
<div id="pager3"></div> 

{include file=$FOOTER}
