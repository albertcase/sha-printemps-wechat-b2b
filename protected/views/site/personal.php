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
					<option value="mr">先生</option>
					<option value="miss">女士</option>
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
                <select name="date" id="date">
                    <option>请选择</option>
                </select>
			</li>
			<li class="fstyle-2">
				<span>希望预约时间：</span>
				<select name="hour" id="hour">
                    <option>请选择</option>
                </select>

			</li>
			<li class="fstyle-2">
				<span>希望联系方式：</span> 
				<select name="contact">
					<option>请选择</option>
					<option value="phone call">电话</option>
					<option value="email">邮箱</option>
				</select>
			</li>
			<li class="fstyle-3">
				<input type="text" name="contactVal">
			</li>
			<li class="fstyle-4">
				<p>你寻找的产品类型：</p>
				<span>
					<label><input type="checkbox" name="chosetype" value="luxury jewelry & accessories">奢侈品与配饰</label>
					<label><input type="checkbox" name="chosetype" value="female fashion">女士时尚</label>
					<label><input type="checkbox" name="chosetype" value="male fashion">男士时尚 </label>
					<label><input type="checkbox" name="chosetype" value="beauty & skincare">美妆与护肤</label>
					<label><input type="checkbox" name="chosetype" value="kid">儿童</label>
					<label><input type="checkbox" name="chosetype" value="underwear">內衣</label>
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
	function isPhoneNum(value){
      return /^0?(13[0-9]|15[012356789]|18[012356789]|14[57])[0-9]{8}$/.test(value);
    };

    function isEmailNum(value){
        return /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(value);
    }

    function isPEFun(nType,noNum){
        if(nType == "电话"){
            if(isPhoneNum(noNum)){
                return true;
            }
        }else{
            if(isEmailNum(noNum)){
                return true;
            }
        }
    }

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
	    		window.location.href = "/site/congratulation";
	    		//alert("提交成功");
	    	}else{
	    		alert("很抱歉，提交失败，请刷新之后重新提交");
	    	}
	    
	    })
	}


	function orderForm(){
		var _gender = $("select[name='gender']").val();
		var _surname = $("input[name='surname']").val();
		var _name = $("input[name='name']").val();
		var _date = $("select[name='date']").val();
		var _hour = $("select[name='hour']").val();
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
		}else if(_date == "" || _date == "请选择"){
         	alert("请选择希望预约日期！");
        }else if(_hour == "" || _hour == "请选择"){
            alert("请选择希望预约时间！");
        }else if(_contact == "请选择"){
			alert("请选择联系方式类型！");
		}else if(!isPEFun(_contact, _contactVal)){
			alert("您的联系方式填写有误！");
		}else if(_typeArr == ""){
			alert("请选择您寻找的产品类型！");
		}else{
			
			formInterface(_gender, _surname, _name, _date, _hour, _contact, _contactVal, _typeArr, _brandVal);
			//alert("提交成功！");
		}

	}




function timeSetBox(pn){

      //获取时间段
      this.minPeriodTime = "10"; //早上10点
      this.maxPeriodTime = "20"; //晚上20点
      this.PeriodArr = [];
      this.PeriodNode = pn;
      this.timeSpacing = "15";

      this.setDate = function(){
        this.status = 6;
      }

      this.setPeriod = function(){
        for(var i=this.minPeriodTime; i<this.maxPeriodTime; i++){
        	for(var v=0; v<60/this.timeSpacing; v++){
                var nv;
                v*15==0?nv="00":nv = v*15;
                this.PeriodArr.push("<option>"+i+":"+nv+"</option>");
        	}
        }
      }

      this.setPeriod();
      this.PeriodArr.push("<option>20:00</option>");
      this.PeriodNode.append(this.PeriodArr);


      //获取日期段


}

function ObjectFactory(){

      var obj = {},

      Constructor = Array.prototype.shift.call( arguments );

      obj.__proto__ =  typeof Constructor .prototype === 'number'  ? Object.prototype

      :  Constructor .prototype;

      var ret = Constructor.apply( obj, arguments );

      return typeof ret === 'object' ? ret : obj;

}

ObjectFactory( timeSetBox, $("#hour"));
//a.doing();
//console.log(a);








var reg = new RegExp("-","g");

function GetDateStr(AddDayCount) {
    var dd = new Date();
    dd.setDate(dd.getDate()+AddDayCount);//获取AddDayCount天后的日期
    var y = dd.getFullYear();
    var m = dd.getMonth()+1;//获取当前月份的日期
    var d = dd.getDate();
    return y+"/"+m+"/"+d;
}

function getDays(strDateStart,strDateEnd){
   var strSeparator = "/"; //日期分隔符
   var oDate1;
   var oDate2;
   var iDays;
   oDate1= strDateStart.split(strSeparator);
   oDate2= strDateEnd.split(strSeparator);
   var strDateS = new Date(oDate1[0], oDate1[1]-1, oDate1[2]);
   var strDateE = new Date(oDate2[0], oDate2[1]-1, oDate2[2]);
   iDays = parseInt(Math.abs(strDateS - strDateE ) / 1000 / 60 / 60 /24)//把相差的毫秒数转换为天数
   return iDays ;
}

var laveDays = 150;//getDays(GetDateStr(1),"2016/4/21");

for(var i=0; i<=laveDays; i++){
    var date1 = new Date(GetDateStr(0).replace(reg,"/"));
    var date2 = new Date(date1);

    date2.setDate(date1.getDate()+i);
    var times = date2.getFullYear()+"/"+(date2.getMonth()+1)+"/"+date2.getDate();
    $("#date").append("<option>"+times+"</option>");
    //console.log(times);
}








</script>















