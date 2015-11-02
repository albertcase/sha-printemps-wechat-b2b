<article>
<header>
	<img src="/vstyle/imgs/order_header.jpg" width="100%" />
</header>
<section class="container">
	
	<div class="personal_con">
		<div class="personal_about">
			法国春天百货奥斯曼总店設有中文导购团队为您提供量身定制形象顾问指导、订购限量版奢华精品等多种服务。预约私人导购服务, 请填写以下信息 :
		</div>
		
		<ul class="personal_form">
			<li class="fstyle-1">
				<span>称呼：</span> 
				<select name="gender">
					<option>请选择</option>
					<option>先生</option>
					<option>女士</option>
				</select>
			</li>
			<li class="fstyle-1">
				<span>姓：</span> 
				<input type="text" name="surname">
			</li>
			<li class="fstyle-1">
				<span>名：</span> 
				<input type="text" name="name">
			</li>

			<li class="fstyle-2">
				<span>希望预约日期：</span> 
				<input type="date" name="date" value="2011-01-04">
			</li>
			<li class="fstyle-2">
				<span>希望预约时间：</span> 
				<input type="time" name="hour" value="10:00">
			</li>
			<li class="fstyle-2">
				<span>希望联系方式：</span> 
				<select name="contact">
					<option>请选择</option>
					<option>电话</option>
					<option>邮箱</option>
				</select>
			</li>
			<li class="fstyle-3">
				<input type="text" name="contactVal">
			</li>
			<li class="fstyle-4">
				<p>你寻找的产品类型：</p>
				<span>
					<label><input type="checkbox" name="chosetype" value="奢侈品与配饰">奢侈品与配饰</label>
					<label><input type="checkbox" name="chosetype" value="女士时尚">女士时尚</label>
					<label><input type="checkbox" name="chosetype" value="男士时尚">男士时尚 </label>
					<label><input type="checkbox" name="chosetype" value="美妆与护肤">美妆与护肤</label>
					<label><input type="checkbox" name="chosetype" value="儿童">儿童</label>
					<label><input type="checkbox" name="chosetype" value="內衣">內衣</label>
				</span>
			</li>
			<li class="fstyle-3">
				<p>你寻找的品牌：</p>
				<span>
					<input type="text" name="brandVal">
				</span>
			</li>

		</ul>

	</div>

</section>
</article>
<footer class="personal_footer">
	<div class="con">
		<a href="javascript:orderForm();">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/vstyle/imgs/submit_btn.png" width="100%" />
		</a>
	</div>
	<img src="/vstyle/imgs/footer_bg.png" width="100%" />
</footer>

<script type="text/javascript">
	
	function formInterface(_sex, _firstname, _secondname, _ddata, _dtime, _contacttype, _contact, _product, _brandname){
		$.ajax({
	        type: "POST",
	        url: "/api/submit",
	        data: {
	            "sex": _sex,
	            "firstname": _firstname,
	            "secondname": _secondname,
	            "ddata": _ddata,
	            "dtime": _dtime,
	            "contacttype": _contacttype,
	            "contact": _contact,
	            "product": _product,
	            "brandname": _brandname
	        },
	        dataType:"json"
	    }).done(function(data){

	    	if(data.code == 1){
	    		alert("提交成功");
	    	}else{
	    		alert("很抱歉，提交失败，请刷新之后重新提交");
	    	}
	    
	    })
	}


	function orderForm(){
		var _gender = $("select[name='gender']").val();
		var _surname = $("input[name='surname']").val();
		var _name = $("input[name='name']").val();
		var _date = $("input[name='date']").val();
		var _hour = $("input[name='time']").val();
		var _contact = $("select[name='contact']").val();
		var _contactVal = $("input[name='contactVal']").val();
		var _typeArr = [];
		var _brandVal = $("input[name='brandVal']").val();

		$("input[name='chosetype']").each(function(){
			if($(this).is(':checked')){
				_typeArr.push($(this).val());
			}
		})

		_typeArr = _typeArr.join("|");

		if(_gender == "请选择"){
			alert("请选择称呼！");
		}else if(_surname == ""){
			alert("姓不能为空！");
		}else if(_name == ""){
			alert("名不能为空！");
		}else if(_contact == "请选择"){
			alert("请选择联系方式类型！");
		}else if(_contactVal == ""){
			alert("请输入您的联系方式！");
		}else if(_typeArr == ""){
			alert("请选择您寻找的产品类型！");
		}else{

			formInterface(_gender, _surname, _name, _date, _hour, _contact, _contactVal, _typeArr, _brandVal);
			//alert("提交成功！");
		}

	}

</script>















