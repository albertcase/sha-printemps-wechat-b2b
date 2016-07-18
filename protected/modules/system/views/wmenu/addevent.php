<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');
$cs->registerScriptFile($baseUrl.'/js/jquery.fineuploader-3.7.0.min.js');

$msgTypeAry = array('text'=>'文本','news'=>'图文','image'=>'图片',);
?>
<div id="SystemWmenuAddEventPanel">
	<div style="text-align:center" >
		<form id="SystemWmenuAddEventForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				<tr>
					<td style="text-align:right" class="row">用户事件：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuAddEventEvent" id="SystemWmenuAddEventEvent" data-options='  
								valueField: "id",
								textField: "name",
								data: [{id:"subscribe",name:"关注"},{id:"click",name:"点击"},{id:"text",name:"接收文本"}],
								onSelect: function(record){
									if(record.id=="click"){
										$("#SystemWmenuAddEventKeywordArea").hide()
										$("#SystemWmenuAddEventMenuList").show();
									}else if("text"){
										$("#SystemWmenuAddEventKeywordArea").show();
										$("#SystemWmenuAddEventMenuList").hide();
									}else{
										$("#SystemWmenuAddEventKeywordArea").hide();
										$("#SystemWmenuAddEventMenuList").hide();
									}
								},
								onLoadSuccess:function(){
									
								}'>
						
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">消息类型：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuAddEventMsgtype" id="SystemWmenuAddEventMsgtype" data-options='  
								valueField: "id",
								textField: "name",
								data: [{id:"text",name:"文本"},{id:"news",name:"图文"}],
								onSelect: function(record){
									if(record.id=="text"){
										$("#SystemWmenuAddEventTextArea").show();
										$("#SystemWmenuAddEventNewsArea").hide();
									}else if(record.id=="news"){
										$("#SystemWmenuAddEventTextArea").hide();
										$("#SystemWmenuAddEventNewsArea").show();
									}
								},
								onLoadSuccess:function(){
									
									
								}'>
					</td>
				</tr>
				<tr id="SystemWmenuAddEventMenuList" style="display:none">
					<td style="text-align:right" class="row">菜单：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuAddEventMenu" id="SystemWmenuAddEventMenu" data-options='  
								valueField: "id",
								textField: "name",
								data: <?php echo $pmenu?>,
								onSelect: function(record){
									
								},
								onLoadSuccess:function(){
									
								}'>  
					</td>
				</tr>
				<tr id="SystemWmenuAddEventKeywordArea" style="display:none">
					<td style="text-align:right" class="row">接收的关键字：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuAddEventKeyword" name="SystemWmenuAddEventKeyword" value="">如果用户发送的不是关键字的是需要进行统一进行回复时，请不要填写关键字

					</td>
				</tr>

				<tr id="SystemWmenuAddEventTextArea" style="display:none">
					<td style="text-align:right" class="row">文本消息内容：</td>
					<td style="text-align:left" class="row">
						<textarea cols="50" rows="10" id="SystemWmenuAddEventText" name="SystemWmenuAddEventText"></textarea>
					</td>
				</tr>
				<tbody id="SystemWmenuAddEventNewsArea" style="display:none">
				<tr>
					<td style="text-align:right" class="row">图文消息标题：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuAddEventTitle" name="SystemWmenuAddEventTitle" value="">
					</td>
				</tr>

				<tr>
					<td style="text-align:right" class="row">图文消息描述：</td>
					<td style="text-align:left" class="row">
						<textarea cols="50" rows="10" id="SystemWmenuAddEventDiscription" name="SystemWmenuAddEventDiscription"></textarea>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;" class="row">图文消息图片：</td>
					<td style="text-align:left;" class="row">
						<div id="SystemWmenuAddEventSimgShowArea">
							<img src="" id="SystemWmenuAddEventSimgShow" width="1px">
						</div>
						<div id="SystemWmenuAddEventSimgBut" style="width:120px">
							<div id="SystemWmenuAddEventSimgProcessing" style="display:none">
								<img id="SystemWmenuAddEventSimgShow" src="<?php echo $baseUrl?>/images/system/processing.gif" style="width: 20px;">
							</div>
							<div id="SystemWmenuAddEventSimgButText" style="padding:10px 0 10px 0;"><a href="javascript:void(0)" class="easyui-linkbutton" >点击上传图片</a></div>
						</div>
						<input type="hidden" value="" name="SystemWmenuAddEventSimg" id="SystemWmenuAddEventSimg">
					</td>
				</tr>
				

				<tr>
					<td style="text-align:right" class="row">图文消息链接：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuAddEventUrl" name="SystemWmenuAddEventUrl" value="">
					</td>
				</tr>
				</tbody>
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="systemWmenuAddEvent.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var systemWmenuAddEvent = {
		init:function(){
			$("#SystemWmenuAddEventPanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '编辑微信事件',  
										});
			systemWmenuAddEvent.createUploader('SystemWmenuAddEventSimg');
			
		},
		submitForm:function(){			
			var formdata = {
				mid : $("input[name='SystemWmenuAddEventMenu']").val(),
				event : $("input[name='SystemWmenuAddEventEvent']").val(),
				msgtype : $("input[name='SystemWmenuAddEventMsgtype']").val(),
				content : $("#SystemWmenuAddEventText").val(),
				title : $("#SystemWmenuAddEventTitle").val(),
				description : $("#SystemWmenuAddEventDiscription").val(),
				picUrl : $("#SystemWmenuAddEventSimg").val(),
				url : $("#SystemWmenuAddEventUrl").val(),
				keyword : $("#SystemWmenuAddEventKeyword").val()
			}

			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/wmenu/eventadd',
				data:formdata,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','添加微信事件').tabs("select","微信事件列表");
						$('#systemWmenuEventDatagrid').datagrid('reload');
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
		},
	}
	
	systemWmenuAddEvent.init();
//-->
</script>