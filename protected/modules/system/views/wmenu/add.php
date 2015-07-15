<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');

?>
<div id="SystemWmenuAddPanel">
	<div style="text-align:center" >
		<form id="SystemWmenuAddForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				<tr>
					<td style="text-align:right" class="row">名称：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuAddName" id="SystemWmenuAddName" data-options="required:true" value="" />  
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">父级菜单：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuAddpName" id="SystemWmenuAddpName" data-options='  
								valueField: "id",
								textField: "name",
								data: <?php echo $pmenu;?>,
								onSelect: function(record){
									
								},
								onLoadSuccess:function(){
									
								}'>  
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">类型：</td>
					<td style="text-align:left" class="row">
						<select class="easyui-combobox" name="SystemWmenuAddEvent" id="SystemWmenuAddEvent" data-options="onSelect:function(record){if(record.value=='click'){ $('#SystemWmenuAddEventType2').hide();$('#SystemWmenuAddEventType1').show();}else{$('#SystemWmenuAddEventType1').hide();$('#SystemWmenuAddEventType2').show();}}">
							<option value="click">click</option>
							<option value="view">view</option>
						</select>  
					</td>
				</tr>
				<tr id="SystemWmenuAddEventType1">
					<td style="text-align:right" class="row">类型KEY：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuAddEventKey" id="SystemWmenuAddEventKey" data-options="required:true" value="" />  
					</td>
				</tr>
				<tr id="SystemWmenuAddEventType2" style="display:none">
					<td style="text-align:right" class="row">类型url：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuAddEventUrl" id="SystemWmenuAddEventUrl" data-options="required:true" value="" />  
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="systemWmenuAdd.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var systemWmenuAdd = {
		init:function(){
			$("#SystemWmenuAddPanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '添加微信菜单',  
										});
			
		},
		submitForm:function(){			
			var formdata = {
				name : $("#SystemWmenuAddName").val(),
				pid : $("input[name='SystemWmenuAddpName']").val(),
				event : $("input[name='SystemWmenuAddEvent']").val(),
				eventkey : $("#SystemWmenuAddEventKey").val(),
				eventurl : $("#SystemWmenuAddEventUrl").val()
			}

			if(!systemWmenuAdd.alertInfoMsg(formdata.name,'请填写名称'))return false;

			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/wmenu/menuadd',
				data:formdata,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','添加微信菜单').tabs("select","微信菜单列表");
						$('#systemWmenuIndexDatagrid').datagrid('reload');
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
		}
	}
	
	systemWmenuAdd.init();
//-->
</script>