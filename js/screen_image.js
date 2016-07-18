// JavaScript Document
(function(){
	$.fn.screenImage = function(options){
		settings = $.extend({
			fullScreen:true,
			hasTitle:false,
			titleHeight:90,
			bottomMargin:30
		},options || {});
		
		return $(this).each(function(){
			
			var self = this;
			var imgSRC = $(this).attr("data-imgs-url");
			var imgs = $(this).attr("data-imgs");
			var imgArr = imgs.split("|");
			var imgLength = imgArr.length;
			var currentIndex = 0;
			var currentWidth,currentHeight,popHeight,containerHeight,containerWidth;
			
			
			for( var i = 0 ; i < imgLength ; i++){
				imgArr[i] =imgSRC +imgArr[i]
			}
			
			var init =function(){
				$(self).bind("click",function(){
					$("body").append('<div id="screen"></div>')	;
					$("body").append('<div id="screen_img_pop"></div>');
					$("#screen").height($(document).height()).fadeIn();
					$("#screen_img_pop").css({top:$(window).scrollTop() + 10})
					
					var tmpSRC = imgArr[0];
					var image = new Image();
					var imgHeight;
					var imgWidth;
					var containerHeight;
					var containerWidth;
					
					$("#screen_img_pop").append('<div id="screen_img_container"><div class="loading"></div><img src="" id="screen_img" /><a href="###" id="screen_left"></a><a href="###" id="screen_right"></a><a href="###" id="screen_close"></a></div>');
					
					$("#screen_close").click(function(e){
						e.preventDefault();
						$("#screen,#screen_img_pop").fadeOut(function(){
							$("#screen,#screen_img_pop").remove();
							$(window).unbind("resize",resizeHandler)
							//$(window).unbind("scroll",scrollHandler);
						});
						currentIndex = 0;	
					});
					
					$("#screen_left").click(function(e){
						e.preventDefault();
						$("#screen_right,#screen_left").hide();
						var tmpIndex = currentIndex - 1;
						if( tmpIndex < 0 ){
							currentIndex = 0;
							return false;
						}else{
							currentIndex -=1 ;
						}
						//showArrow();
						loadImage(tmpIndex);
					});
					
					$("#screen_right").click(function(e){
						e.preventDefault();
						$("#screen_right,#screen_left").hide();
						var tmpIndex = currentIndex + 1;
						if( tmpIndex + 1 > imgLength ){
							currentIndex = imgLength - 1;
							return false;
						}else{
							currentIndex +=1 ;
						}
						//showArrow();
						loadImage(tmpIndex);
					});
					
					
					
					image.onload = function(){
						
						//showArrow();
						
						if(settings.hasTitle){
							$("#screen_title").html(imgTitle);
						}
						
						currentHeight = image.height;
						currentWidth = image.width;
						resizeHandler(showArrow);
						$("#screen_img").hide().attr("src",tmpSRC).fadeIn();
						$("#screen_img_container .loading").hide();
						
					}
					image.src = tmpSRC;
					
				});
			}
			
			var showArrow = function(){
				
				$("#screen_close").fadeIn();
				
				if( currentIndex == 0){
					$("#screen_left").hide()	
				}else{ 
					if( imgLength > 1){
						$("#screen_left").show();
					}
				}
				
				if( currentIndex == imgLength-1){
					$("#screen_right").hide()	
				}else{ 
					if( imgLength > 1){
						$("#screen_right").show();
					}
				}
				
					
			}
			
			var loadImage = function( index ){
				var tmpSRC = imgArr[index];
				var image = new Image();
				$("#screen_img").hide();
				$("#screen_img_container .loading").show();
				image.onload = function(){
					currentHeight = image.height;
					currentWidth = image.width;
					resizeHandler(showArrow);
					$("#screen_img").hide().attr("src",tmpSRC).fadeIn();
					$("#screen_img_container .loading").hide();
					
				}
				image.src = tmpSRC;
				
				$("#screen_now").text(index+1);
			}
			
			var resizeHandler = function(callback){
				if( currentHeight > $(window).height() ){
						containerHeight = $(window).height() - settings.titleHeight - settings.bottomMargin;
					}else{
						containerHeight = currentHeight - settings.titleHeight - settings.bottomMargin;
					}
					containerWidth = containerHeight * currentWidth / currentHeight;
					popHeight = containerHeight + settings.titleHeight;
					$("#screen_img_container").stop(true,true).animate({height:currentHeight,width:currentWidth},function(){
						if( callback ){
							callback();
						}	
					});
					$("#screen_img_pop").stop(true,true).animate({width:currentWidth,height:currentHeight,marginLeft:-currentWidth / 2});
			}
			
			var test = function(){
				
			}
			
			
			init();
			
			$(this).extend(this,{test:function(){
				test();
			}
			});
			
			$(this).data("screenImage",this);
			
		});
		
	}
})(jQuery);