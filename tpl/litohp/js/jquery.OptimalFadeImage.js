(function($) {
$.fn.OptimalFadeImage=function(option)
{
	var defaults = {
				array_image		 : new Array(),
				width 		 	 : $(this)[0].style.width,
				height			 : $(this)[0].style.height,
				fade_intervall	 : 'medium',
				fade_step        : 'medium',
				pause_change	 : 'medium'
			};		
	$(this)[0].VariabiliObject = $.extend(defaults,option);	
	 switch($(this)[0].VariabiliObject.pause_change)
    {
        case 'slow' :
                        $(this)[0].VariabiliObject.pause_change = 5000 ;
                        break;
        case 'medium' : 
                        $(this)[0].VariabiliObject.pause_change = 2000 ;
                        break;
        case 'fast' : 
                        $(this)[0].VariabiliObject.pause_change = 1000 ;
                        break;
        default:
                        break;                    
    }
    switch($(this)[0].VariabiliObject.fade_intervall)
    {
        case 'slow' :
                        $(this)[0].VariabiliObject.fade_intervall = 100 ;
                        break;
        case 'medium' : 
                        $(this)[0].VariabiliObject.fade_intervall = 80 ;
                        break;
        case 'fast' : 
                        $(this)[0].VariabiliObject.fade_intervall = 30 ;
                        break;
        default:
                        break;                     
    }
    switch($(this)[0].VariabiliObject.fade_step)
    {
        case 'slow' :
                        $(this)[0].VariabiliObject.fade_step = 1 ;
                        break;
        case 'medium' : 
                        $(this)[0].VariabiliObject.fade_step = 5 ;
                        break;
        case 'fast' : 
                        $(this)[0].VariabiliObject.fade_step = 10 ;
                        break;
        default:
                        break;                    
    }
	$(this)[0].currentOpacity = new Array();	
	
	$(this)[0].currentOpacity[0]=99;
	for(i=1;i<$(this)[0].VariabiliObject.array_image.length;i++)$(this)[0].currentOpacity[i]=0;
	$(this).append("<div id=\""+this[0].id+"_div\"></div>");
	$("#"+this[0].id+"_div").attr("style","width:" + $(this)[0].VariabiliObject.width+"px; height: "+ $(this)[0].VariabiliObject.height+"px");
	$("#"+this[0].id+"_div").append("<img name=\""+this[0].id+"_img_uno\" id=\""+this[0].id+"_img_uno\" style='position:absolute; z-index: 5'  src=\""+$(this)[0].VariabiliObject.array_image[0]+"\"  />");
	$("#"+this[0].id+"_div").append("<img name=\""+this[0].id+"_img_due\" id=\""+this[0].id+"_img_due\" style='position:absolute; z-index: 4' src=\""+$(this)[0].VariabiliObject.array_image[1]+"\"  />");
	$("#"+this[0].id+"_img_uno").attr("width",parseInt($(this)[0].VariabiliObject.width));
	$("#"+this[0].id+"_img_due").attr("width",parseInt($(this)[0].VariabiliObject.width));
	if ($(this)[0].VariabiliObject.array_image.length <= 1 )
	{
		return; 	
	}
	setTimeout('$(this).OptimalFadeImage.Fade({ id: \''+this[0].id+'\', elemento_attuale : 0, elemento_sucessivo : 1 , cambio : 0}) ' , $(this)[0].VariabiliObject.pause_change);
}
$.fn.OptimalFadeImage.Fade= function(oggetto){	
if (oggetto.cambio == 0)
{
    $("#"+oggetto.id)[0].img_uno=$('#'+oggetto.id+'_img_uno');
    $("#"+oggetto.id)[0].img_due=$('#'+oggetto.id+'_img_due');
}
else
{
    $("#"+oggetto.id)[0].img_uno=$('#'+oggetto.id+'_img_due');
    $("#"+oggetto.id)[0].img_due=$('#'+oggetto.id+'_img_uno');
}
$("#"+oggetto.id)[0].i = setInterval(function() {
           if ($("#"+oggetto.id)[0].currentOpacity[oggetto.elemento_attuale] <= 0) 
            {
                clearInterval($("#"+oggetto.id)[0].i);
                $.fn.OptimalFadeImage.CambioImmagine({ id: oggetto.id, array_image : $("#"+oggetto.id)[0].VariabiliObject.array_image, attuale : oggetto.elemento_sucessivo, cambio : oggetto.cambio});
				return;
            }
			else
			{
                $("#"+oggetto.id)[0].currentOpacity[oggetto.elemento_attuale]-=$("#"+oggetto.id)[0].VariabiliObject.fade_step;
                $("#"+oggetto.id)[0].currentOpacity[oggetto.elemento_sucessivo] += $("#"+oggetto.id)[0].VariabiliObject.fade_step;
                if(document.all) {
		            $("#"+oggetto.id)[0].img_uno[0].style.filter = "alpha(opacity=" +  $("#"+oggetto.id)[0].currentOpacity[oggetto.elemento_attuale] + ")";
		            $("#"+oggetto.id)[0].img_due[0].style.filter = "alpha(opacity=" + $("#"+oggetto.id)[0].currentOpacity[oggetto.elemento_sucessivo] + ")";
	            } else {
		            $("#"+oggetto.id)[0].img_uno[0].style.MozOpacity = $("#"+oggetto.id)[0].currentOpacity[oggetto.elemento_attuale]/100;
		            $("#"+oggetto.id)[0].img_due[0].style.MozOpacity =$("#"+oggetto.id)[0].currentOpacity[oggetto.elemento_sucessivo]/100;
	            }
			}
            },   $("#"+oggetto.id)[0].VariabiliObject.fade_intervall )

}
$.fn.OptimalFadeImage.CambioImmagine= function(oggetto){
   if (parseInt(parseInt(oggetto.attuale) + parseInt(1)) == parseInt(oggetto.array_image.length))
   {
    $("#"+oggetto.id)[0].prossima_immagine=0;
    $("#"+oggetto.id)[0].attuale=parseInt(oggetto.array_image.length)-parseInt(1);
   }
   else
   {
   $("#"+oggetto.id)[0].prossima_immagine = parseInt(parseInt(oggetto.attuale) + parseInt(1));
   $("#"+oggetto.id)[0].attuale = parseInt(parseInt(oggetto.attuale) );
   } 	 
    if (oggetto.cambio==0)
    {
        $('#'+oggetto.id+'_img_uno').attr("src",oggetto.array_image[$("#"+oggetto.id)[0].prossima_immagine]);
		setTimeout('$("#'+oggetto.id+'").OptimalFadeImage.Fade({ id: \''+oggetto.id+'\',elemento_attuale : '+$("#"+oggetto.id)[0].attuale+',elemento_sucessivo : '+$("#"+oggetto.id)[0].prossima_immagine +', cambio : 1}) ',$("#"+oggetto.id)[0].VariabiliObject.pause_change);
		return;			
    }
    else
    {
        $('#'+oggetto.id+'_img_due').attr("src",oggetto.array_image[$("#"+oggetto.id)[0].prossima_immagine]); 
		setTimeout('$("#'+oggetto.id+'").OptimalFadeImage.Fade({ id: \''+oggetto.id+'\',elemento_attuale : '+$("#"+oggetto.id)[0].attuale+',elemento_sucessivo : '+$("#"+oggetto.id)[0].prossima_immagine +', cambio : 0}) ',$("#"+oggetto.id)[0].VariabiliObject.pause_change);
		return;
    }
}
	})(jQuery); 