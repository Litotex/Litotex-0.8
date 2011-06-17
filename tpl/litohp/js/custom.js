$(document).ready(function(){

//only needed for the demo page
demopage_switchslider(); 			

//activate the lightbox
jQuery('a[href$=jpg], a[href$=png], a[href$=gif], a[href$=jpeg]').prettyPhoto({theme: "light_square"});


// here you can see the slide options I used in the demo page. depending on the id of the slider a different setup gets activated
$('#frontpage-slider').aviaSlider({	blockSize: {height: 80, width:80},
transition: 'slide',
display: 'all',
transitionOrder: ['diagonaltop', 'diagonalbottom','topleft', 'bottomright', 'random']
});


$('#diagonal-blocks').aviaSlider({	blockSize: {height: 80, width:80},
transition: 'slide',
display: 'diagonaltop',
switchMovement: true
});	


$('#winding-blocks').aviaSlider({	blockSize: {height: 80, width:80},
transition: 'slide',
display: 'topleft',
switchMovement: true
});								

$('#randomized-blocks').aviaSlider({	blockSize: {height: 80, width:80},
transition: 'slide',
display: 'random'
});


$('#fading_curtain').aviaSlider({	blockSize: {height: 'full', width:40},
display: 'topleft',
transition: 'fade',
betweenBlockDelay:150,
animationSpeed: 600,
switchMovement: true
});

$('#fading-top-curtain').aviaSlider({	
blockSize: {height: 40, width:'full'},
display: 'topleft',
transition: 'fade',
betweenBlockDelay:150,
animationSpeed: 600,
switchMovement: true
});	
						
$('#fullwidth-fade-slider').aviaSlider();


$('#direction-fade-slider').aviaSlider({
blockSize: {height: 3, width:'full'},
display: 'topleft',
transition: 'fade',
betweenBlockDelay:10,
animationSpeed: 600,
switchMovement: true
});

$('#droping-curtain').aviaSlider({	blockSize: {height: 'full', width:40},
display: 'topleft',
transition: 'drop',
betweenBlockDelay:80,
animationSpeed: 800,
switchMovement: true,
slideControlls: 'items',
appendControlls: '.aviaslider'
});	




					
																									
});






// the following stuff is only for the demo page to switch sliders
function demopage_switchslider()
{
	var param = window.location.href.substring(window.location.href.indexOf('?')+8);
	
	
	if(param != undefined && param != '' && window.location.href.indexOf('?') > 0)
	{
		$('.aviaslider').attr('id',param); //change the id of the slideshow depending on the url, so another slideshow gets applied
	}
}


