<?php
//print_r(Yii::app()->user->sysUserId);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>微信管理系统</title>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.9.1.min.js" type="text/javascript"></script>
</head>

<style type="text/css">
<!--
body{font-size:12px; font-family:Verdana,Tahoma,Arial,Geogia,宋体,Sans-serif; color:#4e4e4e; padding-bottom:20px; margin:0; padding:0; position:relative; }
body,form,ul,ol,li,dl,dt,dd,p,h1,h2,h3,h4,h5,h6,img,div,span,input,textarea,tr,td,th,caption{padding:0; margin:0; border:0; }
li{list-style:none}
a{text-decoration:underline;}
img {border:none;}
/*------------------------------------ login -----------------------------------*/
body{background-color: #3d4e58;}
#user_login{ width:374px; height:226px; background:url(<?php echo Yii::app()->request->baseUrl; ?>/images/system/adm_03.gif) no-repeat; margin:0 auto; clear:both; margin-top:200px; _margin-top:200px;}
#user_login li{ padding:5px 0 5px 40px;}
.user_bg{ width:374px; margin:auto; margin:0 auto; clear:both;}
.user_logo{ width:193px; padding: 30px 0 20px 40px;}
.user_godiv{ width:374px; text-align:right; margin:0;}
.user_input{ width:177px; height:18px; background-image:url(<?php echo Yii::app()->request->baseUrl; ?>/images/system/user_11.gif); padding:5px;}
.user_button{ width:65px; height:25px; background-image:url(<?php echo Yii::app()->request->baseUrl; ?>/images/system/user_20.gif); margin:10px 90px 0 0px;}
.user_font{ font-size:13px; color:#fff; font-style:italic; font-weight:bold;}
/*------------------------------------ user_index -----------------------------------*/
#user_top{ width:100%; height:77px; background:url(<?php echo Yii::app()->request->baseUrl; ?>/images/system/admin_03.gif) repeat-x;}
#user_top .top_left{ width:30px; height:77px; background-image:url(<?php echo Yii::app()->request->baseUrl; ?>/images/system/admin_02.gif); float:left;} 
#user_top .top_right{ width:30px; height:77px; background-image:url(<?php echo Yii::app()->request->baseUrl; ?>/images/system/admin_04.gif); float:right;}
#user_top .user_name{ width:90%; float:left; padding:30px 0 10px 0px; font-size:12px; color:#fff; text-align:right;}
.usermain{ width:100%; overflow:hidden; }
.main_left{ width:192px; float:left; padding-left:20px;}
.main_right{ width:80%; float:left; text-align:center;height:766px;}
.user_left { width:192px; background:url(<?php echo Yii::app()->request->baseUrl; ?>/images/system/left.gif) repeat-y; padding-top:20px; padding-bottom:30000px; margin-bottom:-30000px;}
.user_left a{ font-size:13px; color:#aab3b8;}
.user_left a:hover{ font-size:13px; color:#3d4e55;}
#user_main{ width:90%; background-color:#d6dadc; padding:20px; margin:30px; margin:auto;}
#user_main div{ width:90%; padding:5px; border:1px #c8cdd0 solid; background:#f2f2f2; margin:20px auto;}
#framebody{ width:100%; height:100%; line-height:100%;}
.pointer(cursor:pointer)
-->
</style>
<script type="text/javascript">
var login = function (){
	var username = $("#username").val();
	var password = $("#password").val();
	if($("#autoLogin").is(":checked")){
		var autoLogin=1;
	}else{
		var autoLogin=0;
	}
	if(!username){
		alert("请输入用户名");
		return false;
	}

	if(!password){
		alert("请输入密码");
		return false;
	}

	$.ajax({
			type:"POST",
			global:false,
			url:"<?php echo Yii::app()->request->baseUrl; ?>/system/login/login",
			data:"username="+username+"&password="+password+"&autoLogin="+autoLogin,
			dataType:"JSON",
			success:function(data){
				if(data==1){

					<?php
						if(isset($_GET['command'])){
							echo 'window.location.href="'.Yii::app()->request->baseUrl.'/system?command='.$_GET['command'].'"';
						}else{
							echo 'window.location.href="'.Yii::app()->request->baseUrl.'/system"';
						}
					?>
				}
				if(data==2){
					alert("密码错误");
				}
				if(data==3){
					alert("该用户已被锁定");
				}
			}
		});
}
</script>
<body>

<div id="user_login">

  <div class="user_logo"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/system/user_06.gif" /></div>

    <form method="POST" action="">

    <ul>

        <li><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/system/user_09.gif" align="absmiddle" /> <input name="username" id="username" type="text" class="user_input"  value="<?php if(isset($_COOKIE['familymart_system_username'])){ echo $_COOKIE['familymart_system_username'];}?>" /></li>

        <li><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/system/user_15.gif" align="absmiddle" /> <input name="password" id="password" type="password" class="user_input" /> 

        <!-- <a href="#" class="user_font pointer">忘记密码？</a></li> -->

    </ul>

    <div class="user_godiv">
    	<input type="checkbox" style="cursor:pointer;" id="autoLogin" name="autoLogin" checked="true">下次自动登录
        <input name="" id="submit" type="button" onclick="login()" class="pointer user_button" />

    </div>

    </form>

</div>

<div class="user_bg"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/system/adm_05.gif" /></div>

</body>
</html>