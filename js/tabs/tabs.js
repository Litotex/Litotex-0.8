/* 
 * Tab plugin 0.2 - jQuery plugin, 
 * don't needs of Lazy - jQuery based framework
 *
 * @requires jQuery v 1.3 or  higher
 * 
 * http://lazy.sourceforge.net
 * http://sourceforge.net/projects/lazy
 * 
 * Copyright (c) 2009 Stefano Curtoni (www.stefanocurtoni.com)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *   
 *
 * $Date: 2009-12-21
 * 
 * Tab plugin creates an unobtrusive tab widget over 
 * a HTML structure. The underlying structure has to look like the 
 * follow:
 * 
 *<div id="container">
 *  <div class="header">
 *    <ul>
 *     <li><a href="#"><span>Tab   0</span></a></li>
 *     <li class="active"><a href="#"><span>Tab 1</span></a></li>
 *     <li><a href="#"><span>Tab   2</span></a></li>
 *    </ul>  
 * </div>
 * <div>
 *	  <div class="contents">
 *	    <p> contenuto 0 &times;<b>&times; &lsaquo; &rsaquo;</b></p>
 *	  </div>
 *	  <div class="contents">
 *    <p> contenuto 1</p>
 * 	  </div>
 *	  <div class="contents">
 *	    <p> contenuto 2</p>
 *	  </div>
 *  </div>
 *</div>
 *
 *Include in the page the plugin script:
 * <script type="text/javascript" src="../tab/tab-0.2.js"></script>
 * 
 * and the stylesheet file that you need:
 *<link rel="stylesheet" href="../tab/css/tabTop.css" />
 * (for top type tab panels) 
 *<link rel="stylesheet" href="../tab/css/tabBottom.css" />
 *(for bottom type tab panels) 
 *<link rel="stylesheet" href="../tab/css/tabLeft.css" />
 *(for left type panels panels) 
 *<link rel="stylesheet" href="../tab/css/tabRight.css" />
 *(for right type tabs panels) 
 * Each anchor in the unordered list can specify a valid url address
 * to perform remote loading of contents
 * 
 * @example $('#container').tab();
 * @desc Create a tab interface with default settings.
 * 
 * @example	$("#container").tab({ 
 *			    type:"top",
 *				navigationClass:"header",
 *				contentsClass:"contents",
 *				activeClass:"active",
 *				closable:true
 *			});
 * 
 * @desc Create a customized tab interface 
 * 
 * 
 * @example	
 * var tab = $("#container").tab({ 
 *			    type:"top",
 *				navigationClass:"header",
 *				contentsClass:"contents",
 *				activeClass:"active",
 *				closable:true
 *			});
 * 
 * 	$("#addTab").click(function(){
 *		tab.addTab({href:"remote/contents.html"});
 *	});
 *	
 *	$("#disableTab").click(function(){
 *		tab.disableTab(0);
 *	});
 *	
 *	$("#enableTab").click(function(){
 *		tab.enableTab(0);
 *	});
 * 
 * @desc Create a customized tab interface with external controll for 
 * add new tab, disable or enable a tab. 
 * 
 * 
 * @option String type Define the position of tabs respect the contents
 * (top,bottom,left,right)
 * 
 * @option String navigationClass Define the class name that identify navigation div
 * 
 * @option String contentsClass Define the class name that identify contents div
 * 
 * @option String activeClass Define the class name that identify the active tab (li element)
 * 
 * @option Boolean closable Define if it's possible to remove tabs and its contents
 * 
 * @option Function onShow  A function to be invoked before show a tab and his contents 
 * 
 * @option Function onClose A function to be invoked before close a tab and his contents 
 * 
 * 
 * For addTabs :
 * 
 * @option Number position Define where insert the new tab
 * @option String href define the url of remote contents of tab()
 * @option String title Define the label of new tab
 * @option String content define the local contents
 * 
 * For disableTabs
 * 
 * @option Number index Specify the index of the tab to disable
 * 
 * For enableTabs
 * 
 * @option Number index Specify the index of the tab to enable
 * 
 * @name tab
 * @cat Lazy/Tab
 * @author Stefano Curtoni (lazyframework@gmail.com)
 * 
 */
(function($) {

$.extend($.fn,
{
	tab : function(settings){
			
		//reference to actual element/elements
		var $$ = this;
		
		var name = 'tab';
		
		var settings =  $$.settings = $.extend({
		// parametri del metodo
		type: 'top', 	//posizione delle etichette rispetto 			
		navigationClass:"lazyTabHeader", // classe associata al div di navigazione
		contentsClass:"lazyTabContents",  // classe associata ai div di contenuti 
		activeClass:"active", //classe elemento attivo
		linkClass:"lazyTabLink",
		closeClass:"lazyTabClose",
		closable:false,
		onShow:null,
		onClose:null,
		style:null
		},{
		top : {					
		    positionClass:"top"
		},		
		bottom : {					
		    positionClass:"bottom"
		},
		left: {					
		    positionClass:"left"
		},
		right: {					
		    positionClass:"right"
		}
		}[((settings||{}).type||'top')], settings||{}); 
		
		$$.settings = settings;

		$$.addClass(name + $.capitalize(settings.type));
		
		init();
		

//initial function for one or more element
function init() {
	
	var s = $$.settings
	
	// String label 
	var typeLabel = $.capitalize(s.type);
	
	var message = $("<div></div>").addClass('loading').text('loading...');
	
	$$.scrollExist = false;
	
	//div di navigazione
	$$.navigation = $("."+(s.navigationClass),$$).addClass("lazyTabHeader"+typeLabel);
	
	//div di contenuti
	$$.contents = $("."+(s.contentsClass),$$).addClass("lazyTabContents"+typeLabel);
	
	$$.activeTab = $("."+(s.activeClass),$$);
	
	$$.activeIndex = $$.navigation.find("li").index($$.activeTab); 
	
	$$.ulElement = $("ul",$$.navigation);
	
	$$.listItems = $("li",$$.navigation);
	
	$$.linkItems=$("a", $$.navigation).addClass(s.linkClass);
	
	if($$.listItems.size() != $$.contents.size()){
		alert('Error: wrong list size ');
	}

	$$.contents.eq($$.activeIndex).show();

	$$.bind("resize",function(){
		
		$$.width($$.parent().width()-$$.css('border-left-width').replace("px","")-$$.css('border-right-width').replace("px",""));
		$$.height($$.parent().height()-$$.css('border-top-width').replace("px","")-$$.css('border-bottom-width').replace("px",""));
		
		fixElementsSize();	

		scrollHandler();
	});
	
	//ie6 workaround for hover behaviour 
	$$.listItems.hover(
	function(){
		if(!$(this).hasClass('disabled'))
		{
			$(this).addClass('hover');
			return false
		}
	},function(){
		if(!$(this).hasClass('disabled')){
			$(this).removeClass('hover')
			return false
		}
	})
	
	//close tab behaviour
	if(s.closable){
		closeHandler();
	}
	
	fixElementsSize();	
	
	scrollHandler();
	
	//inversione navigation-contents				   
	(s.type=="bottom" || s.type=="right")?reverte():null;
	
	//add tab 
	$$.ulElement.bind('add',function(e,data){
		refresh();
		scrollHandler();
		//execute a costum user function
	});
	
	$$.listItems.bind("disable",function(){
		refresh();
		//execute a costum user function
	});
	
	
	//click event manager
	$$.listItems.bind("click",{s:s},function(e){
		
		if(!$(this).hasClass('disabled')){
			e.preventDefault();
			//execute custom function before show the panel
			if (typeof $$.settings.onShow == 'function') {
				$$.settings.onShow($$.navigation.find("li").index($(this)));
            }
			
			$$.activeTab.removeClass(s.activeClass);
			//update activeTab element
			$$.activeTab = $(this).addClass(s.activeClass); 
			
			//update active contents
			$$.contents.hide();
			$$.activeIndex = $$.navigation.find("li").index($$.activeTab); 
			
			var target = $("a",$$.activeTab).attr("href");
			
			if ( !/#/.test(target)) {
							$$.contents.eq($$.activeIndex).html(message).show();
							$.get(target, function(data){
		  						$$.contents.eq($$.activeIndex).html(data);
							});	
						}
			else{
				$$.contents.eq($$.activeIndex).show();			
			}
		}
	});
	
	}
	function refresh() {
		$$.contents=$(".lazyTabContents"+$.capitalize($$.settings.type));
		$$.listItems=$("li", $$.navigation);
		$$.linkItems=$("a", $$.navigation);
	}
	
	function reverte() {
		//posiziona il div di contenuti prima del div navigation
		temp=$$.contents.parent().remove();
		$$.navigation.before(temp);
	}
	
	function fixElementsSize(){
		if($$.settings.type == "top" || $$.settings.type == "bottom"){

			$$.navigation.width($$.width());
			
			$$.ulElement.height($$.listItems.outerHeight(true));
			$$.contents.width($$.width());
			$$.contents.height($$.height()-$$.navigation.outerHeight(true));
		}
		else if($$.settings.type == "right" || $$.settings.type == "left"){
		
			$$.ulElement.width($$.listItems.outerWidth(true));
			$$.contents.height($$.height());
			$$.contents.width($$.width()-$$.navigation.outerWidth(true));	
		}
	}
	
	function scrollHandler(){
	
		if($$.settings.type == "top" || $$.settings.type == "bottom"){
			
			if (getScrollWidth() > $$.width()){
				
				$$.ulElement.addClass('lazyTabScroll');
						
				if(!$$.scrollExist){
					 
					var scrollCtrlLeft = $('<div></div>').addClass('scrollLeft').height($$.ulElement.innerHeight())
					.mousedown(function(){
						$$.ulElement.animate({
					      left: "0"
					    }, "slow");
					})
					.mouseup(function(){
						$$.ulElement.stop();
					});
					
					var scrollCtrlRight = $('<div></div>').addClass('scrollRight').height($$.ulElement.innerHeight())
					.mousedown(function(){
						$$.ulElement.animate({
					      left: "-"+getScrollDeltaX()+"px"
					    }, "slow");
					})
					.mouseup(function(){
						$$.ulElement.stop();
					});
					$$.navigation.prepend(scrollCtrlLeft);
					$$.navigation.append(scrollCtrlRight);
					$$.scrollExist=true;
				}
				else
				{
					$('.scrollLeft',$$.navigation).show();
					$('.scrollRight',$$.navigation).show();
				}
			}
		
		}
		else if($$.settings.type == "right" || $$.settings.type == "left")
		{	
			
			if (getScrollHeight() > $$.height()){
					
				$$.ulElement.addClass('lazyTabScroll');
					
				if(!$$.scrollExist){	
					var scrollCtrlTop = $('<div></div>').addClass('scrollTop').width($$.ulElement.innerWidth())
					.mousedown(function(){	
						$$.ulElement.animate({
					      top: "0"
					    }, "slow");
					})
					.mouseup(function(){
						$$.ulElement.stop();
					});
					
					var scrollCtrlBottom = $('<div></div>').addClass('scrollBottom').width($$.ulElement.innerWidth())
					.mousedown(function(){
						$$.ulElement.animate({
					      top: "-"+getScrollDeltaY()+"px"
					    }, "slow");
					})
					.mouseup(function(){
						$$.ulElement.stop();
					});
					$$.navigation.prepend(scrollCtrlTop);
					$$.navigation.append(scrollCtrlBottom);
					$$.scrollExist=true
				}
				else
				{
					$('.scrollTop',$$.navigation).show();
					$('.scrollBottom',$$.navigation).show();
				}
			}
			}
	}
	
	function getScrollWidth(){
		var labelWidth=0;	
		$$.listItems.each(function(){labelWidth+=$(this).outerWidth(true)});
	    return labelWidth;
	}
	
	function getScrollHeight(){
		var labelHeight=0;
		$$.listItems.each(function(){labelHeight+=$(this).outerHeight(true)});
		return labelHeight
	}
	
	function getScrollDeltaX(){
	    return getScrollWidth()-$$.navigation.innerWidth()+36;
	}
	
	function getScrollDeltaY(){
		return getScrollHeight()-$$.navigation.innerHeight()+36;
	}
	
	function closeHandler(){
		
		var closeLink = $('<a href="#"></a>').addClass($$.settings.closeClass);
		$$.listItems.append(closeLink);
		
		//close action manager
		$('.'+$$.settings.closeClass,$$).click(function(){
				
				// find the tab index
				var index = $$.listItems.index($(this).parent());
				
				if (typeof $$.settings.onClose == 'function') {
					$$.settings.onClose(index);
	            }
				
				if(!$$.listItems.eq(index).hasClass('disabled')){
					
					//trigger remove event
					$(this).trigger('remove');
					// remove element from the lists	
					$$.listItems.eq(index).remove();
					$$.contents.eq(index).remove();
					 
					if ($$.activeIndex = index){
					 	$$.activeIndex = 0; 
					 	$$.listItems.eq($$.activeIndex).trigger('click');
					}
					
					//scroll controll
					if($$.settings.type == "top" || $$.settings.type == "bottom"){
						var labelWidth=getScrollWidth();		

						if (labelWidth < $$.width()){
							$('.scrollLeft',$$.navigation).hide();
							$('.scrollRight',$$.navigation).hide();
							$$.ulElement.removeClass('lazyTabScroll');
						}	
					}
					else
					if($$.settings.type == "right" || $$.settings.type == "left"){	
						var labelHeight=getScrollHeight();
						if (labelHeight < $$.height()){
							$('.scrollTop',$$.navigation).hide();
							$('.scrollBottom',$$.navigation).hide();
							$$.ulElement.removeClass('lazyTabScroll');
						}
					}
					//refresh lists
					refresh();
				}
				
			return false;
		});
	}
	return $$;
	},
	
	addTab : function(addSettings){
		// define global defaults, 
		$.fn.addTab.defaults = {
			position:null,
			href:"",
			title:"test title",
			content:"test contents"
			};
			
		var addSettings = $.extend($.extend({}, arguments.callee.defaults), addSettings || {});
		var position = addSettings.position == null ? position = this.listItems.index(this.listItems.filter(':last')) : position = addSettings.position;
					
		this.activeTab.clone(true)
			.removeClass(this.settings.activeClass)
			.insertAfter(this.listItems.eq(position))
			.find('.'+this.settings.linkClass)
			.attr('href',addSettings.href)
			.find('span')
			.text(addSettings.title);

		this.contents.eq(0).clone(true).html(addSettings.content).insertAfter(this.contents.eq(position));
		//fire add event
		this.ulElement.trigger('add',position);
	},
	
	disableTab : function(index){
		if (index != this.activeIndex){
			this.listItems.eq(index).addClass('disabled').trigger('disable');
		}else{
			alert('cannot disable this tab');
		}
	},
	
	enableTab : function(index){
		this.listItems.eq(index).removeClass('disabled').trigger('enable');
	}
});

$.extend($,{
	capitalize: function(string) {
		return string.charAt(0).toUpperCase() + string.substring(1).toLowerCase();
	}
});
})(jQuery);