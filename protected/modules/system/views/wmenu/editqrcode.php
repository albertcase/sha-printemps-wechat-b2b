<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');
$cs->registerScriptFile($baseUrl.'/js/jquery.fineuploader-3.7.0.min.js');

$msgTypeAry = array('text'=>'文本','news'=>'图文','image'=>'图片',);
?>
<div id="SystemWmenuEditqrcodePanel">
	<div style="text-align:center" >
		<form id="SystemWmenuEditqrcodeForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				<tr>
					<td style="text-align:right" class="row">用户事件：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuEditqrcodeqrcode" id="SystemWmenuEditqrcodeqrcode" data-options='  
								valueField: "id",
								textField: "name",
								data: [{id:"subscribe",name:"关注"},{id:"click",name:"点击"},{id:"text",name:"接收文本"}],
								onSelect: function(record){
									if(record.id=="click"){
										$("#SystemWmenuEditqrcodeKeywordArea").hide()
										$("#SystemWmenuEditqrcodeMenuList").show();
									}else if("text"){
										$("#SystemWmenuEditqrcodeKeywordArea").show();
										$("#SystemWmenuEditqrcodeMenuList").hide();
									}else{
										$("#SystemWmenuEditqrcodeKeywordArea").hide();
										$("#SystemWmenuEditqrcodeMenuList").hide();
									}
								},
								onLoadSuccess:function(){
									$("#SystemWmenuEditqrcodeqrcode").combobox("select", "<?php echo $wmenu["qrcode"];?>");
									if("<?php echo $wmenu['qrcode'];?>"=="click"){
										$("#SystemWmenuEditqrcodeMenuList").show();
									}
								}'>
						
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">消息类型：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuEditqrcodeMsgtype" id="SystemWmenuEditqrcodeMsgtype" data-options='  
								valueField: "id",
								textField: "name",
								data: [{id:"text",name:"文本"},{id:"news",name:"图文"}],
								onSelect: function(record){
									if(record.id=="text"){
										$("#SystemWmenuEditqrcodeTextArea").show();
										$("#SystemWmenuEditqrcodeNewsArea").hide();
									}else if(record.id=="news"){
										$("#SystemWmenuEditqrcodeTextArea").hide();
										$("#SystemWmenuEditqrcodeNewsArea").show();
									}
								},
								onLoadSuccess:function(){
									$("#SystemWmenuEditqrcodeMsgtype").combobox("select", "<?php echo $wmenu["msgtype"];?>");
									
								}'>
					</td>
				</tr>
				<tr id="SystemWmenuEditqrcodeMenuList" style="display:none">
					<td style="text-align:right" class="row">菜单：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuEditqrcodeMenu" id="SystemWmenuEditqrcodeMenu" data-options='  
								valueField: "id",
								textField: "name",
								data: <?php echo $pmenu?>,
								onSelect: function(record){
									
								},
								onLoadSuccess:function(){
									$("#SystemWmenuEditqrcodeMenu").combobox("select", "<?php echo $wmenu["mid"];?>");
								}'>  
					</td>
				</tr>
				<tr id="SystemWmenuEditqrcodeKeywordArea" style="display:none">
					<td style="text-align:right" class="row">接收的关键字：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditqrcodeKeyword" name="SystemWmenuEditqrcodeKeyword" value="<?php echo $wmenu["keyword"];?>">如果用户发送的不是关键字的是需要进行统一进行回复时，请不要填写关键字

					</td>
				</tr>
				<tr id="SystemWmenuEditqrcodeTextArea" style="display:none">
					<td style="text-align:right" class="row">文本消息内容：</td>
					<td style="text-align:left" class="row">
						<textarea cols="50" rows="10" id="SystemWmenuEditqrcodeText" name="SystemWmenuEditqrcodeText"><?php echo $wmenu['content']?></textarea>
					</td>
				</tr>
				<tbody id="SystemWmenuEditqrcodeNewsArea" style="display:none">
				<tr>
					<td style="text-align:right" class="row">图文消息标题：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditqrcodeTitle" name="SystemWmenuEditqrcodeTitle" value="<?php echo $wmenu['title']?>">
					</td>
				</tr>

				<tr>
					<td style="text-align:right" class="row">图文消息描述：</td>
					<td style="text-align:left" class="row">
						<textarea cols="50" rows="10" id="SystemWmenuEditqrcodeDiscription" name="SystemWmenuEditqrcodeDiscription"><?php echo $wmenu['description']?></textarea>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;" class="row">图文消息图片：</td>
					<td style="text-align:left;" class="row">
						<div id="SystemWmenuEditqrcodeSimgShowArea">
							<img src="<?php echo $baseUrl.$wmenu['picUrl']?>" id="SystemWmenuEditqrcodeSimgShow" width="100px">
						</div>
						<div id="SystemWmenuEditqrcodeSimgBut" style="width:120px">
							<div id="SystemWmenuEditqrcodeSimgProcessing" style="display:none">
								<img id="SystemWmenuEditqrcodeSimgShow" src="<?php echo $baseUrl?>/images/system/processing.gif" style="width: 20px;">
							</div>
							<div id="SystemWmenuEditqrcodeSimgButText" style="padding:10px 0 10px 0;"><a href="javascript:void(0)" class="easyui-linkbutton" >点击上传图片</a></div>
						</div>
						<input type="hidden" value="<?php echo $wmenu['picUrl']?>" name="SystemWmenuEditqrcodeSimg" id="SystemWmenuEditqrcodeSimg">
					</td>
				</tr>
				

				<tr>
					<td style="text-align:right" class="row">图文消息链接：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditqrcodeUrl2" name="SystemWmenuEditqrcodeUrl2" value="<?php echo $wmenu['url']?>">
					</td>
				</tr>
				</tbody>
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<input type="hidden" value="<?php echo $wmenu['id']?>" name="SystemWmenuEditqrcodeID" id="SystemWmenuEditqrcodeID">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="systemWmenuEditqrcode.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var systemWmenuEditqrcode = {
		init:function(){
			$("#SystemWmenuEditqrcodePanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '编辑微信事件',  
										});
			systemWmenuEditqrcode.createUploader('SystemWmenuEditqrcodeSimg');
			
		},
		submitForm:function(){	
			var formdata = {
				mid : $("input[name='SystemWmenuEditqrcodeMenu']").val(),
				qrcode : $("input[name='SystemWmenuEditqrcodeqrcode']").val(),
				msgtype : $("input[name='SystemWmenuEditqrcodeMsgtype']").val(),
				content : $("#SystemWmenuEditqrcodeText").val(),
				title : $("#SystemWmenuEditqrcodeTitle").val(),
				description : $("#SystemWmenuEditqrcodeDiscription").val(),
				picUrl : $("#SystemWmenuEditqrcodeSimg").val(),
				url : $("#SystemWmenuEditqrcodeUrl2").val(),
				keyword : $("#SystemWmenuEditqrcodeKeyword").val(),
				id : $("#SystemWmenuEditqrcodeID").val()
			}

			if(!systemWmenuEditqrcode.alertInfoMsg(formdata.id,'请求数据非法'))return false;

			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/wmenu/qrcodeupdate',
				data:formdata,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','编辑微信事件').tabs("select","微信事件列表");
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
	
	systemWmenuEditqrcode.init();
//-->
</script>