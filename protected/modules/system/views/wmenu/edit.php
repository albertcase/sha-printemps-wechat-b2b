<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');

?>
<div id="SystemWmenuEditPanel">
	<div style="text-align:center" >
		<form id="SystemWmenuEditForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				<tr>
					<td style="text-align:right" class="row">名称：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuEditName" id="SystemWmenuEditName" data-options="required:true" value="<?php echo $wmenu["name"];?>" />  
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">父级菜单：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-combobox" name="SystemWmenuEditpName" id="SystemWmenuEditpName" data-options='  
								valueField: "id",
								textField: "name",
								data: <?php echo $pmenu;?>,
								onSelect: function(record){
									
								},
								onLoadSuccess:function(){
									$("#SystemWmenuEditpName").combobox("setValue", "<?php echo $wmenu["pid"];?>");
								}'>  
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">类型：</td>
					<td style="text-align:left" class="row">
						<select class="easyui-combobox" name="SystemWmenuEditEvent" id="SystemWmenuEditEvent" data-options="onSelect:function(record){if(record.value=='click'){ $('#SystemWmenuEditEventType2').hide();$('#SystemWmenuEditEventType1').show();}else{$('#SystemWmenuEditEventType1').hide();$('#SystemWmenuEditEventType2').show();}}">
							<option value="click" <?php if($wmenu["event"]=='click') echo 'selected';?>>click</option>
							<option value="view" <?php if($wmenu["event"]=='view') echo 'selected';?>>view</option>
						</select>  
					</td>
				</tr>
				<tr id="SystemWmenuEditEventType1" <?php if($wmenu["event"]=='view') echo 'style="display:none"';?>>
					<td style="text-align:right" class="row">类型KEY：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuEditEventKey" id="SystemWmenuEditEventKey" data-options="required:true" value="<?php echo $wmenu["eventkey"];?>" />  
					</td>
				</tr>
				<tr id="SystemWmenuEditEventType2" <?php if($wmenu["event"]=='click') echo 'style="display:none"';?>>
					<td style="text-align:right" class="row">类型url：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuEditEventUrl" id="SystemWmenuEditEventUrl" data-options="required:true" value="<?php echo $wmenu["eventurl"];?>" />  
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<input type="hidden" value="<?php echo $wmenu['id']?>" name="SystemWmenuEditID" id="SystemWmenuEditID">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="systemWmenuEdit.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var systemWmenuEdit = {
		init:function(){
			$("#SystemWmenuEditPanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '编辑编辑微信菜单',  
										});
			
		},
		submitForm:function(){			
			var formdata = {
				name : $("#SystemWmenuEditName").val(),
				pid : $("input[name='SystemWmenuEditpName']").val(),
				event : $("input[name='SystemWmenuEditEvent']").val(),
				eventkey : $("#SystemWmenuEditEventKey").val(),
				eventurl : $("#SystemWmenuEditEventUrl").val(),
				id : $("#SystemWmenuEditID").val()
			}

			if(!systemWmenuEdit.alertInfoMsg(formdata.name,'请填写名称'))return false;
			if(!systemWmenuEdit.alertInfoMsg(formdata.id,'请求数据非法'))return false;

			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/wmenu/menuupdate',
				data:formdata,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','编辑微信菜单').tabs("select","微信菜单列表");
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
	
	systemWmenuEdit.init();
//-->
</script>