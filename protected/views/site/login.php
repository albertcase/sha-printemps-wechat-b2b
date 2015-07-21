<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/tour/bg.jpg" width="100%" class="login_bg"/>
<div id="login">
	<div class="login_con">
		<div class="login">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/tour/logo.png" width="100%" />
		</div>
		<div class="tour_con">
			<div class="tips">
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/tour/tips.png" width="100%" />
			</div>
			<div class="tour_form">
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/tour/number.png" width="100%" class="tour_label"/>
			    <input type="text" class="tour tour_no"/>
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/tour/name.png" width="100%" class="tour_label"/>
			    <input type="text" class="tour tour_name"/>	

			</div>
		</div>	
		<div class="tour_btn">
	    	<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/tour/submit_btn.png" width="100%"/>
	    </div>
	</div>	
</div>
<script type="text/javascript">
  $(".tour_btn").on("click",function(){
  	var number=$(".tour_no").val();
  	var name=$(".tour_name").val();
  	if(number==""||name==""){
  		$(".tips img").attr("src","<?php echo Yii::app()->request->baseUrl; ?>/images/spring/tour/sorry.png");
  	}
  
  })
</script>	