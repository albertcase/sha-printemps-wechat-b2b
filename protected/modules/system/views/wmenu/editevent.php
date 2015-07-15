<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');
$cs->registerScriptFile($baseUrl.'/js/jquery.fineuploader-3.7.0.min.js');

$msgTypeAry = array('text'=>'文本','news'=>'图文','image'=>'图片',);
?>
<div id="SystemWmenuEditEventPanel">
	<div style="text-align:center" >
		<form id="SystemWmenuEditEventForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				<tr>
					<td style="text-align:right" class="row">用户事件：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuEditEventEvent" id="SystemWmenuEditEventEvent" data-options='  
								valueField: "id",
								textField: "name",
								data: [{id:"subscribe",name:"关注"},{id:"click",name:"点击"},{id:"text",name:"接收文本"}],
								onSelect: function(record){
									if(record.id=="click"){
										$("#SystemWmenuEditEventKeywordArea").hide()
										$("#SystemWmenuEditEventMenuList").show();
									}else if("text"){
										$("#SystemWmenuEditEventKeywordArea").show();
										$("#SystemWmenuEditEventMenuList").hide();
									}else{
										$("#SystemWmenuEditEventKeywordArea").hide();
										$("#SystemWmenuEditEventMenuList").hide();
									}
								},
								onLoadSuccess:function(){
									$("#SystemWmenuEditEventEvent").combobox("select", "<?php echo $wmenu["event"];?>");
									if("<?php echo $wmenu['event'];?>"=="click"){
										$("#SystemWmenuEditEventMenuList").show();
									}
								}'>
						
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">消息类型：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuEditEventMsgtype" id="SystemWmenuEditEventMsgtype" data-options='  
								valueField: "id",
								textField: "name",
								data: [{id:"text",name:"文本"},{id:"news",name:"图文"}],
								onSelect: function(record){
									if(record.id=="text"){
										$("#SystemWmenuEditEventTextArea").show();
										$("#SystemWmenuEditEventNewsArea").hide();
									}else if(record.id=="news"){
										$("#SystemWmenuEditEventTextArea").hide();
										$("#SystemWmenuEditEventNewsArea").show();
									}
								},
								onLoadSuccess:function(){
									$("#SystemWmenuEditEventMsgtype").combobox("select", "<?php echo $wmenu["msgtype"];?>");
									
								}'>
					</td>
				</tr>
				<tr id="SystemWmenuEditEventMenuList" style="display:none">
					<td style="text-align:right" class="row">菜单：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuEditEventMenu" id="SystemWmenuEditEventMenu" data-options='  
								valueField: "id",
								textField: "name",
								data: <?php echo $pmenu?>,
								onSelect: function(record){
									
								},
								onLoadSuccess:function(){
									$("#SystemWmenuEditEventMenu").combobox("select", "<?php echo $wmenu["mid"];?>");
								}'>  
					</td>
				</tr>
				<tr id="SystemWmenuEditEventKeywordArea" style="display:none">
					<td style="text-align:right" class="row">接收的关键字：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditEventKeyword" name="SystemWmenuEditEventKeyword" value="<?php echo $wmenu["keyword"];?>">如果用户发送的不是关键字的是需要进行统一进行回复时，请不要填写关键字

					</td>
				</tr>
				<tr id="SystemWmenuEditEventTextArea" style="display:none">
					<td style="text-align:right" class="row">文本消息内容：</td>
					<td style="text-align:left" class="row">
						<textarea cols="50" rows="10" id="SystemWmenuEditEventText" name="SystemWmenuEditEventText"><?php echo $wmenu['content']?></textarea>
					</td>
				</tr>
				<tbody id="SystemWmenuEditEventNewsArea" style="display:none">
				<tr>
					<td style="text-align:right" class="row">图文消息标题：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditEventTitle" name="SystemWmenuEditEventTitle" value="<?php echo $wmenu['title']?>">
					</td>
				</tr>

				<tr>
					<td style="text-align:right" class="row">图文消息描述：</td>
					<td style="text-align:left" class="row">
						<textarea cols="50" rows="10" id="SystemWmenuEditEventDiscription" name="SystemWmenuEditEventDiscription"><?php echo $wmenu['description']?></textarea>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;" class="row">图文消息图片：</td>
					<td style="text-align:left;" class="row">
						<div id="SystemWmenuEditEventSimgShowArea">
							<img src="<?php echo $baseUrl.$wmenu['picUrl']?>" id="SystemWmenuEditEventSimgShow" width="100px">
						</div>
						<div id="SystemWmenuEditEventSimgBut" style="width:120px">
							<div id="SystemWmenuEditEventSimgProcessing" style="display:none">
								<img id="SystemWmenuEditEventSimgShow" src="<?php echo $baseUrl?>/images/system/processing.gif" style="width: 20px;">
							</div>
							<div id="SystemWmenuEditEventSimgButText" style="padding:10px 0 10px 0;"><a href="javascript:void(0)" class="easyui-linkbutton" >点击上传图片</a></div>
						</div>
						<input type="hidden" value="<?php echo $wmenu['picUrl']?>" name="SystemWmenuEditEventSimg" id="SystemWmenuEditEventSimg">
					</td>
				</tr>
				

				<tr>
					<td style="text-align:right" class="row">图文消息链接：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditEventUrl2" name="SystemWmenuEditEventUrl2" value="<?php echo $wmenu['url']?>">
					</td>
				</tr>
				</tbody>
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<input type="hidden" value="<?php echo $wmenu['id']?>" name="SystemWmenuEditEventID" id="SystemWmenuEditEventID">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="systemWmenuEditEvent.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var systemWmenuEditEvent = {
		init:function(){
			$("#SystemWmenuEditEventPanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '编辑微信事件',  
										});
			systemWmenuEditEvent.createUploader('SystemWmenuEditEventSimg');
			
		},
		submitForm:function(){	
			var formdata = {
				mid : $("input[name='SystemWmenuEditEventMenu']").val(),
				event : $("input[name='SystemWmenuEditEventEvent']").val(),
				msgtype : $("input[name='SystemWmenuEditEventMsgtype']").val(),
				content : $("#SystemWmenuEditEventText").val(),
				title : $("#SystemWmenuEditEventTitle").val(),
				description : $("#SystemWmenuEditEventDiscription").val(),
				picUrl : $("#SystemWmenuEditEventSimg").val(),
				url : $("#SystemWmenuEditEventUrl2").val(),
				keyword : $("#SystemWmenuEditEventKeyword").val(),
				id : $("#SystemWmenuEditEventID").val()
			}

			if(!systemWmenuEditEvent.alertInfoMsg(formdata.id,'请求数据非法'))return false;

			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/wmenu/eventupdate',
				data:formdata,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','编辑微信事件').tabs("select","微信事件列表");
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
	
	systemWmenuEditEvent.init();
//-->
</script>