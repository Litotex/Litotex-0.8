var current;
var tOut;
function show(item){
    if(!document.getElementById(item))
        return;
    cover('um_'+current);clearTimeout(tOut);
    if(document.getElementById){
        document.getElementById(item).style.display="block";
    }
}

function cover(item){
    if(!document.getElementById(item))
        return;
    document.getElementById(item).style.display="none";
}
	
function out(item){
    if(!document.getElementById(item))
        return;
    tOut=setTimeout("cover('"+item+"')",200);
}