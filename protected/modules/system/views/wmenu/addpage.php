<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');
$cs->registerScriptFile($baseUrl.'/js/jquery.fineuploader-3.7.0.min.js');
?>
<div id="SystemWmenuCreatepageAddPanel">
	<div style="text-align:center" >
		<form id="SystemWmenuCreatepageAddForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				<tr>
					<td style="text-align:right" class="row">标题：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" name="SystemWmenuCreatepageAddTitle" id="SystemWmenuCreatepageAddTitle" data-options="required:true"  />  
					</td>
				</tr>
				<tr>
					<td style="text-align:right;" class="row">图片:</td>
					<td style="text-align:left;" class="row">
						<div id="SystemWmenuCreatepageAddBimgShowArea">
							<img src="" id="SystemWmenuCreatepageAddBimgShow" width="1px">
						</div>
						<div id="SystemWmenuCreatepageAddBimgBut" style="width:120px">
							<div id="SystemWmenuCreatepageAddBimgProcessing" style="display:none">
								<img id="SystemWmenuCreatepageAddBimgShow" src="<?php echo $baseUrl?>/images/processing.gif" style="width: 20px;">
							</div>
							<div id="SystemWmenuCreatepageAddBimgButText" style="padding:10px 0 10px 0;"><a href="javascript:void(0)" class="easyui-linkbutton" >上传图片</a></div>
						</div>
						<input type="hidden" name="SystemWmenuCreatepageAddBimg" id="SystemWmenuCreatepageAddBimg">
						<table id="video_table" style="text-align:center">
							<tr><td width="250px">Bimg</td><td width="20px">删除</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">文字：</td>
					<td style="text-align:left" class="row">
						<textarea cols="50" rows="10" id="SystemWmenuCreatepageAddContent" name="SystemWmenuCreatepageAddContent"></textarea>
					</td>
				</tr>		
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="systemWmenuCreatepageAdd.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var systemWmenuCreatepageAdd = {
		Bimgnum:0,
		removenum:'',
		init:function(){
			$("#SystemWmenuCreatepageAddPanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '添加微信菜单',  
										});
			systemWmenuCreatepageAdd.createUploader2('SystemWmenuCreatepageAddBimg');	
		},
		submitForm:function(){
			var title = $("#SystemWmenuCreatepageAddTitle").val();
			var bimg='';
			for(var i=1;i<=systemWmenuCreatepageAdd.Bimgnum;i++){
				if(systemWmenuCreatepageAdd.removenum.indexOf(i)>=0){
					continue;
				}
				bimg+=$('#Bimg_'+i).children().attr('src').replace(BASEUSER, '')+"|"
			}
			var content = $("input[name='SystemWmenuCreatepageAddContent']").val();
			//if(!systemWmenuCreatepageAdd.alertInfoMsg(bimg,'请上传图片'))return false;
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/Wmenu/PageAdd',
				data:{"title":title,"img":bimg,"content":content},
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','添加微信菜单').tabs("select","生成微信页面");
						$('#systemWmenuCreatepageDatagrid').datagrid('reload');
					}else{
						$.messager.alert('系统消息',data.msg,'error');
					}
				}
			});
		},
		createUploader:function (obj,type){
			$('#'+obj+'ShowArea').fineUploader({
				uploaderType: 'basic',
				multiple: false,
				debug: true,
				autoUpload: true,
				button: $("#"+obj+'But'),
				request: {
					endpoint: BASEUSER+'/system/home/upload'+type
				}
			}).on('progress',function(id, fileName, loaded, total){
				$("#"+obj+"ButText").hide();
				$("#"+obj+"Processing").show();
			}).on('complete',function(id, fileName, responseJSON,xhr){
				if(!type){
					$("#"+obj+"Show").attr('src', BASEUSER+"/"+xhr.uploadName).css("width","200px");
					$("#"+obj).val(xhr.uploadName);
				}	
				else{
					//$("#"+obj+"ShowArea").text(xhr.uploadName);
					systemWmenuCreatepageAdd.addDiv(obj,type,xhr.uploadName);

				}	
				$("#"+obj+"ButText").show();
				$("#"+obj+"Processing").hide();
			}).on('upload',function(id, fileName){
				//alert('upload');
			}).on('submit', function(event, id, name) {
				// alert('onsubmit');//$(this).fineUploader('setParams', {'param1': 'val1'});
			}).on('error',function(id, name, errorReason, xhr){
				alert(xhr)
				return false;
			});
		},
		createUploader2:function (obj){
			$('#'+obj+'ShowArea').fineUploader({
				uploaderType: 'basic',
				multiple: false,
				debug: true,
				autoUpload: true,
				button: $("#"+obj+'But'),
				request: {
					endpoint: BASEUSER+'/system/home/upload'
				}
			}).on('progress',function(id, fileName, loaded, total){
				$("#"+obj+"ButText").hide();
				$("#"+obj+"Processing").show();
			}).on('complete',function(id, fileName, responseJSON,xhr){
				systemWmenuCreatepageAdd.addDiv(obj,xhr.uploadName);
				$("#"+obj+"ButText").show();
				$("#"+obj+"Processing").hide();
			}).on('upload',function(id, fileName){
				//alert('upload');
			}).on('submit', function(event, id, name) {
				// alert('onsubmit');//$(this).fineUploader('setParams', {'param1': 'val1'});
			}).on('error',function(id, name, errorReason, xhr){
				alert(xhr)
				return false;
			});
		},
		addDiv:function(obj,name){			
			systemWmenuCreatepageAdd.Bimgnum++;			
			var htm='<tr><td id="Bimg_'+systemWmenuCreatepageAdd.Bimgnum+'"><img src="'+BASEUSER+name+'" width="50px" height="50px"></td>';
			htm+='<td><img onclick="systemWmenuCreatepageAdd.removeDiv(this,'+systemWmenuCreatepageAdd.Bimgnum+')" src="'+BASEUSER+'/images/chacha.jpg"></td></tr>'
			$('#video_table').append(htm);
		},
		removeDiv:function(obj,num){
			$(obj).parent().parent().remove()
			systemWmenuCreatepageAdd.removenum+=num+',';
		},
		alertInfoMsg:function(val,msg){
			if(!val){
				$.messager.alert('系统消息',msg,'info');
				return false;
			}else{
				return true;
			}
		}
	}
	
	systemWmenuCreatepageAdd.init();
//-->
</script>