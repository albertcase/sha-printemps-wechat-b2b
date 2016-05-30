<div class="sortTips"></div>
<div class="page sort">
	<div class="sortTheme">
		<div class="con">
			<h2></h2>
			<p>
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/vstyle/imgs/sortimg.jpg" class="categorieImg" width="100%" />	
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

	var curBrandNum, categorieNum, categorieVal;

	GetQueryString("b") == null ?  curBrandNum = "" : curBrandNum = GetQueryString("b");
	GetQueryString("categorie") == null ?  categorieNum = "" : categorieNum = GetQueryString("categorie");

	/*if(curBrandNum == 1){
		$(".sortTheme .con h2").html("PRINTEMPS HAUSSMANN 奥斯曼旗舰店");
	}else if(curBrandNum == 2){
		$(".sortTheme .con h2").html("PRINTEMPS DU LOUVRE 卢浮春天百货");
	}*/


    switch(categorieNum) {
                case '1':
    				categorieVal = 'ACCESSORIES & JEWELLERY 时尚配饰与奢华精品';
    				break;

    			case '2':
    			    categorieVal = 'BEAUTY 美容护肤';
    				break;

    			case '3':
    				categorieVal = 'WOMEN 女士';
    				break;

    			case '4':
    				categorieVal = 'MEN 男士';
    				break;

    			case '5':
    				categorieVal = 'CHILDREN & HOME 儿童家居';
    				break;

    			case '6':
    				categorieVal = 'ACCESSORIES 时尚与奢华配饰';
    				break;

    			case '7':
    				categorieVal = 'BEAUTY 美容护肤';
    				break;

    			case '8':
    				categorieVal = 'WATCHES & JEWELLERY 高级珠宝与腕表';
    				break;

    			default :
    			    if(curBrandNum != 2){
    			        categorieVal = 'PRINTEMPS HAUSSMANN 奥斯曼旗舰店';
    			    }else{
    			        categorieVal = 'PRINTEMPS DU LOUVRE 卢浮春天百货';
    			    }
    }


    $(".sortTheme .con h2").html(categorieVal);

    if(categorieNum >= 1 && categorieNum <=8){
    	$(".categorieImg").attr("src", "/vstyle/imgs/categorieImg/c"+categorieNum+".jpg");
    }
    


//    function arabic_Chinese_change_fun(v){
//        var resultArr = [];
//        v = String(v);
//        for (var i=0; i < v.length; i++){
//            resultArr.push( '零一二三四五六七八九'.charAt(v.charAt(i)));
//        }
//        return resultArr.join("");
//    }

    function arabic_Chinese_change_fun(v){
        if(v === 0){
            return "RDC"
        }else{
            return v + "er étage";
        }
    }

    //console.log(arabic_Chinese_change_fun("12345678090"));



	var sortArr = [], topv = {},curpos = "";
	$.ajax({
        type: "GET",
        url: "/api/brand?store=" + curBrandNum + "&categorie=" + categorieNum,
        dataType:"json"
    }).done(function(data){
           //console.log(data);
           var sortHtml = $.map(data, function(k, v){
           		var sortContentHtml = $.map(k ,function(ck, cv){
           			if (ck.building!='')
           				return '<li><div class="con"><h4>'+ck.brand+'</h4><p>'+ ck.building + '，' + arabic_Chinese_change_fun(Number(ck.floor)) +'层</p></div></li>'
           			else
           				return '<li><div class="con"><h4>'+ck.brand+'</h4><p>'+ arabic_Chinese_change_fun(Number(ck.floor)) +'层</p></div></li>'
           		}).join("");
           		return '<div class="sortCategory"><h3>'+v+'</h3><ul class="sort-'+v+'">'+sortContentHtml+'</ul></div>';
           }).join("");


           //sortHtml == "" ? sortHtml = "无当前分类内容!" : sortHtml = sortHtml
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




