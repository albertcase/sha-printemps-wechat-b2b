<?php
$returnUrl = yii::app()->request->hostInfo.Yii::app()->user->returnUrl;
$cs=Yii::app()->getClientScript();
$baseUrl=Yii::app()->request->baseUrl;
$cs->registerCssFile($baseUrl.'/css/table.css');
$cs->registerScriptFile($baseUrl.'/js/jquery.fineuploader-3.7.0.min.js');

$msgTypeAry = array('text'=>'文本','news'=>'图文','image'=>'图片',);
?>
<div id="SystemWmenuEditstorePanel">
	<div style="text-align:center" >
		<form id="SystemWmenuEditstoreForm" method="post" action="">
			<table style="width:100%;text-align:center" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
				<tr>
					<th scope="col" style="text-align:right;width:20%">名称</th>
					<th scope="col">操作项</th>
				</tr>
				<tr id="SystemWmenuEditstoreCountryArea">
					<td style="text-align:right" class="row">国家：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditstoreCountry" name="SystemWmenuEditstoreCountry" value="<?php echo $storeMsg['country']?>">
					</td>
				</tr>

				<tr id="SystemWmenuEditstoreCityArea">
					<td style="text-align:right" class="row">城市：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditstoreCity" name="SystemWmenuEditstoreCity" value="<?php echo $storeMsg['city']?>">
					</td>
				</tr>

				<tr id="SystemWmenuEditstoreNameArea">
					<td style="text-align:right" class="row">门店名称：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditstoreName" name="SystemWmenuEditstoreName" value="<?php echo $storeMsg['name']?>">
					</td>
				</tr>

				<tr id="SystemWmenuEditstoreAddressArea">
					<td style="text-align:right" class="row">门店地址：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditstoreAddress" name="SystemWmenuEditstoreAddress" value="<?php echo $storeMsg['address']?>">
					</td>
				</tr>

				<tr id="SystemWmenuEditstoreTelphoneArea">
					<td style="text-align:right" class="row">电话：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditstoreTelphone" name="SystemWmenuEditstoreTelphone" value="<?php echo $storeMsg['telphone']?>">
					</td>
				</tr>

				<tr id="SystemWmenuEditstoreOpenArea">
					<td style="text-align:right" class="row">营业时间：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditstoreOpen" name="SystemWmenuEditstoreOpen" value="<?php echo $storeMsg['open']?>">
					</td>
				</tr>

				<tr id="SystemWmenuEditstoreLatArea">
					<td style="text-align:right" class="row">经度：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditstoreLat" name="SystemWmenuEditstoreLat" value="<?php echo $storeMsg['lat']?>">
					</td>
				</tr>

				<tr id="SystemWmenuEditstoreLngArea">
					<td style="text-align:right" class="row">纬度：</td>
					<td style="text-align:left" class="row">
						<input type="text" id="SystemWmenuEditstoreLng" name="SystemWmenuEditstoreLng" value="<?php echo $storeMsg['lng']?>">
					</td>
				</tr>

				<tr>
					<td style="text-align:right;" class="row">图片：</td>
					<td style="text-align:left;" class="row">
						<div id="SystemWmenuEditStoreSimgShowArea">
							<img src="<?php echo  $baseUrl.$storeMsg['picUrl']?>" id="SystemWmenuEditStoreSimgShow">
						</div>
						<div id="SystemWmenuEditStoreSimgBut" style="width:120px">
							<div id="SystemWmenuEditStoreSimgProcessing" style="display:none">
								<img id="SystemWmenuEditStoreSimgShow" src="<?php echo $baseUrl?>/images/system/processing.gif" style="width: 20px;">
							</div>
							<div id="SystemWmenuEditStoreSimgButText" style="padding:10px 0 10px 0;"><a href="javascript:void(0)" class="easyui-linkbutton" >点击上传图片</a></div>
						</div>
						<input type="hidden" value="<?php echo $storeMsg['picUrl']?>" name="SystemWmenuEditStoreSimg" id="SystemWmenuEditStoreSimg">
					</td>
				</tr>

				<tr>
					<td style="text-align:right;" class="row">地图：</td>
					<td style="text-align:left;" class="row">
						<div id="SystemWmenuEditStoreBimgShowArea">
							<img src="<?php echo  $baseUrl.$storeMsg['mapUrl']?>" id="SystemWmenuEditStoreBimgShow">
						</div>
						<div id="SystemWmenuEditStoreBimgBut" style="width:120px">
							<div id="SystemWmenuEditStoreBmgProcessing" style="display:none">
								<img id="SystemWmenuEditStoreBimgShow" src="<?php echo $baseUrl?>/images/system/processing.gif" style="width: 20px;">
							</div>
							<div id="SystemWmenuEditStoreBimgButText" style="padding:10px 0 10px 0;"><a href="javascript:void(0)" class="easyui-linkbutton" >点击上传图片</a></div>
						</div>
						<input type="hidden" value="<?php echo $storeMsg['mapUrl']?>" name="SystemWmenuEditStoreBimg" id="SystemWmenuEditStoreBimg">
					</td>
				</tr>
				</tbody>
				<tr>
					<td colspan="2" style="text-align:center;width:200px" class="row">
						<input type="hidden" value="<?php echo $storeMsg['id']?>" name="SystemWmenuEditstoreID" id="SystemWmenuEditstoreID">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="systemWmenuEditstore.submitForm()">提交</a>
					</td>
				</tr>
			</table>			
		</form>
	</div>
</div>
<script type="text/javascript">
<!--
	var systemWmenuEditstore = {
		init:function(){
			$("#SystemWmenuEditstorePanel").panel({
											height: parseInt($('#tt .panel').css('height')),
											title: '编辑门店',  
										});
			systemWmenuEditstore.createUploader('SystemWmenuEditStoreSimg');
			systemWmenuEditstore.createUploader('SystemWmenuEditStoreBimg');
		},
		submitForm:function(){	
			var formdata = {
				country : $("#SystemWmenuEditstoreCountry").val(),
				city : $("#SystemWmenuEditstoreCity").val(),
				name : $("#SystemWmenuEditstoreName").val(),
				address : $("#SystemWmenuEditstoreAddress").val(),
				telphone : $("#SystemWmenuEditstoreTelphone").val(),
				open : $("#SystemWmenuEditstoreOpen").val(),
				lat : $("#SystemWmenuEditstoreLat").val(),
				lng : $("#SystemWmenuEditstoreLng").val(),
				id : $("#SystemWmenuEditstoreID").val(),
				picUrl: $("#SystemWmenuEditStoreSimg").val(),
				mapUrl: $("#SystemWmenuEditStoreBimg").val()
			}

			if(!systemWmenuEditstore.alertInfoMsg(formdata.id,'请求数据非法'))return false;

			$.ajax({
				type:"POST",
				global:false,
				url:BASEUSER+'/system/wmenu/storeupdate',
				data:formdata,
				dataType:"JSON",
				success:function(data){					
					if(data.code==1){						
						$.messager.alert('系统消息',data.msg);
						$('#tt').tabs('close','编辑门店').tabs("select","门店列表");
						$('#systemWmenustoreDatagrid').datagrid('reload');
					}else{
						$.messager.alert('系统消息',data.msg,'error');
					}
				}
			});
		},		
		alertInfoMsg:function(val,msg){
			if(!val){
				$.messager.alert('系统消息',msg,'info');
				return false;
			}else{
				return true;
			}
		},
		createUploader:function (obj){
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
				$("#"+obj+"Show").attr('src', BASEUSER+"/"+xhr.uploadName);
				$("#"+obj).val(xhr.uploadName);
				$("#"+obj+"ButText").show();
				$("#"+obj+"Processing").hide();
			}).on('upload',function(id, fileName){
				//alert('upload');
			}).on('submit', function(store, id, name) {
				// alert('onsubmit');//$(this).fineUploader('setParams', {'param1': 'val1'});
			}).on('error',function(id, name, errorReason, xhr){
				alert(xhr)
			});
		},
	}
	
	systemWmenuEditstore.init();
//-->
</script>