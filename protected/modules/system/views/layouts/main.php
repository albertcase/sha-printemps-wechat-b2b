<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/easyui.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/icon.css" media="screen, projection" />
	<?php
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$baseUrl=Yii::app()->request->baseUrl;
		//$cs->registerScriptFile($baseUrl.'/js/jquery-1.9.1.min.js', CClientScript::POS_END);
		$cs->registerCssFile($baseUrl.'/css/jquery-ui-1.10.1.custom.min.css');
		$cs->registerCssFile($baseUrl.'/css/zTreeStyle/zTreeStyle.css');
		$cs->registerScriptFile($baseUrl.'/js/jquery.easyui.min.js', CClientScript::POS_END);
		$cs->registerScriptFile($baseUrl.'/js/easyui-lang-zh_CN.js', CClientScript::POS_END);
		$cs->registerScriptFile($baseUrl.'/js/jquery.tabs.extend.js', CClientScript::POS_END);
		$cs->registerScriptFile($baseUrl.'/js/jquery.ztree.all-3.2.min.js', CClientScript::POS_END);
		if(isset($_GET['command'])){
			$command = base64_decode($_GET['command']);
			$command = json_decode($command,true);
			$cs->registerScript('index-custom-js',
								'jQuery(document).ready(function(){
										openTab("'.$command['name'].'","'.$command['url'].'?id='.$command['id'].'");
									})');
		}
		
	?>
	<script type="text/javascript">
		BASEUSER='<?php echo Yii::app()->request->hostInfo.Yii::app()->request->baseUrl; ?>';
		var CKEDITOR_BASEPATH = BASEUSER+'/js/ckeditor/';
	</script>	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<script>
		var openTab = function(name,url)
		{
			
			if($("#tt").tabs('exists',name))
			{
				$("#tt").tabs('select',name);
				$('#tt').tabs('getTab',name).panel('refresh','<?php echo Yii::app()->request->baseUrl; ?>/system/'+url);
				return false;
			}
			$('#tt').tabs('add',{  
			    title:name,
			    href:'<?php echo Yii::app()->request->baseUrl; ?>/system/'+url,
			    closable:true,
			    tools:[{
			        iconCls:'icon-mini-refresh',
			        handler:function(e){
						$('#tt').tabs('select',name);
						e.stopPropagation();
						$('#tt').tabs('getTab',name).panel('refresh');
						//$('#tt').tabs('getTab',name).panel('options').content = undefined;
			        }  
			    }]  
			});
			$('#tt').tabs('addEventParam');
		}

		var loginOut = function (){
			$.messager.confirm('系统消息', '您确定要退出登录吗？', function(r){
				if(r){
					$.ajax({
						type:"POST",
						global:false,
						url:"<?php echo Yii::app()->request->baseUrl; ?>/system/login/loginout",
						data:"",
						dataType:"JSON",
						success:function(data){	
							window.location.href="<?php echo Yii::app()->request->baseUrl; ?>/system/login";
						}
					});
				}
			});
		}
	</script>
</head>

<body class="easyui-layout" id="cc">
	<div data-options="region:'north',border:false" style="height:50px;padding:10px">
		<div style="width:200px">微信管理系統</div>
		<div style="float:right;padding:0 200px 1px 1px">欢迎您：<?php if(isset(Yii::app()->user->sysUserRName))echo Yii::app()->user->sysUserRName?> <a href="#" onclick="loginOut();" class="easyui-linkbutton">退出</a></div>
	</div>
	<div data-options="region:'west',split:true" title="菜单" style="width:280px;padding1:1px;">
		<ul id="sysMenuTree" class="easyui-tree" data-options="url:'<?php echo Yii::app()->request->baseUrl; ?>/system/menu/list',checkbox:false,
			onClick: function(node){
				//$(this).tree('toggle', node.target);
				if(node.attributes.url)
					openTab(node.text,node.attributes.url);
			}"
	></ul>
	</div>
	<!-- <div data-options="region:'east',split:true,collapsed:true,title:'East'" style="width:100px;padding:10px;">east region</div> -->
	<div data-options="region:'south',border:false" style="height:50px;text-align:center">Copyright &copy; <?php echo date('Y'); ?> by SameSame.<br/>
		All Rights Reserved.<br/>Powered by SameSame</div>
	<div data-options="region:'center'" id="mainWindow">
		<div class="easyui-tabs" id="tt" data-options="fit:true,border:false">
			<div title="首页" style="padding:20px;overflow:hidden;" data-options="href:'<?php echo Yii::app()->request->baseUrl; ?>/system/site/serverInfo'">
			</div>			
		</div>
	</div>
</body>
</html>
