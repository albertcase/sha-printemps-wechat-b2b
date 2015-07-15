<table id="systemWmenustoreDatagrid" class="easyui-datagrid" title="门店列表"
		data-options="
			iconCls: 'icon-edit',
			singleSelect: true,
			toolbar: '#systemWmenustoreDatagridTb',
			url: '<?php echo Yii::app()->request->baseUrl; ?>/system/wmenu/storelist',
			//onClickRow: systemWmenustoreOnClickRow,
			pagination: true,
			pageSize:50,
			autoRowHeight:true,
			rownumbers: true,
			animate: true,
			onLoadSuccess:function(){$('#systemWmenustoreDatagrid').datagrid('resize',{height:parseInt($('#tt .panel').css('height'))});systemWmenustoreEditIndex = undefined;}
		">
	<thead>
		<tr>
			<th data-options="field:'id',width:120,
				formatter:function(value,row){
					return '<a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=openTab(\'编辑门店\',\'/wmenu/editstore?id='+row.id+'\')>编辑门店</a> | <a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=systemWmenustoreDelete('+row.id+')>删除门店</a>';
			}">操作</th>
			<th data-options='field:"name",width:200,formatter:function(value,row){
								if(!value){
									return "无";
								}else{
									return value;
								}
						}'>门店名称</th>
			<th data-options='field:"address",width:200,formatter:function(value,row){
								if(!value){
									return "无";
								}else{
									return value;
								}
						}'>地址</th>

			<th data-options='field:"telphone",width:100,formatter:function(value,row){
								if(!value){
									return "无";
								}else{
									return value;
								}
						}'>电话</th>
			<th data-options='field:"picUrl",width:100,formatter:function(value,row){
								if(row.picUrl)
									return "<img src=\""+row.picUrl+"\" width=\"100px\">";
								else
									return "暂无图片";
						}'>图片</th>
			<th data-options='field:"lat",width:50,formatter:function(value,row){
								if(!value){
									return "无";
								}else{
									return value;
								}
						}'>经度</th>
			<th data-options='field:"lng",width:50,formatter:function(value,row){
								if(!value){
									return "无";
								}else{
									return value;
								}
						}'>纬度</th>
			<th data-options='field:"mapUrl",width:100,formatter:function(value,row){
								if(row.mapUrl)
									return "<img src=\""+row.mapUrl+"\" width=\"100px\">";
								else
									return "暂无图片";
						}'>地图</th>
			
			
		</tr>
	</thead>
</table>

<div id="systemWmenustoreDatagridTb" style="height:auto">
	<div style="margin-bottom:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="openTab('添加二维码','/wmenu/addstore')">新增</a>
	</div>
</div>


<script type="text/javascript">
	var systemWmenustoreDelete = function (id){
		$.messager.confirm('系统消息', '确认删除该条信息吗？', function(r){
			if (r){
				$.ajax({
					type:"POST",
					global:false,
					url:BASEUSER+'/system/wmenu/storedelete',
					data:{"id":id},
					dataType:"JSON",
					success:function(data){					
						if(data.code==1){						
							$.messager.alert('系统消息',data.msg);							
							$('#systemWmenustoreDatagrid').datagrid('reload');
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
	var systemWmenustoreEditIndex = undefined;
	var systemWmenustoreEndEditing = function (){
		if (systemWmenustoreEditIndex == undefined){
			return true;
		}
		if ($('#systemWmenustoreDatagrid').datagrid('validateRow', systemWmenustoreEditIndex)){
						
			$('#systemWmenustoreDatagrid').datagrid('endEdit', systemWmenustoreEditIndex);

			var HomeKvInfo = [];
			HomeKvInfo.id = $('#systemWmenustoreDatagrid').datagrid('getRows')[systemWmenustoreEditIndex]['id'];
			HomeKvInfo.disable = $('#systemWmenustoreDatagrid').datagrid('getRows')[systemWmenustoreEditIndex]['disable'];
			HomeKvInfo.sortnum = $('#systemWmenustoreDatagrid').datagrid('getRows')[systemWmenustoreEditIndex]['sortnum'];
			
			systemWmenustoreSave(HomeKvInfo,systemWmenustoreEditIndex);
			systemWmenustoreEditIndex = undefined;

			return true;
		} else {
			return false;
		}
	}
	var systemWmenustoreOnClickRow = function (index,data){
		if (systemWmenustoreEditIndex != index){
			if (systemWmenustoreEditIndex == undefined){
				$('#systemWmenustoreDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				systemWmenustoreEditIndex = index;
			} else {	
				systemWmenustoreEndEditing();			
				$('#systemWmenustoreDatagrid').datagrid('refreshRow', systemWmenustoreEditIndex).datagrid('endEdit', systemWmenustoreEditIndex);
				$('#systemWmenustoreDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				
				systemWmenustoreEditIndex = index;
			}
			var cid = $('#systemWmenustoreDatagrid').datagrid('getRows')[systemWmenustoreEditIndex]['cid'];
			$("#systemWmenustoreAreaCombobox").combobox("reload",BASEUSER+"/system/franchise/arealistForStreet?cid="+cid);
		}
	}
	var systemWmenustoreAppend = function (){
		if (systemWmenustoreEndEditing()){
			$('#systemWmenustoreDatagrid').datagrid('appendRow',{status:'显示'});
			
			systemWmenustoreEditIndex = $('#systemWmenustoreDatagrid').datagrid('getRows').length-1;
			$('#systemWmenustoreDatagrid').datagrid('selectRow', systemWmenustoreEditIndex)
					.datagrid('beginEdit', systemWmenustoreEditIndex);
		}
	}
	var systemWmenustoreRemove = function (){
		if (systemWmenustoreEditIndex == undefined){return}
		$('#systemWmenustoreDatagrid').datagrid('cancelEdit', systemWmenustoreEditIndex)
				.datagrid('deleteRow', systemWmenustoreEditIndex);
		systemWmenustoreEditIndex = undefined;
	}
	var systemWmenustoreAccept = function (){
		systemWmenustoreEndEditing();
	}
	var systemWmenustoreReject = function(){
		$('#systemWmenustoreDatagrid').datagrid('rejectChanges');
		systemWmenustoreEditIndex = undefined;
	}
	var systemWmenustoreGetChanges = function (){
		var rows = $('#systemWmenustoreDatagrid').datagrid('getChanges','updated');
		alert(rows.length+' rows are changed!');
	}
	var systemWmenustoreSave = function (HomeKvInfo,editIndex){
		if(HomeKvInfo.id){
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/home/kvupdateforlistedit",
				data:{"id":HomeKvInfo.id,"disable":HomeKvInfo.disable,"sortnum":HomeKvInfo.sortnum},
				dataType:"JSON",
				success:function(data){
					if(data.code==1){
						$('#systemWmenustoreDatagrid').datagrid('acceptChanges').datagrid('reload',{});
					}else{
						$.messager.alert('系统消息',data.msg,'error');
						$('#systemWmenustoreDatagrid').datagrid('acceptChanges').datagrid('reload',{});
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
			// 			$('#systemWmenustoreDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}else{
			// 			$.messager.alert('系统消息',data.msg,'error');
			// 			$('#systemWmenustoreDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}
			// 	}
			// });
		}
	}

	
</script>