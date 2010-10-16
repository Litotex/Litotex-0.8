var submitFunctions = new Array();

function registerSubmitFunction(callback, params){
	submitFunctions.push(new Array(callback, params));
	return true;
}
function callSubmitFunctions(){
	i = 0;
	for(i = 0; i < submitFunctions.length; i++){
		submitFunctions[i][0](submitFunctions[i][1]);
	}
}