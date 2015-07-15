<table id="systemUserDatagrid" class="easyui-datagrid" title="系统用户管理"
		data-options="
			iconCls: 'icon-edit',
			singleSelect: true,
			toolbar: '#systemUserDatagridTb',
			url: '<?php echo Yii::app()->request->baseUrl; ?>/system/sysuser/list',
			onClickRow: systemUserOnClickRow,
			pagination: true,
			pageSize:50,
			autoRowHeight:true,
			rownumbers: true,
			animate: true,
			onLoadSuccess:function(){$('#systemUserDatagrid').datagrid('resize',{height:parseInt($('#tt .panel').css('height'))});}
		">
	<thead>
		<tr>
			<th data-options="field:'id',width:40,formatter:function(value,row){
					return '<a href=\'javascript:void(0)\' class=\'easyui-linkbutton\' onclick=systemUserDelete('+row.id+')>删除</a>';
			}">操作</th>
			<th data-options="field:'uname',width:100,editor:{type:'text',options:{required:true}}">用户名</th>
			<th data-options="field:'rname',width:100,editor:'text'">真实姓名</th>
			<th data-options="field:'password',width:100,editor:'text',
					formatter:function(value,row){
						return row.password;
					}">密码</th>
			<th data-options="field:'email',width:100,editor:{
						type:'text',
						options:{
							required:true,
							validType:'email'
						}
					}">Email</th>
			<th data-options='field:"cname",width:300,
					formatter:function(value,row){
						return row.cname;
					},
					editor:{
						type:"combobox",
						options:{
							valueField:"cname",
							textField:"cname",
							data:<?php echo $cityList;?>,
							required:true,
							multiple:true,
							onSelect:function(rec){								
								var str=$(this).combobox("getText")
								var strAry=str.split(",");
								if(strAry[0]==""){
									str=str.substr(1);
									$(this).combobox("setText",str)
								}														
							},
							onUnselect:function(rec){
								var str=$(this).combobox("getText")
								var strAry=str.split(",");
								if(strAry[0]==""){
									str=str.substr(1);
									$(this).combobox("setText",str)
								}
							}

						}

					}'>所属城市</th>
			<th data-options='field:"pname",width:200,
					formatter:function(value,row){
						return row.pname;
					},
					editor:{
						type:"combobox",
						options:{
							valueField:"pname",
							textField:"pname",
							data:<?php echo $permissions;?>,
							multiple:true,
							required:true,
							onSelect:function(rec){								
								var str=$(this).combobox("getText")
								var strAry=str.split(",");
								if(strAry[0]==""){
									str=str.substr(1);
									$(this).combobox("setText",str)
								}														
							},
							onUnselect:function(rec){
								var str=$(this).combobox("getText")
								var strAry=str.split(",");
								if(strAry[0]==""){
									str=str.substr(1);
									$(this).combobox("setText",str)
								}
							}
						}
					}'>权限</th>			
			<th data-options="field:'status',width:60,editor:{type:'checkbox',options:{on:'激活',off:'锁定'}}">状态</th>
			<th data-options="field:'ouname',width:60">操作用户</th>
			<th data-options="field:'createtime',width:120">操作时间</th>
			
		</tr>
	</thead>
</table>

<div id="systemUserDatagridTb" style="height:auto">
		<div style="margin-bottom:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="systemUserAppend()">新增</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="systemUserAccept()">保存</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="systemUserReject()">取消</a>		
	</div>
	<div>
		权限: 
		<input class="easyui-combobox" id="systemUserPermissionsForSearch" name="systemUserPermissionsForSearch" data-options='valueField:"id",textField:"pname",data:<?php echo $permissions;?>' >
		真实姓名: 
		<input type="text" class="easyui-text" id="systemUserNameForSearch" name="systemUserNameForSearch">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="systemUserSearch()">搜索</a>
	</div>
</div>

<script type="text/javascript">
	var systemUserDelete = function (id){
			$.messager.confirm('系统消息', '确认删除该条信息吗？', function(r){
				if (r){
					$.ajax({
						type:"POST",
						global:false,
						url:BASEUSER+'/system/sysuser/delete',
						data:{"id":id},
						dataType:"JSON",
						success:function(data){					
							if(data.code==1){						
								$.messager.alert('系统消息',data.msg);
								$('#systemUserDatagrid').datagrid('reload');
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
	var systemUserEditIndex = undefined;
	var systemUserEndEditing = function (){
		if (systemUserEditIndex == undefined){
			return true;
		}
		if ($('#systemUserDatagrid').datagrid('validateRow', systemUserEditIndex)){
			var edDepartment = $('#systemUserDatagrid').datagrid('getEditor', {index:systemUserEditIndex,field:'cname'});
			var edPermissions = $('#systemUserDatagrid').datagrid('getEditor', {index:systemUserEditIndex,field:'pname'});
			var departmentName = $(edDepartment.target).combotree('getText');
			var permissionsName = $(edPermissions.target).combobox('getText');

			$('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['cname'] = departmentName;
			$('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['pname'] = permissionsName;
			$('#systemUserDatagrid').datagrid('endEdit', systemUserEditIndex);

			var userInfo = [];
			userInfo.uid = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['id'];
			//userInfo.cid = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['cid'];
			userInfo.cname = departmentName;
			userInfo.email = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['email'];
			userInfo.pid = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['pid'];
			userInfo.pname = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['pname'];
			userInfo.uname = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['uname'];
			userInfo.rname = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['rname'];
			userInfo.password = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['password'];
			userInfo.status = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['status'];
			
			systemUserSave(userInfo,systemUserEditIndex);
			systemUserEditIndex = undefined;

			return true;
		} else {
			return false;
		}
	}

	var systemUserEndEditing2 = function (){
		if (systemUserEditIndex == undefined){
			return true;
		}
		if ($('#systemUserDatagrid').datagrid('validateRow', systemUserEditIndex)){
			var edDepartment = $('#systemUserDatagrid').datagrid('getEditor', {index:systemUserEditIndex,field:'cname'});
			var edPermissions = $('#systemUserDatagrid').datagrid('getEditor', {index:systemUserEditIndex,field:'pid'});
			var departmentName = $(edDepartment.target).combotree('getText');
			var permissionsName = $(edPermissions.target).combobox('getText');

			$('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['cname'] = departmentName;
			$('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['pname'] = permissionsName;
			$('#systemUserDatagrid').datagrid('endEdit', systemUserEditIndex);

			var userInfo = [];
			userInfo.uid = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['id'];
			//userInfo.cid = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['cid'];
			userInfo.cname = departmentName;
			userInfo.email = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['email'];
			userInfo.pid = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['pid'];
			userInfo.pname = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['pname'];
			userInfo.uname = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['uname'];
			userInfo.rname = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['rname'];
			userInfo.password = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['password'];
			userInfo.status = $('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['status'];
			
			systemUserSave2(userInfo,systemUserEditIndex);
			systemUserEditIndex = undefined;

			return true;
		} else {
			return false;
		}
	}
	var systemUserOnClickRow = function (index,data){
		//systemUserEndEditing()
		data.password='';		
		if (systemUserEditIndex != index){			
			if (systemUserEditIndex == undefined){
				$('#systemUserDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				
				systemUserEditIndex = index;
			} else {
				
				//$('#systemUserDatagrid').datagrid('selectRow', systemUserEditIndex);
				$('#systemUserDatagrid').datagrid('getRows')[systemUserEditIndex]['password'] = '******';
				systemUserEndEditing()
				$('#systemUserDatagrid').datagrid('refreshRow', systemUserEditIndex).datagrid('endEdit', systemUserEditIndex);
				$('#systemUserDatagrid').datagrid('selectRow', index)
					.datagrid('beginEdit', index);
				
				systemUserEditIndex = index;
			}

			var edDepartment = $('#systemUserDatagrid').datagrid('getEditor', {index:systemUserEditIndex,field:'cname'});	
			//alert($(edDepartment.target).combobox("getValues"))
			var str=$(edDepartment.target).combobox("getText");
			$(edDepartment.target).combobox("setValues",str.split(","));			
		}
	}
	var systemUserAppend = function (){
		if (systemUserEndEditing()){
			$('#systemUserDatagrid').datagrid('appendRow',{status:'激活'});
			systemUserEditIndex = $('#systemUserDatagrid').datagrid('getRows').length-1;
			$('#systemUserDatagrid').datagrid('selectRow', systemUserEditIndex)
					.datagrid('beginEdit', systemUserEditIndex);
		}
	}
	var systemUserRemove = function (){
		if (systemUserEditIndex == undefined){return}
		$('#systemUserDatagrid').datagrid('cancelEdit', systemUserEditIndex)
				.datagrid('deleteRow', systemUserEditIndex);
		systemUserEditIndex = undefined;

	}
	var systemUserAccept = function (){
		systemUserEndEditing();
	}
	var systemUserReject = function(){
		$('#systemUserDatagrid').datagrid('rejectChanges');
		systemUserEditIndex = undefined;
	}
	var systemUserGetChanges = function (){
		var rows = $('#systemUserDatagrid').datagrid('getChanges','updated');
		alert(rows.length+' rows are changed!');
	}
	var systemUserSave = function (userInfo,editIndex){
		if(userInfo.uid){
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/sysuser/update",
				data:{"uid":userInfo.uid,"cname":userInfo.cname,"pid":userInfo.pid,"pname":userInfo.pname,"uname":userInfo.uname,"rname":userInfo.rname,"password":userInfo.password,"status":userInfo.status,email:userInfo.email},
				dataType:"JSON",
				success:function(data){
					if(data.code==1){
						$('#systemUserDatagrid').datagrid('reload');
					}else{
						$.messager.alert('系统消息',data.msg,'error');
					}
				}
			});
		}else{
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/sysuser/add",
				data:{"cname":userInfo.cname,"pid":userInfo.pid,"pname":userInfo.pname,"uname":userInfo.uname,"rname":userInfo.rname,"password":userInfo.password,"status":userInfo.status,email:userInfo.email},
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$('#systemUserDatagrid').datagrid('reload');
					}else{
						$.messager.alert('系统消息',data.msg,'error');
					}
				}
			});
		}
	}

    var systemUserSave2 = function (userInfo,editIndex){
		if(userInfo.uid){
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/sysuser/update",
				data:{"uid":userInfo.uid,"cname":userInfo.cname,"pid":userInfo.pid,"pname":userInfo.pname,"uname":userInfo.uname,"rname":userInfo.rname,"password":userInfo.password,"status":userInfo.status,email:userInfo.email},
				dataType:"JSON",
				success:function(data){
					if(data.code==1){
						//$('#systemUserDatagrid').datagrid('reload');
					}else{
						//$.messager.alert('系统消息',data.msg,'error');
					}
				}
			});
		}else{
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+"/system/sysuser/add",
				data:{"cname":userInfo.cname,"pid":userInfo.pid,"pname":userInfo.pname,"uname":userInfo.uname,"rname":userInfo.rname,"password":userInfo.password,"status":userInfo.status,email:userInfo.email},
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						//$('#systemUserDatagrid').datagrid('reload');
					}else{
						//$.messager.alert('系统消息',data.msg,'error');
					}
				}
			});
		}
	}
	var systemUserSearch = function (){
		$('#systemUserDatagrid').datagrid('load',{
			permissions: $("#systemUserPermissionsForSearch").combobox('getValue'),
			userName:$("#systemUserNameForSearch").val()
		});
	}

	$(document).ready(function(){
		//$('#systemUserDepartmentForSearch').combotree('setValue', '1');
	});
</script>