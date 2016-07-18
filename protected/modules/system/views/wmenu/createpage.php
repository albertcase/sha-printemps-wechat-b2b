<table id="systemWmenuCreatepageDatagrid" class="easyui-datagrid" title="生成微信页面"
		data-options="
			iconCls: 'icon-edit',
			singleSelect: true,
			toolbar: '#systemWmenuCreatepageDatagridTb',
			url: '<?php echo Yii::app()->request->baseUrl; ?>/system/wmenu/pagelist',
			//onClickRow: systemWmenuCreatepageOnClickRow,
			pagination: true,
			pageSize:50,
			autoRowHeight:true,
			rownumbers: true,
			animate: true,
			onLoadSuccess:function(){$('#systemWmenuCreatepageDatagrid').datagrid('resize',{height:parseInt($('#tt .panel').css('height'))});systemWmenuCreatepageEditIndex = undefined;}
		">
	<thead>
		<tr>
			<th data-options="field:'id',width:70,
				formatter:function(value,row){
					return '<a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=openTab(\'编辑微信页面\',\'/wmenu/editpage?id='+row.id+'\')>编辑</a> | <a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=systemWmenuCreatepageDelete('+row.id+')>删除</a>';
			}">操作</th>
			<th data-options='field:"title",width:200'>标题</th>
			<th data-options='field:"img",width:200'>图片</th>
			<th data-options='field:"content",width:100'>文字</th>
			<th data-options='field:"createtime",width:500,formatter:function(value,row){
					return BASEUSER+"/weixin/page/"+row.id;
			}'>链接</th>
		</tr>
	</thead>
</table>

<div id="systemWmenuCreatepageDatagridTb" style="height:auto">
	<div style="margin-bottom:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="openTab('添加微信菜单','/wmenu/addpage')">新增</a>
	</div>
</div>


<script type="text/javascript">
	var systemWmenuCreatepageDelete = function (id){
		$.messager.confirm('系统消息', '确认删除该条信息吗？', function(r){
			if (r){
				$.ajax({
					type:"POST",
					global:false,
					url:BASEUSER+'/system/wmenu/createpagedelete',
					data:{"id":id},
					dataType:"JSON",
					success:function(data){					
						if(data.code==1){						
							$.messager.alert('系统消息',data.msg);
							$('#tt').tabs("select","生成微信页面").tabs("select","生成微信页面");
							$('#systemWmenuCreatepageDatagrid').datagrid('reload');
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
	var systemWmenuCreatepageEditIndex = undefined;
	var systemWmenuCreatepageEndEditing = function (){
		if (systemWmenuCreatepageEditIndex == undefined){
			return true;
		}
		if ($('#systemWmenuCreatepageDatagrid').datagrid('validateRow', systemWmenuCreatepageEditIndex)){
						
			$('#systemWmenuCreatepageDatagrid').datagrid('endEdit', systemWmenuCreatepageEditIndex);

			var HomeKvInfo = [];
			HomeKvInfo.id = $('#systemWmenuCreatepageDatagrid').datagrid('getRows')[systemWmenuCreatepageEditIndex]['id'];
			HomeKvInfo.disable = $('#systemWmenuCreatepageDatagrid').datagrid('getRows')[systemWmenuCreatepageEditIndex]['disable'];
			HomeKvInfo.sortnum = $('#systemWmenuCreatepageDatagrid').datagrid('getRows')[systemWmenuCreatepageEditIndex]['sortnum'];
			
			systemWmenuCreatepageSave(HomeKvInfo,systemWmenuCreatepageEditIndex);
			systemWmenuCreatepageEditIndex = undefined;

			return true;
		} else {
			return false;
		}
	}
	var systemWmenuCreatepageOnClickRow = function (index,data){
		if (systemWmenuCreatepageEditIndex != index){
			if (systemWmenuCreatepageEditIndex == undefined){
				$('#systemWmenuCreatepageDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				systemWmenuCreatepageEditIndex = index;
			} else {	
				systemWmenuCreatepageEndEditing();			
				$('#systemWmenuCreatepageDatagrid').datagrid('refreshRow', systemWmenuCreatepageEditIndex).datagrid('endEdit', systemWmenuCreatepageEditIndex);
				$('#systemWmenuCreatepageDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				
				systemWmenuCreatepageEditIndex = index;
			}
			var cid = $('#systemWmenuCreatepageDatagrid').datagrid('getRows')[systemWmenuCreatepageEditIndex]['cid'];
			$("#systemWmenuCreatepageAreaCombobox").combobox("reload",BASEUSER+"/system/franchise/arealistForStreet?cid="+cid);
		}
	}
	var systemWmenuCreatepageAppend = function (){
		if (systemWmenuCreatepageEndEditing()){
			$('#systemWmenuCreatepageDatagrid').datagrid('appendRow',{status:'显示'});
			
			systemWmenuCreatepageEditIndex = $('#systemWmenuCreatepageDatagrid').datagrid('getRows').length-1;
			$('#systemWmenuCreatepageDatagrid').datagrid('selectRow', systemWmenuCreatepageEditIndex)
					.datagrid('beginEdit', systemWmenuCreatepageEditIndex);
		}
	}
	var systemWmenuCreatepageRemove = function (){
		if (systemWmenuCreatepageEditIndex == undefined){return}
		$('#systemWmenuCreatepageDatagrid').datagrid('cancelEdit', systemWmenuCreatepageEditIndex)
				.datagrid('deleteRow', systemWmenuCreatepageEditIndex);
		systemWmenuCreatepageEditIndex = undefined;
	}
	var systemWmenuCreatepageAccept = function (){
		systemWmenuCreatepageEndEditing();
	}
	var systemWmenuCreatepageReject = function(){
		$('#systemWmenuCreatepageDatagrid').datagrid('rejectChanges');
		systemWmenuCreatepageEditIndex = undefined;
	}
	var systemWmenuCreatepageGetChanges = function (){
		var rows = $('#systemWmenuCreatepageDatagrid').datagrid('getChanges','updated');
		alert(rows.length+' rows are changed!');
	}
	var systemWmenuCreatepageSave = function (HomeKvInfo,editIndex){
		if(HomeKvInfo.id){
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/wmenu/kvupdateforlistedit",
				data:{"id":HomeKvInfo.id,"disable":HomeKvInfo.disable,"sortnum":HomeKvInfo.sortnum},
				dataType:"JSON",
				success:function(data){
					if(data.code==1){
						$('#systemWmenuCreatepageDatagrid').datagrid('acceptChanges').datagrid('reload',{});
					}else{
						$.messager.alert('系统消息',data.msg,'error');
						$('#systemWmenuCreatepageDatagrid').datagrid('acceptChanges').datagrid('reload',{});
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
			// 			$('#systemWmenuCreatepageDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}else{
			// 			$.messager.alert('系统消息',data.msg,'error');
			// 			$('#systemWmenuCreatepageDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}
			// 	}
			// });
		}
	}

	
</script>