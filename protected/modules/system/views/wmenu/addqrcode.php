<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');
$cs->registerScriptFile($baseUrl.'/js/jquery.fineuploader-3.7.0.min.js');

$msgTypeAry = array('text'=>'文本','news'=>'图文','image'=>'图片',);
?>
<div id="SystemWmenuAddqrcodePanel">
	<div style="text-align:center" >
		<form id="SystemWmenuAddqrcodeForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				
				<tr id="SystemWmenuAddqrcodeBakArea">
					<td style="text-align:right" class="row">二维码说明：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuAddqrcodeBak" name="SystemWmenuAddqrcodeBak" value="">
					</td>
				</tr>

				<tr id="SystemWmenuAddqrcodeSceneIdArea">
					<td style="text-align:right" class="row">二维码场景值：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuAddqrcodeSceneId" name="SystemWmenuAddqrcodeSceneId" value="">
					</td>
				</tr>

				<tr id="SystemWmenuAddqrcodeImgArea">
					<td style="text-align:right" class="row">二维码图片：</td>
					<td style="text-align:left" class="row"><img src="">
						<input type="hidden" id="SystemWmenuAddqrcodeImg" name="SystemWmenuAddqrcodeImg" value="">
					</td>
				</tr>

				<tr id="SystemWmenuAddqrcodeTicketArea">
					<td style="text-align:right" class="row">Ticket：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuAddqrcodeTicket" name="SystemWmenuAddqrcodeTicket" value="" readonly>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="systemWmenuAddqrcode.getQrcode()">获取二维码</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="systemWmenuAddqrcode.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var systemWmenuAddqrcode = {
		init:function(){
			$("#SystemWmenuAddqrcodePanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '添加二维码',  
										});
			systemWmenuAddqrcode.createUploader('SystemWmenuAddqrcodeSimg');
			
		},
		getQrcode:function(){
			var formdata = {
				sceneId : $("input[name='SystemWmenuAddqrcodeSceneId']").val()
			}
			if(!formdata.sceneId){
				$.messager.alert('系统消息',"请输入场景值",'error');
				return false;
			}
			if(formdata.sceneId<=0||formdata.sceneId>100000){
				$.messager.alert('系统消息',"场景值范围在1~100000之间",'error');
				return false;
			}
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/wmenu/getqrcode',
				data:formdata,
				dataType:"JSON",
				success:function(data){					
					$("#SystemWmenuAddqrcodeTicket").val(data.ticket);
					$("#SystemWmenuAddqrcodeImgArea img").attr("src","https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket="+data.ticket)

				}
			});
			
		},
		submitForm:function(){			
			var formdata = {
				bak : $("#SystemWmenuAddqrcodeBak").val(),
				sceneId : $("input[name='SystemWmenuAddqrcodeSceneId']").val(),
				ticket : $("#SystemWmenuAddqrcodeTicket").val()
			}
			if(!formdata.bak){
				$.messager.alert('系统消息',"请输入二维码说明",'error');
				return false;
			}
			if(!formdata.ticket){
				$.messager.alert('系统消息',"请先生成二维码后再提交",'error');
				return false;
			}
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/wmenu/qrcodeadd',
				data:formdata,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','添加二维码').tabs("select","二维码列表");
						$('#systemWmenuqrcodeDatagrid').datagrid('reload');
					}else{
						$.messager.alert('系统消息',data.msg,'error');
					}
				}
			});
		},		
		alertInfoMsg:function(val,msg){
			if(!val){
				$.messager.alert('系统消息',msg,'info');
				return false;
			}else{
				return true;
			}
		},
		createUploader:function (obj){
			$('#'+obj+'ShowArea').fineUploader({
				uploaderType: 'basic',
				multiple: false,
				debug: true,
				autoUpload: true,
				button: $("#"+obj+'But'),
				request: {
					endpoint: BASEUSER+'/system/home/upload'
				}
			}).on('progress',function(id, fileName, loaded, total){
				$("#"+obj+"ButText").hide();
				$("#"+obj+"Processing").show();
			}).on('complete',function(id, fileName, responseJSON,xhr){
				$("#"+obj+"Show").attr('src', BASEUSER+"/"+xhr.uploadName);
				$("#"+obj).val(xhr.uploadName);
				$("#"+obj+"ButText").show();
				$("#"+obj+"Processing").hide();
			}).on('upload',function(id, fileName){
				//alert('upload');
			}).on('submit', function(qrcode, id, name) {
				// alert('onsubmit');//$(this).fineUploader('setParams', {'param1': 'val1'});
			}).on('error',function(id, name, errorReason, xhr){
				alert(xhr)
			});
		},
	}
	
	systemWmenuAddqrcode.init();
//-->
</script>