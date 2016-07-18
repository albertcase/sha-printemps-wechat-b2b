<div style="padding:10px 0 10px 60px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'permissions-form',
	'enableAjaxValidation'=>false,
)); ?>
		<table>
			<tr>
				<td>权限名称:</td>
				<td><input class="easyui-validatebox" type="text" name="SystemPermissionsAddRname" id="SystemPermissionsAddRname" data-options="required:true"></input></td>
			</tr>
			<tr>
				<td>权限菜单:</td>
				<td>
					<ul id="SystemPermissionsAddTree" class="ztree"></ul>
				</td>
			</tr>
		</table>
	</form>
	</div>
	<div style="text-align:center;padding:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" onclick="SystemPermissionsAddSubmit()">保存</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" onclick="SystemPermissionsAddcancel()">取消</a>
	</div>
</div>

<?php $this->endWidget(); ?>

<SCRIPT type="text/javascript">
<!--

var SystemPermissionsAddSetting = {
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
		onCheck: SystemPermissionsAddOnCheck
	}
};

var SystemPermissionsAddzNodes =<?php echo Menu::getListOfJsoan()?>;

var SystemPermissionsAddClearFlag = false;

var SystemPermissionsAddOnCheck = function (e, treeId, treeNode) {
	return false;
	SystemPermissionsAddCount();
	if (SystemPermissionsAddClearFlag) {
		SystemPermissionsAddClearCheckedOldNodes();
	}
}

var SystemPermissionsAddClearCheckedOldNodes = function () {
	var zTree = $.fn.zTree.getZTreeObj("SystemPermissionsAddTree"),
	nodes = zTree.getChangeCheckedNodes();
	for (var i=0, l=nodes.length; i<l; i++) {
		nodes[i].checkedOld = nodes[i].checked;
	}
}

var SystemPermissionsAddCount = function () {
	var zTree = $.fn.zTree.getZTreeObj("SystemPermissionsAddTree"),
	checkedVal = [];
	
	for(var i in zTree.getCheckedNodes(true)){
		checkedVal.push(zTree.getCheckedNodes(true)[i].id);		
	}
	
	return checkedVal;
}

var SystemPermissionsAddCreateTree = function () {
	$.fn.zTree.init($("#SystemPermissionsAddTree"), SystemPermissionsAddSetting, SystemPermissionsAddzNodes);
	//SystemPermissionsAddCount();
	//SystemPermissionsAddClearFlag = $("#last").attr("checked");
}

var SystemPermissionsAddSubmit = function (){
	var rname = $("#SystemPermissionsAddRname").val();
	var role = SystemPermissionsAddCount();
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
		url:BASEUSER+"/system/permissions/add",
		data:"rname="+rname+"&role="+role,
		dataType:"JSON",
		success:function(data){					
			if(data.code==1){
				$.messager.alert('系统消息','权限添加成功!');
				$("#tt").tabs('close','添加权限');				
			}else{
				$.messager.alert('系统消息',data.msg,'error');
			}
		}
	});
}

var SystemPermissionsAddcancel = function(){
	$("#tt").tabs('close','添加权限');
}

$(document).ready(function(){
	SystemPermissionsAddCreateTree();
});
//-->
</SCRIPT>