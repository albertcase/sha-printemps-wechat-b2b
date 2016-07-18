<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');
$cs->registerScriptFile($baseUrl.'/js/jquery.fineuploader-3.7.0.min.js');
?>
<div id="SystemWmenuCreatepageEditPanel">
	<div style="text-align:center" >
		<form id="SystemWmenuCreatepageEditForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				<tr>
					<td style="text-align:right" class="row">标题：</td>
					<td style="text-align:left" class="row">
						<input class="easyui-validatebox" type="text" value="<?php echo $subjectMsg['title'];?>" name="SystemWmenuCreatepageEditTitle" id="SystemWmenuCreatepageEditTitle" data-options="required:true"  />  
					</td>
				</tr>
				<tr>
					<td style="text-align:right;" class="row">图片:</td>
					<td style="text-align:left;" class="row">
						<div id="SystemWmenuCreatepageEditBimgShowArea">
							<img src="" id="SystemWmenuCreatepageEditBimgShow" width="1px">
						</div>
						<div id="SystemWmenuCreatepageEditBimgBut" style="width:120px">
							<div id="SystemWmenuCreatepageEditBimgProcessing" style="display:none">
								<img id="SystemWmenuCreatepageEditBimgShow" src="<?php echo $baseUrl?>/images/processing.gif" style="width: 20px;">
							</div>
							<div id="SystemWmenuCreatepageEditBimgButText" style="padding:10px 0 10px 0;"><a href="javascript:void(0)" class="easyui-linkbutton" >上传图片</a></div>
						</div>
						<input type="hidden" name="SystemWmenuCreatepageEditBimg" id="SystemWmenuCreatepageEditBimg">
						<table id="video_table_edit" style="text-align:center">
							<tr><td width="250px">Bimg</td><td width="20px">删除</td></tr>
							<tr><td id="Bimg_1"></td>
							<td><img onclick="systemWmenuCreatepageEdit.removeDiv(this,1)" src="<?php echo $baseUrl?>/images/chacha.jpg"></td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="text-align:right" class="row">文字：</td>
					<td style="text-align:left" class="row">
						<textarea cols="50" rows="10" id="SystemWmenuCreatepageEditContent" name="SystemWmenuCreatepageEditContent"><?php echo $subjectMsg['content']?></textarea>  
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<input type="hidden" value="<?php echo $subjectMsg['id']?>" name="SystemWmenuCreatepageEditId" id="SystemWmenuCreatepageEditId">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="systemWmenuCreatepageEdit.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<?php
$bimgary=explode("|",$subjectMsg['img']);
$table='';
$table.='<tr><td width="250px">图片</td><td width="20px">删除</td></tr>';			
for($i=1;$i<count($bimgary);$i++){
	$table.='<tr><td id="Bimg_'.$i.'"><img src="'.yii::app()->request->hostInfo.$baseUrl.$bimgary[$i-1].'" width="50px" height="50px"></td>';
	$table.='<td><img onclick="systemWmenuCreatepageEdit.removeDiv(this,'.$i.')" src="'. $baseUrl.'/images/chacha.jpg"></td></tr>';
}
?>
<script type="text/javascript">
<!--
	var systemWmenuCreatepageEdit = {
		Bimgnum:<?php echo count($bimgary)-1;?>,
		removenum:'',
		init:function(){
			$("#SystemWmenuCreatepageEditPanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '编辑微信页面',  
										});
			systemWmenuCreatepageEdit.createUploader2('SystemWmenuCreatepageEditBimg','');	
			$('#video_table_edit').html('<?php echo $table?>')	
		},
		submitForm:function(){
			var bimg='';
			for(var i=1;i<=systemWmenuCreatepageEdit.Bimgnum;i++){
				if(systemWmenuCreatepageEdit.removenum.indexOf(i)>=0){
					continue;
				}
				bimg+=$('#Bimg_'+i).children().attr('src').replace(BASEUSER, '')+"|"
			}
			var id= $("#SystemWmenuCreatepageEditId").val();
			var title= $("#SystemWmenuCreatepageEditTitle").val();
			var content= $("input[name='SystemWmenuCreatepageEditContent']").val();
			//if(!systemWmenuCreatepageEdit.alertInfoMsg(simg,'请上传左侧图片'))return false;
			//if(!systemWmenuCreatepageEdit.alertInfoMsg(bimg,'请上传右侧图片'))return false;
			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/Wmenu/UpdatePage',
				data:{"id":id,"title":title,"img":bimg,content:content},
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','编辑微信页面').tabs("select","生成微信页面");
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
					systemWmenuCreatepageEdit.EditDiv(obj,type,xhr.uploadName);

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
				systemWmenuCreatepageEdit.addDiv(obj,xhr.uploadName);
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
			systemWmenuCreatepageEdit.Bimgnum++;			
			var htm='<tr><td id="Bimg_'+systemWmenuCreatepageEdit.Bimgnum+'"><img src="'+BASEUSER+name+'" width="50px" height="50px"></td>';
			htm+='<td><img onclick="systemWmenuCreatepageEdit.removeDiv(this,'+systemWmenuCreatepageEdit.Bimgnum+')" src="'+BASEUSER+'/images/chacha.jpg"></td></tr>'
			$('#video_table_edit').append(htm);
			//$('#Bimg_'+systemWmenuCreatepageEdit.Bimgnum).html('<img src="'+BASEUSER+name+'" width="50px" height="50px">');		
		},
		removeDiv:function(obj,num){
			//var lowid=systemWmenuCreatepageEdit.Bimgnum>systemWmenuCreatepageEdit.flvnum?systemWmenuCreatepageEdit.flvnum:systemWmenuCreatepageEdit.Bimgnum;
			//if(num==lowid){
			//	return false
			//}
			$(obj).parent().parent().remove()
			systemWmenuCreatepageEdit.removenum+=num+',';
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
	
	systemWmenuCreatepageEdit.init();
//-->
</script>