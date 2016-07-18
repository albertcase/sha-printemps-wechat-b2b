<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');
$cs->registerScriptFile($baseUrl.'/js/jquery.fineuploader-3.7.0.min.js');
?>
<div id="SystemWmenuTextAddPanel">
	<div style="text-align:center" >
		<form id="SystemWmenuTextAddForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				<tr>
					<td style="text-align:right" class="row">标题：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuTextAddTitle" id="SystemWmenuTextAddTitle" data-options="required:true" value="" />  
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">日期：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuTextAddDt" id="SystemWmenuTextAddDt" data-options="required:true" value="" />  
					</td>
				</tr>
				<tr>
					<td style="text-align:right;" class="row">图文消息图片：</td>
					<td style="text-align:left;" class="row">
						<div id="SystemWmenuTextAddSimgShowArea">
							<img src="" id="SystemWmenuTextAddSimgShow" width="200px">
						</div>
						<div id="SystemWmenuTextAddSimgBut" style="width:120px">
							<div id="SystemWmenuTextAddSimgProcessing" style="display:none">
								<img id="SystemWmenuTextAddSimgShow" src="<?php echo $baseUrl?>/images/system/processing.gif" style="width: 20px;">
							</div>
							<div id="SystemWmenuTextAddSimgButText" style="padding:10px 0 10px 0;"><a href="javascript:void(0)" class="easyui-linkbutton" >点击上传图片</a></div>
						</div>
						<input type="hidden" value="" name="SystemWmenuTextAddSimg" id="SystemWmenuTextAddSimg">
					</td>
				</tr>
				<tr id="SystemWmenuAddEventTextArea">
					<td style="text-align:right" class="row">内容：</td>
					<td style="text-align:left" class="row">
						<textarea cols="50" rows="10" id="SystemWmenuAddContent" name="SystemWmenuAddContent"></textarea>
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">链接：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuTextAddUrl" id="SystemWmenuTextAddUrl" data-options="required:true" value="" />  
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="SystemWmenuTextAdd.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var SystemWmenuTextAdd = {
		init:function(){
			$("#SystemWmenuTextAddPanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '添加微信菜单',  
										});
			SystemWmenuTextAdd.createUploader('SystemWmenuTextAddSimg');
		},
		submitForm:function(){			
			var formdata = {
				title : $("#SystemWmenuTextAddTitle").val(),
				dt : $("#SystemWmenuTextAddDt").val(),
				pic : $("#SystemWmenuTextAddSimg").val(),
				content : $("#SystemWmenuAddContent").val(),
				url :$("#SystemWmenuTextAddUrl").val()
			}

			if(!SystemWmenuTextAdd.alertInfoMsg(formdata.title,'请填写标题'))return false;
			if(!SystemWmenuTextAdd.alertInfoMsg(formdata.dt,'请填写日期'))return false;
			if(!SystemWmenuTextAdd.alertInfoMsg(formdata.pic,'请上传图片'))return false;
			if(!SystemWmenuTextAdd.alertInfoMsg(formdata.content,'请填写内容'))return false;
			if(!SystemWmenuTextAdd.alertInfoMsg(formdata.url,'请填写链接'))return false;
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/wmenu/addtext',
				data:formdata,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','添加微信文章').tabs("select","微信文章列表");
						$('#systemWmenuTextDatagrid').datagrid('reload');
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
			}).on('submit', function(event, id, name) {
				// alert('onsubmit');//$(this).fineUploader('setParams', {'param1': 'val1'});
			}).on('error',function(id, name, errorReason, xhr){
				alert(xhr)
			});
		}
	}
	
	SystemWmenuTextAdd.init();
//-->
</script>