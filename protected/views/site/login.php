<div class="page login">
	
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/vstyle/imgs/login_bg.jpg" width="100%" />
	<div class="loginContainer">
		<div class="login_con">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/vstyle/imgs/logo.png" width="100%" />
			<div class="loginframe" style="display:inline-block;">
				<div class="login_tips">
					欢迎使用<br />法国春天百货导游服务账号<br />请你通过导游证编号与姓名登录<br />谢谢！
				</div>

				<div class="login_form">
					<ul>
						<li>
							<p>导游证编号：</p>
							<input type="text" name="code">
						</li>
						<li>
							<p>姓名：</p>
							<input type="text" name="name">
						</li>
					</ul>
				</div>

				<a href="javascript:checkForm();" class="submit_btn">
					<img src="<?php echo Yii::app()->request->baseUrl; ?>/vstyle/imgs/submit_btn.png" width="100%" />
				</a>
			</div>

			<div class="loginframe" id="successTips">
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/vstyle/imgs/success.png" width="100%" />
			</div>


		</div>


	</div>

</div>

<script type="text/javascript">



	function checkForm(){
		var codenum = $("input[name=code]").val();
		var name = $("input[name=name]").val();

		if(codenum == ""){
			alert("导游证编号不能为空！");
		}else if(name == ""){
			alert("姓名不能为空！");
		}else{

			$.ajax({
		        type: "POST",
		        url: "/api/check",
		        data: {
		            "cardnum": codenum,
		            "name": name
		        },
		        dataType:"json"
		    }).done(function(data){
		    	var callbackTips;
		    	if(data.code == 1){
		    		$(".loginframe").hide();
		    		$("#successTips").css({"display":"inline-block"});
		    		callbackTips = "验证成功";
		    	}else{
		    		callbackTips = "很抱歉，登录失败，请重新登录";
		    	}
		    	
		    	$(".login_tips").addClass("error").html(callbackTips);
				$("input").val("");
				setTimeout('$(".login_tips").removeClass("error").html("欢迎使用<br />法国春天百货导游服务账号<br />请你通过导游证编号与姓名登录<br />谢谢！");', 3000)
		    })
			
		}
	}
</script>