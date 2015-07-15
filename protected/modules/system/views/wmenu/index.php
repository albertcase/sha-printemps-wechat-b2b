<table id="systemWmenuIndexDatagrid" class="easyui-datagrid" title="微信菜单列表"
		data-options="
			iconCls: 'icon-edit',
			singleSelect: true,
			toolbar: '#systemWmenuIndexDatagridTb',
			url: '<?php echo Yii::app()->request->baseUrl; ?>/system/wmenu/list',
			//onClickRow: systemWmenuIndexOnClickRow,
			pagination: true,
			pageSize:50,
			autoRowHeight:true,
			rownumbers: true,
			animate: true,
			onLoadSuccess:function(){$('#systemWmenuIndexDatagrid').datagrid('resize',{height:parseInt($('#tt .panel').css('height'))});systemWmenuIndexEditIndex = undefined;}
		">
	<thead>
		<tr>
			<th data-options="field:'id',width:70,
				formatter:function(value,row){
					return '<a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=openTab(\'编辑微信菜单\',\'/wmenu/edit?id='+row.id+'\')>编辑</a> | <a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=systemWmenuIndexDelete('+row.id+')>删除</a>';
			}">操作</th>
			<th data-options='field:"name",width:200'>名称</th>
			<th data-options='field:"pid",width:100,
					formatter:function(value,row){
						if(value==0){
							return "一级菜单";
						}else{
							return "二级菜单";
						}
					}'>类别</th>
			<th data-options='field:"event",width:50'>类型</th>
		</tr>
	</thead>
</table>

<div id="systemWmenuIndexDatagridTb" style="height:auto">
	<div style="margin-bottom:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="openTab('添加微信菜单','/wmenu/add')">新增</a>
	</div>
</div>


<script type="text/javascript">
	var systemWmenuIndexDelete = function (id){
		$.messager.confirm('系统消息', '确认删除该条信息吗？', function(r){
			if (r){
				$.ajax({
					type:"POST",
					global:false,
					url:BASEUSER+'/system/wmenu/menuDelete',
					data:{"id":id},
					dataType:"JSON",
					success:function(data){					
						if(data.code==1){						
							$.messager.alert('系统消息',data.msg);
							$('#tt').tabs("select","微信菜单列表").tabs("select","微信菜单列表");
							$('#systemWmenuIndexDatagrid').datagrid('reload');
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
	var systemWmenuIndexEditIndex = undefined;
	var systemWmenuIndexEndEditing = function (){
		if (systemWmenuIndexEditIndex == undefined){
			return true;
		}
		if ($('#systemWmenuIndexDatagrid').datagrid('validateRow', systemWmenuIndexEditIndex)){
						
			$('#systemWmenuIndexDatagrid').datagrid('endEdit', systemWmenuIndexEditIndex);

			var HomeKvInfo = [];
			HomeKvInfo.id = $('#systemWmenuIndexDatagrid').datagrid('getRows')[systemWmenuIndexEditIndex]['id'];
			HomeKvInfo.disable = $('#systemWmenuIndexDatagrid').datagrid('getRows')[systemWmenuIndexEditIndex]['disable'];
			HomeKvInfo.sortnum = $('#systemWmenuIndexDatagrid').datagrid('getRows')[systemWmenuIndexEditIndex]['sortnum'];
			
			systemWmenuIndexSave(HomeKvInfo,systemWmenuIndexEditIndex);
			systemWmenuIndexEditIndex = undefined;

			return true;
		} else {
			return false;
		}
	}
	var systemWmenuIndexOnClickRow = function (index,data){
		if (systemWmenuIndexEditIndex != index){
			if (systemWmenuIndexEditIndex == undefined){
				$('#systemWmenuIndexDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				systemWmenuIndexEditIndex = index;
			} else {	
				systemWmenuIndexEndEditing();			
				$('#systemWmenuIndexDatagrid').datagrid('refreshRow', systemWmenuIndexEditIndex).datagrid('endEdit', systemWmenuIndexEditIndex);
				$('#systemWmenuIndexDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				
				systemWmenuIndexEditIndex = index;
			}
			var cid = $('#systemWmenuIndexDatagrid').datagrid('getRows')[systemWmenuIndexEditIndex]['cid'];
			$("#systemWmenuIndexAreaCombobox").combobox("reload",BASEUSER+"/system/franchise/arealistForStreet?cid="+cid);
		}
	}
	var systemWmenuIndexAppend = function (){
		if (systemWmenuIndexEndEditing()){
			$('#systemWmenuIndexDatagrid').datagrid('appendRow',{status:'显示'});
			
			systemWmenuIndexEditIndex = $('#systemWmenuIndexDatagrid').datagrid('getRows').length-1;
			$('#systemWmenuIndexDatagrid').datagrid('selectRow', systemWmenuIndexEditIndex)
					.datagrid('beginEdit', systemWmenuIndexEditIndex);
		}
	}
	var systemWmenuIndexRemove = function (){
		if (systemWmenuIndexEditIndex == undefined){return}
		$('#systemWmenuIndexDatagrid').datagrid('cancelEdit', systemWmenuIndexEditIndex)
				.datagrid('deleteRow', systemWmenuIndexEditIndex);
		systemWmenuIndexEditIndex = undefined;
	}
	var systemWmenuIndexAccept = function (){
		systemWmenuIndexEndEditing();
	}
	var systemWmenuIndexReject = function(){
		$('#systemWmenuIndexDatagrid').datagrid('rejectChanges');
		systemWmenuIndexEditIndex = undefined;
	}
	var systemWmenuIndexGetChanges = function (){
		var rows = $('#systemWmenuIndexDatagrid').datagrid('getChanges','updated');
		alert(rows.length+' rows are changed!');
	}
	var systemWmenuIndexSave = function (HomeKvInfo,editIndex){
		if(HomeKvInfo.id){
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/home/kvupdateforlistedit",
				data:{"id":HomeKvInfo.id,"disable":HomeKvInfo.disable,"sortnum":HomeKvInfo.sortnum},
				dataType:"JSON",
				success:function(data){
					if(data.code==1){
						$('#systemWmenuIndexDatagrid').datagrid('acceptChanges').datagrid('reload',{});
					}else{
						$.messager.alert('系统消息',data.msg,'error');
						$('#systemWmenuIndexDatagrid').datagrid('acceptChanges').datagrid('reload',{});
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
			// 			$('#systemWmenuIndexDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}else{
			// 			$.messager.alert('系统消息',data.msg,'error');
			// 			$('#systemWmenuIndexDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}
			// 	}
			// });
		}
	}

	
</script>