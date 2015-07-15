<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');
$cs->registerScriptFile($baseUrl.'/js/jquery.fineuploader-3.7.0.min.js');
?>
<div id="SystemPasswordEditPanel">
	<div style="text-align:center" >
		<form id="SystemPasswordEditForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<td style="text-align:right" class="row">旧密码：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" rows="5" cols="50" type="password" name="SystemPasswordEditOld" id="SystemPasswordEditOld" data-options="required:true">
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">新密码：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" rows="5" cols="50" type="password" name="SystemPasswordEditNew1" id="SystemPasswordEditNew1" data-options="required:true">
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">确认新密码：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" rows="5" cols="50" type="password" name="SystemPasswordEditNew2" id="SystemPasswordEditNew2" data-options="required:true">
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<input type="hidden" value="<?php echo $rsMsg['id']?>" name="SystemPasswordEditID" id="SystemPasswordEditID">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="SystemPasswordEdit.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var SystemPasswordEdit = {
		init:function(){
			$("#SystemPasswordEditPanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '修改密码',  
										});
		},
		submitForm:function(){
			var formMsg = {
				oldpassword:$("#SystemPasswordEditOld").val(),
				newpassword1:$("input[name='SystemPasswordEditNew1']").val(),
				newpassword2:$("input[name='SystemPasswordEditNew2']").val(),
			}

			if(!SystemPasswordEdit.alertInfoMsg(formMsg.oldpassword,'请填写旧密码'))return false;
			if(!SystemPasswordEdit.alertInfoMsg(formMsg.newpassword1,'请填写新密码'))return false;
			if(!SystemPasswordEdit.alertInfoMsg(formMsg.newpassword2,'请确认新密码'))return false;
			if(formMsg.newpassword1!=formMsg.newpassword2){$.messager.alert('系统消息','两次密码不统一','info');return false}
			
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/sysuser/passwordUpdate',
				data:formMsg,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','修改密码').tabs("select","修改密码");
						$('#SystemPasswordDatagrid').datagrid('reload');
					}else{
						$.messager.alert('系统消息',data.msg,'error');
					}
				}
			});
		},
		createUploader:function (obj){
			$('#'+obj+'ShowArea').fineUploader({
				uploaderType: 'basic',
				multiple: false,
				debug: true,
				autoUpload: true,
				button: $("#"+obj+'But'),
				request: {
					endpoint: BASEUSER+'/system/home/uploadCsv'
				}
			}).on('progress',function(id, fileName, loaded, total){
				$("#"+obj+"ButText").hide();
				$("#"+obj+"Processing").show();
			}).on('complete',function(id, fileName, responseJSON,xhr){
				$("#"+obj+"ShowArea").text(xhr.uploadName);
				$("#"+obj).val(xhr.uploadName);
				$("#"+obj+"ButText").show();
				$("#"+obj+"Processing").hide();
			}).on('upload',function(id, fileName){
				//alert('upload');
			}).on('submit', function(event, id, name) {
				// alert('onsubmit');//$(this).fineUploader('setParams', {'param1': 'val1'});
			}).on('error',function(id, name, errorReason, xhr){
				alert(xhr)
			});
		},
		alertInfoMsg:function(val,msg){
			if(!val){
				$.messager.alert('系统消息',msg,'info');
				return false;
			}else{
				return true;
			}
		}
	}
	
	SystemPasswordEdit.init();
//-->
</script>