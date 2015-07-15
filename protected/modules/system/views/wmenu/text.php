<table id="systemWmenuTextDatagrid" class="easyui-datagrid" title="微信文章列表"
		data-options="
			iconCls: 'icon-edit',
			singleSelect: true,
			toolbar: '#systemWmenuTextDatagridTb',
			url: '<?php echo Yii::app()->request->baseUrl; ?>/system/wmenu/textlist',
			//onClickRow: systemWmenuTextOnClickRow,
			pagination: true,
			pageSize:50,
			autoRowHeight:true,
			rownumbers: true,
			animate: true,
			onLoadSuccess:function(){$('#systemWmenuTextDatagrid').datagrid('resize',{height:parseInt($('#tt .panel').css('height'))});systemWmenuTextEditIndex = undefined;}
		">
	<thead>
		<tr>
			<th data-options="field:'id',width:70,
				formatter:function(value,row){
					return '<a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=openTab(\'编辑微信文章\',\'/wmenu/textedit?id='+row.id+'\')>编辑</a> | <a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=systemWmenuTextDelete('+row.id+')>删除</a>';
			}">操作</th>
			<th data-options='field:"dt",width:200'>日期</th>
			<th data-options='field:"title",width:200'>名称</th>
			<th data-options='field:"pic",width:200'>封面</th>
			<th data-options='field:"content",width:200'>描述</th>
			<th data-options='field:"url",width:200'>链接</th>
		</tr>
	</thead>
</table>

<div id="systemWmenuTextDatagridTb" style="height:auto">
	<div style="margin-bottom:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="openTab('添加微信文章','/wmenu/textadd')">新增</a>
	</div>
</div>


<script type="text/javascript">
	var systemWmenuTextDelete = function (id){
		$.messager.confirm('系统消息', '确认删除该条信息吗？', function(r){
			if (r){
				$.ajax({
					type:"POST",
					global:false,
					url:BASEUSER+'/system/wmenu/textDelete',
					data:{"id":id},
					dataType:"JSON",
					success:function(data){					
						if(data.code==1){						
							$.messager.alert('系统消息',data.msg);
							$('#tt').tabs("select","微信文章列表").tabs("select","微信文章列表");
							$('#systemWmenuTextDatagrid').datagrid('reload');
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
	var systemWmenuTextEditIndex = undefined;
	var systemWmenuTextEndEditing = function (){
		if (systemWmenuTextEditIndex == undefined){
			return true;
		}
		if ($('#systemWmenuTextDatagrid').datagrid('validateRow', systemWmenuTextEditIndex)){
						
			$('#systemWmenuTextDatagrid').datagrid('endEdit', systemWmenuTextEditIndex);

			var HomeKvInfo = [];
			HomeKvInfo.id = $('#systemWmenuTextDatagrid').datagrid('getRows')[systemWmenuTextEditIndex]['id'];
			HomeKvInfo.disable = $('#systemWmenuTextDatagrid').datagrid('getRows')[systemWmenuTextEditIndex]['disable'];
			HomeKvInfo.sortnum = $('#systemWmenuTextDatagrid').datagrid('getRows')[systemWmenuTextEditIndex]['sortnum'];
			
			systemWmenuTextSave(HomeKvInfo,systemWmenuTextEditIndex);
			systemWmenuTextEditIndex = undefined;

			return true;
		} else {
			return false;
		}
	}
	var systemWmenuTextOnClickRow = function (index,data){
		if (systemWmenuTextEditIndex != index){
			if (systemWmenuTextEditIndex == undefined){
				$('#systemWmenuTextDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				systemWmenuTextEditIndex = index;
			} else {	
				systemWmenuTextEndEditing();			
				$('#systemWmenuTextDatagrid').datagrid('refreshRow', systemWmenuTextEditIndex).datagrid('endEdit', systemWmenuTextEditIndex);
				$('#systemWmenuTextDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				
				systemWmenuTextEditIndex = index;
			}
			var cid = $('#systemWmenuTextDatagrid').datagrid('getRows')[systemWmenuTextEditIndex]['cid'];
			$("#systemWmenuTextAreaCombobox").combobox("reload",BASEUSER+"/system/franchise/arealistForStreet?cid="+cid);
		}
	}
	var systemWmenuTextAppend = function (){
		if (systemWmenuTextEndEditing()){
			$('#systemWmenuTextDatagrid').datagrid('appendRow',{status:'显示'});
			
			systemWmenuTextEditIndex = $('#systemWmenuTextDatagrid').datagrid('getRows').length-1;
			$('#systemWmenuTextDatagrid').datagrid('selectRow', systemWmenuTextEditIndex)
					.datagrid('beginEdit', systemWmenuTextEditIndex);
		}
	}
	var systemWmenuTextRemove = function (){
		if (systemWmenuTextEditIndex == undefined){return}
		$('#systemWmenuTextDatagrid').datagrid('cancelEdit', systemWmenuTextEditIndex)
				.datagrid('deleteRow', systemWmenuTextEditIndex);
		systemWmenuTextEditIndex = undefined;
	}
	var systemWmenuTextAccept = function (){
		systemWmenuTextEndEditing();
	}
	var systemWmenuTextReject = function(){
		$('#systemWmenuTextDatagrid').datagrid('rejectChanges');
		systemWmenuTextEditIndex = undefined;
	}
	var systemWmenuTextGetChanges = function (){
		var rows = $('#systemWmenuTextDatagrid').datagrid('getChanges','updated');
		alert(rows.length+' rows are changed!');
	}
	var systemWmenuTextSave = function (HomeKvInfo,editIndex){
		if(HomeKvInfo.id){
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/home/kvupdateforlistedit",
				data:{"id":HomeKvInfo.id,"disable":HomeKvInfo.disable,"sortnum":HomeKvInfo.sortnum},
				dataType:"JSON",
				success:function(data){
					if(data.code==1){
						$('#systemWmenuTextDatagrid').datagrid('acceptChanges').datagrid('reload',{});
					}else{
						$.messager.alert('系统消息',data.msg,'error');
						$('#systemWmenuTextDatagrid').datagrid('acceptChanges').datagrid('reload',{});
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
			// 			$('#systemWmenuTextDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}else{
			// 			$.messager.alert('系统消息',data.msg,'error');
			// 			$('#systemWmenuTextDatagrid').datagrid('acceptChanges').datagrid('reload',{});
			// 		}
			// 	}
			// });
		}
	}

	
</script>