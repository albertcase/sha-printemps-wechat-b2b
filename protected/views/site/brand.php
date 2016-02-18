<div class="sortTips"></div>
<div class="page sort">
	<div class="sortTheme">
		<div class="con">
			<h2></h2>
			<p>
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/vstyle/imgs/sortimg.jpg" width="100%" />	
			</p>
		</div>
	</div>
	<div class="sortList">
		
		
		<!-- <div class="sortCategory">
			<h3>D</h3>
			<ul>
				<li>
					<div class="con">
						<h4>DAVID YURMAN</h4>
						<p>春天百货女士时尚馆，一层</p>
					</div>
				</li>
				<li>
					<div class="con">
						<h4>DE BEERSAGNELLE</h4>
						<p>春天百货女士时尚馆，一层</p>
					</div>
				</li>
				<li>
					<div class="con">
						<h4>DINH VAN</h4>
						<p>春天百货女士时尚馆，一层</p>
					</div>
				</li>
				<li>
					<div class="con">
						<h4>DIOR JOAILLERIE</h4>
						<p>春天百货女士时尚馆，一层</p>
					</div>
				</li>
			</ul>
		</div> -->

	</div>
</div>


<script type="text/javascript">

	function GetQueryString(name){
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]); return null;
	}

	var curBrandNum;

	GetQueryString("b") == null ?  curBrandNum = 1 : curBrandNum = GetQueryString("b");

	if(curBrandNum == 1){
		$(".sortTheme .con h2").html("PRINTEMPS HAUSSMANN 奥斯曼旗舰店");
	}else if(curBrandNum == 2){
		$(".sortTheme .con h2").html("PRINTEMPS DU LOUVRE 卢浮春天百货");
	}




	var sortArr = [], topv = {},curpos = "";
	$.ajax({
        type: "GET",
        url: "/api/brand?store=" + curBrandNum,
        dataType:"json"
    }).done(function(data){
           //console.log(data);
           var sortHtml = $.map(data, function(k, v){
           		var sortContentHtml = $.map(k ,function(ck, cv){
           			return '<li><div class="con"><h4>'+ck.brand+'</h4><p>'+ck.description+'</p></div></li>'
           		}).join("");
           		return '<div class="sortCategory"><h3>'+v+'</h3><ul class="sort-'+v+'">'+sortContentHtml+'</ul></div>';
           }).join("");

           $(".sortList").html(sortHtml);
           $(".sortCategory").eq(0).find("h3").addClass("hover");


           $(".sortCategory").each(function(){
           		topv[$(this).find("h3").html()] = parseInt($(this).offset().top) //- parseInt($(this).find("h3").innerHeight())
           		//topv.push()
           })

           

           $(".sortCategory h3").click(function(){
           	   topv = {};
           	   var _this = $(this);
           	   if($(this).hasClass("hover")){
           	   	   $(".sortCategory h3").removeClass("hover");
			       $(".sortCategory ul").slideUp(100,function(){
			       		$(".sortCategory").each(function(){
			           		topv[$(this).find("h3").html()] = parseInt($(this).offset().top) //- parseInt($(this).find("h3").innerHeight())
			            })

			            $('html, body').stop().animate({scrollTop:_this.offset().top}, 'fast')
			       });

			       
           	   }else{
           	   	   $(".sortCategory h3").removeClass("hover");
			       $(".sortCategory ul").slideUp(60,function(){
			       		_this.siblings("ul").slideDown(100,function(){
				    	    $(".sortCategory").each(function(){
				           		topv[$(this).find("h3").html()] = parseInt($(this).offset().top) //- parseInt($(this).find("h3").innerHeight())
				            })

				            $('html, body').stop().animate({scrollTop:_this.offset().top}, '600')
				        });
			       });
			       
			       $(this).addClass("hover");   
           	   }


		   })

           $(".sortTips").click(function(){
           		$(".sortCategory h3").removeClass("hover");
		        $(".sortCategory ul").slideUp(200,function(){
		       		$(".sortCategory").each(function(){
		           		topv[$(this).find("h3").html()] = parseInt($(this).offset().top) //- parseInt($(this).find("h3").innerHeight())
		            })
		        });
           })


		   $(window).scroll(function(){	  
			   var scrolltop;
		       if($("body")[0].scrollTop){ 
		       	scrolltop = $("body")[0].scrollTop; 
		       } else { 
		       	scrolltop = document.documentElement.scrollTop; 
		       }
			   
			   curpos = $.map(topv,function(v,k){
			   		if(scrolltop >= v) return k;
			   }).join("");


			   curpos.charAt(curpos.length - 1) === "s" ? curpos = "others" : curpos = curpos.charAt(curpos.length - 1);
			   curpos == "" ? curpos : curpos = "<p>"+curpos+"</p>";
			   
			   $(".sortTips").html(curpos);


			})

    }).fail(function() {
        console.log("请求接口失败！");
    });


    



</script>




