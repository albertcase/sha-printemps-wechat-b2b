<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');
$cs->registerScriptFile($baseUrl.'/js/jquery.fineuploader-3.7.0.min.js');
?>
<div id="SystemWmenuTextEditPanel">
	<div style="text-align:center" >
		<form id="SystemWmenuTextEditForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				<tr>
					<td style="text-align:right" class="row">标题：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuTextEditTitle" id="SystemWmenuTextEditTitle" data-options="required:true" value="<?php echo $wmenu["title"];?>" />  
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">日期：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuTextEditDt" id="SystemWmenuTextEditDt" data-options="required:true" value="<?php echo $wmenu["dt"];?>" />  
					</td>
				</tr>
				<tr>
					<td style="text-align:right;" class="row">图文消息图片：</td>
					<td style="text-align:left;" class="row">
						<div id="SystemWmenuTextEditSimgShowArea">
							<img src="<?php echo $wmenu["pic"];?>" id="SystemWmenuTextEditSimgShow" width="200px">
						</div>
						<div id="SystemWmenuTextEditSimgBut" style="width:120px">
							<div id="SystemWmenuTextEditSimgProcessing" style="display:none">
								<img id="SystemWmenuTextEditSimgShow" src="<?php echo $baseUrl?>/images/system/processing.gif" style="width: 20px;">
							</div>
							<div id="SystemWmenuTextEditSimgButText" style="padding:10px 0 10px 0;"><a href="javascript:void(0)" class="easyui-linkbutton" >点击上传图片</a></div>
						</div>
						<input type="hidden" value="<?php echo $wmenu["pic"];?>" name="SystemWmenuTextEditSimg" id="SystemWmenuTextEditSimg">
					</td>
				</tr>
				<tr id="SystemWmenuAddEventTextArea">
					<td style="text-align:right" class="row">内容：</td>
					<td style="text-align:left" class="row">
						<textarea cols="50" rows="10" id="SystemWmenuAddContent" name="SystemWmenuAddContent"><?php echo $wmenu["content"];?></textarea>
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">链接：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuTextEditUrl" id="SystemWmenuTextEditUrl" data-options="required:true" value="<?php echo $wmenu["url"];?>" />  
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<input type="hidden" value="<?php echo $wmenu['id']?>" name="SystemWmenuTextEditId" id="SystemWmenuTextEditId">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="SystemWmenuTextEdit.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var SystemWmenuTextEdit = {
		init:function(){
			$("#SystemWmenuTextEditPanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '添加微信菜单',  
										});
			SystemWmenuTextEdit.createUploader('SystemWmenuTextEditSimg');
		},
		submitForm:function(){			
			var formdata = {
				title : $("#SystemWmenuTextEditTitle").val(),
				dt : $("#SystemWmenuTextEditDt").val(),
				pic : $("#SystemWmenuTextEditSimg").val(),
				content : $("#SystemWmenuAddContent").val(),
				url :$("#SystemWmenuTextEditUrl").val(),
				id:$("#SystemWmenuTextEditId").val()
			}

			if(!SystemWmenuTextEdit.alertInfoMsg(formdata.title,'请填写标题'))return false;
			if(!SystemWmenuTextEdit.alertInfoMsg(formdata.dt,'请填写日期'))return false;
			if(!SystemWmenuTextEdit.alertInfoMsg(formdata.pic,'请上传图片'))return false;
			if(!SystemWmenuTextEdit.alertInfoMsg(formdata.content,'请填写内容'))return false;
			if(!SystemWmenuTextEdit.alertInfoMsg(formdata.url,'请填写链接'))return false;
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/wmenu/textupdate',
				data:formdata,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','编辑微信文章').tabs("select","微信文章列表");
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
	
	SystemWmenuTextEdit.init();
//-->
</script>