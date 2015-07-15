<?php

echo "<pre>";
print_r($menu);
?>

<a href="javascript:void(0)" onclick="systemWmenuCreat()">确认生成微信菜单</a>
<script type="text/javascript">
var systemWmenuCreat = function(){
	$.ajax({
			type:"POST",
			global:false,
			url:"<?php echo Yii::app()->request->baseUrl; ?>/system/wmenu/createok",
			data:{},
			dataType:"JSON",
			success:function(data){
				if(data==1){
					alert('创建菜单成功');
					return;
				}else{
					alert('创建菜单失败');
					return;
				}
			}
		});
}
</script>