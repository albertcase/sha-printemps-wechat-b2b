;(function($){
    $(function(){
		$(".ph_1").on("click",function(){
			$(".ph_1_text").fadeIn(1500);
			 
		});
		$(".ph_1_text").on("click",function(event){
			$(".ph_1_text").fadeOut(1500);
			 event.stopPropagation();
		});



 	})

})(jQuery)
	