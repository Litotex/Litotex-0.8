{include file=$HEADER}




{literal}
<script type="text/javascript">

jQuery(document).ready(function(){

jQuery("#list3").jqGrid({ 
url:'index.php?package=acp_log_viewer&action=show_log', 
datatype: "json", 
colNames:['ID','User', 'Datum', 'Eintrag'], 
colModel:[ 	{name:'ID',index:'ID', width:60, sorttype:"int"}, 
			{name:'userid',index:'userid', width:90, sorttype:"int"}, 
			{name:'logdate',index:'logdate', width:120, sorttype:"date"}, 
			{name:'message',index:'message', width:690}
			], 
			height:400,
			rowNum:20, 
			rowList:[20,40,60], 
			pager: '#pager3', 
			sortname: 'ID', 
			viewrecords: true, 
			sortorder: "desc", 
			loadonce: true, 
			caption: "Logging" }); 
});
</script>

{/literal}


<table id="list3"></table> 
<div id="pager3"></div> 
<br>
<button id="button">A button element</button>

Logeintr&auml;ge k&ouml;nnen mit <strong>package::$log-&gt;debug('eintrag')</strong> geschrieben werden.


{include file=$FOOTER}
