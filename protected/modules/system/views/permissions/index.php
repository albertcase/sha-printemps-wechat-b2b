<table id="SystemPermissionsGrid" class="easyui-datagrid" title="权限列表" data-options="
				singleSelect:true,
				collapsible:true,
				iconCls: 'icon-edit',
				rownumbers: true,
				animate: true,
				collapsible: false,
				fitColumns: true,
				url: '<?php echo Yii::app()->request->baseUrl; ?>/system/permissions/list',
				idField: 'id',
				treeField: 'text',
				autoRowHeight:true,
				pagination: true,
				pageSize:50,
				toolbar: '#SystemPermissionsTb',
				onClickRow: SystemPermissionsOnClickRow,
				onLoadSuccess:function(){$('#SystemPermissionsGrid').treegrid('resize',{height:parseInt($('#tt .panel').css('height'))});},
				onLoadError:function(){$.messager.alert('系统消息','系统数据加载错误','error');},
				locales:'zh_CN'">
		<thead>
			<tr>
				<th data-options="field:'id',width:60,align:'center',toolbar: '#tb',
					formatter:function(value){
						return '<a onclick=\'SystemPermissionsEdit('+value+')\'  class=\'easyui-linkbutton l-btn l-btn-plain\' href=\'javascript:void(0)\' ><span class=\'l-btn-left\'><span class=\'l-btn-text icon-edit l-btn-icon-left\'>编辑</span></span></a>'}"
	            >操作</th>
				<th data-options="field:'rname',width:80">权限名称</th>
				<th data-options="field:'uname',width:100">操作用户</th>
				
			</tr>
		</thead>
	</table>
	<div id="SystemPermissionsTb" style="height:auto">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="SystemPermissionsAppend()">新增</a>
	</div>

<script type="text/javascript">
<!--
	var SystemPermissionsEditId = undefined;

	var SystemPermissionsAppend = function (nid){
		openTab('添加权限','permissions/padd');
	}

	var SystemPermissionsEdit = function (nid){
		if(!nid){
			$.messager.alert('系统消息','请选择您要编辑的行','warning');
			return false;
		}
		openTab('编辑权限','permissions/pedit?id='+nid);
		
	}

	var SystemPermissionsOnClickRow = function (index,data){
		
	}
//-->
</script>

