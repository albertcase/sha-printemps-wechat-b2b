<table id="systemWmenuqrcodeDatagrid" class="easyui-datagrid" title="二维码列表"
		data-options="
			iconCls: 'icon-edit',
			singleSelect: true,
			toolbar: '#systemWmenuqrcodeDatagridTb',
			url: '<?php echo Yii::app()->request->baseUrl; ?>/system/wmenu/qrcodelist',
			//onClickRow: systemWmenuqrcodeOnClickRow,
			pagination: true,
			pageSize:50,
			autoRowHeight:true,
			rownumbers: true,
			animate: true,
			onLoadSuccess:function(){$('#systemWmenuqrcodeDatagrid').datagrid('resize',{height:parseInt($('#tt .panel').css('height'))});systemWmenuqrcodeEditIndex = undefined;}
		">
	<thead>
		<tr>
			<th data-options='field:"bak",width:100,formatter:function(value,row){
								if(!value){
									return "无";
								}else{
									return value;
								}
						}'>描述</th>
			<th data-options='field:"scene_id",width:30,formatter:function(value,row){
								if(!value){
									return "无";
								}else{
									return value;
								}
						}'>场景值</th>

			<th data-options='field:"ticket",width:600,formatter:function(value,row){
								if(!value){
									return "无";
								}else{
									return value;
								}
						}'>ticket</th>
			<th data-options='field:"image",width:100,formatter:function(value,row){
								if(row.ticket)
									return "<img src=\"https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket="+row.ticket+"\" width=\"100px\">";
								else
									return "暂无图片";
						}'>二维码</th>
			
			
		</tr>
	</thead>
</table>

<div id="systemWmenuqrcodeDatagridTb" style="height:auto">
	<div style="margin-bottom:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="openTab('添加二维码','/wmenu/addqrcode')">新增</a>
	</div>
</div>


<script type="text/javascript">
	var systemWmenuqrcodeDelete = function (id){
		$.messager.confirm('系统消息', '确认删除该条信息吗？', function(r){
			if (r){
				$.ajax({
					type:"POST",
					global:false,
					url:BASEUSER+'/system/wmenu/qrcodedelete',
					data:{"id":id},
					dataType:"JSON",
					success:function(data){					
						if(data.code==1){						
							$.messager.alert('系统消息',data.msg);							
							$('#systemWmenuqrcodeDatagrid').datagrid('reload');
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
	var systemWmenuqrcodeEditIndex = undefined;
	var systemWmenuqrcodeEndEditing = function (){
		if (systemWmenuqrcodeEditIndex == undefined){
			return true;
		}
		if ($('#systemWmenuqrcodeDatagrid').datagrid('validateRow', systemWmenuqrcodeEditIndex)){
						
			$('#systemWmenuqrcodeDatagrid').datagrid('endEdit', systemWmenuqrcodeEditIndex);

			var HomeKvInfo = [];
			HomeKvInfo.id = $('#systemWmenuqrcodeDatagrid').datagrid('getRows')[systemWmenuqrcodeEditIndex]['id'];
			HomeKvInfo.disable = $('#systemWmenuqrcodeDatagrid').datagrid('getRows')[systemWmenuqrcodeEditIndex]['disable'];
			HomeKvInfo.sortnum = $('#systemWmenuqrcodeDatagrid').datagrid('getRows')[systemWmenuqrcodeEditIndex]['sortnum'];
			
			systemWmenuqrcodeSave(HomeKvInfo,systemWmenuqrcodeEditIndex);
			systemWmenuqrcodeEditIndex = undefined;

			return true;
		} else {
			return false;
		}
	}
	var systemWmenuqrcodeOnClickRow = function (index,data){
		if (systemWmenuqrcodeEditIndex != index){
			if (systemWmenuqrcodeEditIndex == undefined){
				$('#systemWmenuqrcodeDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				systemWmenuqrcodeEditIndex = index;
			} else {	
				systemWmenuqrcodeEndEditing();			
				$('#systemWmenuqrcodeDatagrid').datagrid('refreshRow', systemWmenuqrcodeEditIndex).datagrid('endEdit', systemWmenuqrcodeEditIndex);
				$('#systemWmenuqrcodeDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				
				systemWmenuqrcodeEditIndex = index;
			}
			var cid = $('#systemWmenuqrcodeDatagrid').datagrid('getRows')[systemWmenuqrcodeEditIndex]['cid'];
			$("#systemWmenuqrcodeAreaCombobox").combobox("reload",BASEUSER+"/system/franchise/arealistForStreet?cid="+cid);
		}
	}
	var systemWmenuqrcodeAppend = function (){
		if (systemWmenuqrcodeEndEditing()){
			$('#systemWmenuqrcodeDatagrid').datagrid('appendRow',{status:'显示'});
			
			systemWmenuqrcodeEditIndex = $('#systemWmenuqrcodeDatagrid').datagrid('getRows').length-1;
			$('#systemWmenuqrcodeDatagrid').datagrid('selectRow', systemWmenuqrcodeEditIndex)
					.datagrid('beginEdit', systemWmenuqrcodeEditIndex);
		}
	}
	var systemWmenuqrcodeRemove = function (){
		if (systemWmenuqrcodeEditIndex == undefined){return}
		$('#systemWmenuqrcodeDatagrid').datagrid('cancelEdit', systemWmenuqrcodeEditIndex)
				.datagrid('deleteRow', systemWmenuqrcodeEditIndex);
		systemWmenuqrcodeEditIndex = undefined;
	}
	var systemWmenuqrcodeAccept = function (){
		systemWmenuqrcodeEndEditing();
	}
	var systemWmenuqrcodeReject = function(){
		$('#systemWmenuqrcodeDatagrid').datagrid('rejectChanges');
		systemWmenuqrcodeEditIndex = undefined;
	}
	var systemWmenuqrcodeGetChanges = function (){
		var rows = $('#systemWmenuqrcodeDatagrid').datagrid('getChanges','updated');
		alert(rows.length+' rows are changed!');
	}
	var systemWmenuqrcodeSave = function (HomeKvInfo,editIndex){
		if(HomeKvInfo.id){
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/home/kvupdateforlistedit",
				data:{"id":HomeKvInfo.id,"disable":HomeKvInfo.disable,"sortnum":HomeKvInfo.sortnum},
				dataType:"JSON",
				success:function(data){
					if(data.code==1){
						$('#systemWmenuqrcodeDatagrid').datagrid('acceptChanges').datagrid('reload',{});
					}else{
						$.messager.alert('系统消息',data.msg,'error');
						$('#systemWmenuqrcodeDatagrid').datagrid('acceptChanges').datagrid('reload',{});
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
			// 			$('#systemWmenuqrcodeDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}else{
			// 			$.messager.alert('系统消息',data.msg,'error');
			// 			$('#systemWmenuqrcodeDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}
			// 	}
			// });
		}
	}

	
</script>