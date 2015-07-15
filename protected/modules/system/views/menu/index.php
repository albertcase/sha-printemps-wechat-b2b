<table id="SystemMenuGrid" title="菜单列表" class="easyui-treegrid"
			 data-options="
				iconCls: 'icon-edit',
				rownumbers: true,
				animate: true,
				collapsible: false,
				fitColumns: true,
				url: '<?php echo Yii::app()->request->baseUrl; ?>/system/menu/listForView',
				idField: 'id',
				treeField: 'text',
				autoRowHeight:true,
				//pagination: true,
				//pageSize:50,
				onLoadSuccess:function(){$('#SystemMenuGrid').treegrid('resize',{height:parseInt($('#tt .panel').css('height'))});},
				onLoadError:function(){$.messager.alert('系统消息','系统数据加载错误','error');},
				locales:'zh_CN'">
	<thead>
		<tr>
			<th data-options="field:'text',editor:'text'" rowspan="2" width="20%">名称</th>
			<th data-options="field:'url',editor:'text'" width="30%">url</th>
			<th data-options="field:'sort',editor:'text'" width="5%">排序</th>			
			<th data-options="field:'id',editor:'button',
				formatter:function(value){
					if(value==1){
						return '<a onclick=\'SystemMenuAppend('+value+')\'  class=\'easyui-linkbutton l-btn l-btn-plain\' href=\'javascript:void(0)\' ><span class=\'l-btn-left\'><span class=\'l-btn-text icon-add l-btn-icon-left\'>添加子菜单</span></span></a>';
					}else{
						return '<a id=\'SystemMenuNodeEdit_'+value+'\' onclick=\'SystemMenuEditNode('+value+')\' class=\'easyui-linkbutton l-btn l-btn-plain\' href=\'javascript:void(0)\' ><span class=\'l-btn-left\'><span class=\'l-btn-text icon-edit l-btn-icon-left\'>编辑</span></span></a><a onclick=\'SystemMenuRemove('+value+')\'  class=\'easyui-linkbutton l-btn l-btn-plain\' href=\'javascript:void(0)\' ><span class=\'l-btn-left\'><span class=\'l-btn-text icon-remove l-btn-icon-left\'>删除</span></span></a><a onclick=\'SystemMenuAppend('+value+')\'  class=\'easyui-linkbutton l-btn l-btn-plain\' href=\'javascript:void(0)\' ><span class=\'l-btn-left\'><span class=\'l-btn-text icon-add l-btn-icon-left\'>添加子菜单</span></span></a>';
					}
	            }" width="45%">操作</th>
		</tr>
	</thead>
</table>

<script type="text/javascript">
<!--

var SystemMenuCodeIndex = 10000;
function SystemMenuEditNode(nid,type){
	if(type=='add'){
		var obj = $("#SystemMenuNodeEdit_"+nid);
		var strA = '<a onclick=\'SystemMenuSaveNode('+nid+')\'  class=\'easyui-linkbutton l-btn l-btn-plain\' href=\'javascript:void(0)\' ><span class=\'l-btn-left\'><span class=\'l-btn-text icon-save l-btn-icon-left\'>保存</span></span></a><a onclick=\'SystemMenuCancelNode('+nid+')\'  class=\'easyui-linkbutton l-btn l-btn-plain\' href=\'javascript:void(0)\' ><span class=\'l-btn-left\'><span class=\'l-btn-text icon-cancel l-btn-icon-left\'>取消</span></span></a>';
		obj.after(strA);
		$('#SystemMenuGrid').treegrid('beginEdit',nid);
	}else{
		//var node = $('#SystemMenuGrid').treegrid('getSelected');alert(node);
		var obj = $("#SystemMenuNodeEdit_"+nid)
		if (nid){
			var strA = '<a onclick=\'SystemMenuSaveNode('+nid+')\'  class=\'easyui-linkbutton l-btn l-btn-plain\' href=\'javascript:void(0)\' ><span class=\'l-btn-left\'><span class=\'l-btn-text icon-save l-btn-icon-left\'>保存</span></span></a><a onclick=\'SystemMenuCancelNode('+nid+')\'  class=\'easyui-linkbutton l-btn l-btn-plain\' href=\'javascript:void(0)\' ><span class=\'l-btn-left\'><span class=\'l-btn-text icon-cancel l-btn-icon-left\'>取消</span></span></a>';
			obj.after(strA);
			$('#SystemMenuGrid').treegrid('beginEdit',nid);
		}
	}
}
function SystemMenuSaveNode(nid){
	$('#SystemMenuGrid').treegrid('select',nid);
	var node = $('#SystemMenuGrid').treegrid('getSelected');
	if (node){
		$('#SystemMenuGrid').treegrid('endEdit',node.id);
		if(node.id>=SystemMenuCodeIndex){
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/menu/add",
				data:"pid="+node._parentId+"&name="+node.text+"&url="+node.url+"&sort="+node.sort,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){
						$('#SystemMenuGrid').treegrid('reload');
						$('#sysMenuTree').tree('reload');
					}else{
						$.messager.alert('系统消息',data.msg,'error');
					}
				}
			});
		}else{
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/menu/update",
				data:"id="+node.id+"&name="+node.text+"&url="+node.url+"&sort="+node.sort,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){
						$('#SystemMenuGrid').treegrid('reload');
						$('#sysMenuTree').tree('reload');
					}else{
						$.messager.alert('系统消息',data.msg,'error');
					}
				}
			});
		}
	}
}
function SystemMenuCancelNode(nid){
	$('#SystemMenuGrid').treegrid('select',nid);
	var node = $('#SystemMenuGrid').treegrid('getSelected');
	if (node){
		$('#SystemMenuGrid').treegrid('cancelEdit',node.id);
	}
}
function SystemMenuRemove(nid){
	$('#SystemMenuGrid').treegrid('select',nid);
	var node = $('#SystemMenuGrid').treegrid('getSelected');
	if (node){
		var nodes = $('#SystemMenuGrid').treegrid('getChildren', node.id);
		var s = null;
		
		var strMsg = '确定删除？';
		if(nodes.length>0){
			strMsg = '该菜单所有子菜单将全部删除，确定删除？';
		}
		$.messager.confirm('确定删除？', strMsg, function(r){
			if (r){
				$.ajax({
					type:"POST",
					global:false,
					url:BASEUSER+"/system/menu/delete",
					data:"nid="+node.id,
					dataType:"JSON",
					success:function(data){					
						if(data.code==1){
							$('#SystemMenuGrid').treegrid('remove', node.id);
							$('#sysMenuTree').tree('reload');
						}else{
							$.messager.alert('系统消息',data.msg,'error');
						}
					}
				});
			}
		});
		
	}
}

function SystemMenuAppend(nid){
	SystemMenuCodeIndex++;
	var data = [{
		id:SystemMenuCodeIndex,
		text: '请编辑本菜单',
		url: '',
		sort: 999999
	}];
	var node = $('#SystemMenuGrid').treegrid('getSelected');
	$('#SystemMenuGrid').treegrid('append', {
		parent: (nid ? nid : null),
		data: data
	});
	//$('#SystemMenuGrid').treegrid('beginEdit',SystemMenuCodeIndex);
	SystemMenuEditNode(SystemMenuCodeIndex,'add');
}
//-->
</script>

