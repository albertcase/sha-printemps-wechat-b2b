<table id="systemWmenuEventDatagrid" class="easyui-datagrid" title="微信事件列表"
		data-options="
			iconCls: 'icon-edit',
			singleSelect: true,
			toolbar: '#systemWmenuEventDatagridTb',
			url: '<?php echo Yii::app()->request->baseUrl; ?>/system/wmenu/eventlist',
			//onClickRow: systemWmenuEventOnClickRow,
			pagination: true,
			pageSize:50,
			autoRowHeight:true,
			rownumbers: true,
			animate: true,
			onLoadSuccess:function(){$('#systemWmenuEventDatagrid').datagrid('resize',{height:parseInt($('#tt .panel').css('height'))});systemWmenuEventEditIndex = undefined;}
		">
	<thead>
		<tr>
			<th data-options="field:'id',width:70,
				formatter:function(value,row){
					return '<a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=openTab(\'编辑微信事件\',\'/wmenu/editevent?id='+row.id+'\')>编辑</a> | <a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=systemWmenuEventDelete('+row.id+')>删除</a>';
			}">操作</th>
			<th data-options='field:"mname",width:100,formatter:function(value,row){
								if(!value){
									return "无";
								}else{
									return value;
								}
						}'>菜单</th>
			<th data-options='field:"msgtype",width:100,formatter:function(value,row){
								if(value=="text"){
									return "文本";
								}else if(value=="news"){
									return "图文";
								}
						}'>消息类型</th>

			<th data-options='field:"event",width:100,formatter:function(value,row){
								if(value=="subscribe"){
									return "关注";
								}else if(value=="click"){
									return "点击";
								}else if(value=="text"){
									return "接收文本";
								}
						}'>事件</th>
			<th data-options='field:"keyword",width:100,formatter:function(value,row){
								if(!value){
									return "无";
								}else{
									return value;
								}
						}'>事件</th>
			
			
		</tr>
	</thead>
</table>

<div id="systemWmenuEventDatagridTb" style="height:auto">
	<div style="margin-bottom:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="openTab('添加微信事件','/wmenu/addevent')">新增</a>
	</div>
</div>


<script type="text/javascript">
	var systemWmenuEventDelete = function (id){
		$.messager.confirm('系统消息', '确认删除该条信息吗？', function(r){
			if (r){
				$.ajax({
					type:"POST",
					global:false,
					url:BASEUSER+'/system/wmenu/eventdelete',
					data:{"id":id},
					dataType:"JSON",
					success:function(data){					
						if(data.code==1){						
							$.messager.alert('系统消息',data.msg);							
							$('#systemWmenuEventDatagrid').datagrid('reload');
						}else{
							$.messager.alert('系统消息',data.msg,'error');
						}
					}
				});
			}else{
				return false;
			}
		});
		
	}
	var systemWmenuEventEditIndex = undefined;
	var systemWmenuEventEndEditing = function (){
		if (systemWmenuEventEditIndex == undefined){
			return true;
		}
		if ($('#systemWmenuEventDatagrid').datagrid('validateRow', systemWmenuEventEditIndex)){
						
			$('#systemWmenuEventDatagrid').datagrid('endEdit', systemWmenuEventEditIndex);

			var HomeKvInfo = [];
			HomeKvInfo.id = $('#systemWmenuEventDatagrid').datagrid('getRows')[systemWmenuEventEditIndex]['id'];
			HomeKvInfo.disable = $('#systemWmenuEventDatagrid').datagrid('getRows')[systemWmenuEventEditIndex]['disable'];
			HomeKvInfo.sortnum = $('#systemWmenuEventDatagrid').datagrid('getRows')[systemWmenuEventEditIndex]['sortnum'];
			
			systemWmenuEventSave(HomeKvInfo,systemWmenuEventEditIndex);
			systemWmenuEventEditIndex = undefined;

			return true;
		} else {
			return false;
		}
	}
	var systemWmenuEventOnClickRow = function (index,data){
		if (systemWmenuEventEditIndex != index){
			if (systemWmenuEventEditIndex == undefined){
				$('#systemWmenuEventDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				systemWmenuEventEditIndex = index;
			} else {	
				systemWmenuEventEndEditing();			
				$('#systemWmenuEventDatagrid').datagrid('refreshRow', systemWmenuEventEditIndex).datagrid('endEdit', systemWmenuEventEditIndex);
				$('#systemWmenuEventDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				
				systemWmenuEventEditIndex = index;
			}
			var cid = $('#systemWmenuEventDatagrid').datagrid('getRows')[systemWmenuEventEditIndex]['cid'];
			$("#systemWmenuEventAreaCombobox").combobox("reload",BASEUSER+"/system/franchise/arealistForStreet?cid="+cid);
		}
	}
	var systemWmenuEventAppend = function (){
		if (systemWmenuEventEndEditing()){
			$('#systemWmenuEventDatagrid').datagrid('appendRow',{status:'显示'});
			
			systemWmenuEventEditIndex = $('#systemWmenuEventDatagrid').datagrid('getRows').length-1;
			$('#systemWmenuEventDatagrid').datagrid('selectRow', systemWmenuEventEditIndex)
					.datagrid('beginEdit', systemWmenuEventEditIndex);
		}
	}
	var systemWmenuEventRemove = function (){
		if (systemWmenuEventEditIndex == undefined){return}
		$('#systemWmenuEventDatagrid').datagrid('cancelEdit', systemWmenuEventEditIndex)
				.datagrid('deleteRow', systemWmenuEventEditIndex);
		systemWmenuEventEditIndex = undefined;
	}
	var systemWmenuEventAccept = function (){
		systemWmenuEventEndEditing();
	}
	var systemWmenuEventReject = function(){
		$('#systemWmenuEventDatagrid').datagrid('rejectChanges');
		systemWmenuEventEditIndex = undefined;
	}
	var systemWmenuEventGetChanges = function (){
		var rows = $('#systemWmenuEventDatagrid').datagrid('getChanges','updated');
		alert(rows.length+' rows are changed!');
	}
	var systemWmenuEventSave = function (HomeKvInfo,editIndex){
		if(HomeKvInfo.id){
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/home/kvupdateforlistedit",
				data:{"id":HomeKvInfo.id,"disable":HomeKvInfo.disable,"sortnum":HomeKvInfo.sortnum},
				dataType:"JSON",
				success:function(data){
					if(data.code==1){
						$('#systemWmenuEventDatagrid').datagrid('acceptChanges').datagrid('reload',{});
					}else{
						$.messager.alert('系统消息',data.msg,'error');
						$('#systemWmenuEventDatagrid').datagrid('acceptChanges').datagrid('reload',{});
					}
				}
			});
		}else{
			// $.ajax({
			// 	type:"POST",
			// 	global:false,
			// 	url:BASEUSER+"/system/home/kvadd",
			// 	data:{"cid":HomeKvInfo.cid,"tid":HomeKvInfo.tid,"pic":HomeKvInfo.pic,"keywords":HomeKvInfo.keywords,"overtime":HomeKvInfo.overtime},
			// 	dataType:"JSON",
			// 	success:function(data){					
			// 		if(data.code==1){						
			// 			$('#systemWmenuEventDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}else{
			// 			$.messager.alert('系统消息',data.msg,'error');
			// 			$('#systemWmenuEventDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}
			// 	}
			// });
		}
	}

	
</script>