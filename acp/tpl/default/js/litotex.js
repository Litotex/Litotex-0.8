/**
 * Button Design
 */

$(function(){
$("#button").button();
$("#radioset").buttonset();
});

/**
 * On Load start some Funktions
 */
$(document).ready(function() {
	litotexAcpTabs();
	litotexAccordion();
	
});

var iAccordionCount = 0;
function litotexAccordion(){
	$.each($(".accordion"), function (key, oElement){
		oElement.id = 'accordion_'+iAccordionCount;
		$(oElement).accordion({ autoHeight: false });
	});
}

/**
 * initalizise JQuery Tabs for all Elements with the litotex_acp_tab Class
 */
function litotexAcpTabs(){
	
	var iTabCount = 0;

	$.each($('.litotex_acp_tab'), function(key, oElement) { 
		
		// Seit ID if not exist
		if(
			oElement && 
			!oElement.id
		){
			oElement.id = "litotex_acp_tab_"+iTabCount;
			iTabCount++;
		}
		
		$('#'+oElement.id).tabs();
		
	});
	
}