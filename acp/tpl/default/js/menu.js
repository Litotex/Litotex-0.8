<!--
// Aufklappmenü vertikal
var untermenue=new Array();
// hier weitere Untermenüs hinzufügen
untermenue[0]="um_0";
untermenue[1]="um_1";

function show(ebene1)
{
if(document.getElementById)
{
clearout();cover();
document.getElementById(ebene1).style.display="block";
}}

function cover() 
{
for(i=0;i<untermenue.length;i++)
{
document.getElementById(untermenue[i]).style.display="none";
}}
	
function out()
{
timer=setTimeout("cover()",200);
}
	
function clearout() 
{
if(window.timer)
{
clearTimeout(timer);
}}