<div style="padding:10px 0 10px 60px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'permissions-form',
	'enableAjaxValidation'=>false,
)); ?>
		<table>
			<tr>
				<td>权限名称:</td>
				<td><input class="easyui-validatebox" type="text" name="SystemPermissionsEditRname" id="SystemPermissionsEditRname" data-options="required:true" value="<?php echo $permissions['rname']?>"></input></td>
			</tr>
			<tr>
				<td>权限菜单:</td>
				<td>
					<ul id="SystemPermissionsEditTree" class="ztree"></ul>
				</td>
			</tr>
		</table>
	</form>
	</div>
	<div style="text-align:center;padding:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" onclick="SystemPermissionsEditSubmit()">保存</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" onclick="SystemPermissionsEditcancel()">取消</a>
	</div>
</div>

<?php $this->endWidget(); ?>

<SCRIPT type="text/javascript">
<!--

var SystemPermissionsEditSetting = {
	view: {
		selectedMulti: true
	},
	check: {
		enable: true
	},
	data: {
		simpleData: {
			enable: true
		}
	},
	callback: {
		onCheck: SystemPermissionsEditOnCheck
	}
};

var SystemPermissionsEditzNodes =<?php echo Menu::getListOfJsoan($permissions['role'])?>;

var SystemPermissionsEditClearFlag = false;

var SystemPermissionsEditOnCheck = function (e, treeId, treeNode) {
	return false;
	SystemPermissionsEditCount();
	if (SystemPermissionsEditClearFlag) {
		SystemPermissionsEditClearCheckedOldNodes();
	}
}

var SystemPermissionsEditClearCheckedOldNodes = function () {
	var zTree = $.fn.zTree.getZTreeObj("SystemPermissionsEditTree"),
	nodes = zTree.getChangeCheckedNodes();
	for (var i=0, l=nodes.length; i<l; i++) {
		nodes[i].checkedOld = nodes[i].checked;
	}
}

var SystemPermissionsEditCount = function () {
	var zTree = $.fn.zTree.getZTreeObj("SystemPermissionsEditTree"),
	checkedVal = [];
	
	for(var i in zTree.getCheckedNodes(true)){
		checkedVal.push(zTree.getCheckedNodes(true)[i].id);		
	}
	
	return checkedVal;
}

var SystemPermissionsEditCreateTree = function () {
	$.fn.zTree.init($("#SystemPermissionsEditTree"), SystemPermissionsEditSetting, SystemPermissionsEditzNodes);
}

var SystemPermissionsEditSubmit = function (){
	var rname = $("#SystemPermissionsEditRname").val();
	var role = SystemPermissionsEditCount();
	if(!rname){
		$.messager.alert('系统消息','请填写权限名称','warning');
		return false;
	}

	if(role==''){
		$.messager.alert('系统消息','请选择权限菜单','warning');
		return false;
	}

	$.ajax({
		type:"POST",
		global:false,
		url:BASEUSER+"/system/permissions/update",
		data:"rname="+rname+"&role="+role+"&id="+<?php echo $permissions['id']?>,
		dataType:"JSON",
		success:function(data){					
			if(data.code==1){
				$.messager.alert('系统消息','权限编辑成功!');
				$("#tt").tabs('close','编辑权限');
			}else{
				$.messager.alert('系统消息',data.msg,'error');
			}
		}
	});
}

var SystemPermissionsEditcancel = function(){
	$("#tt").tabs('close','编辑权限');
}

$(document).ready(function(){
	SystemPermissionsEditCreateTree();
});
//-->
</SCRIPT>