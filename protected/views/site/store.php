<div class="page store">

<div class="container">
        <h2>巴黎春天百货</h2>
        <?php
        if (file_exists(Yii::app()->request->baseUrl.'/vstyle/imgs/store/'.$store['id'].'.jpg')) {
        ?>
    	<img src="<?php echo Yii::app()->request->baseUrl; ?>/vstyle/imgs/store/<?php echo $store['id'];?>.jpg" width="100%" />
    	<?php
    	}
    	?>
    	<div class="storeInfo">
    	    <span class="flagIcon"></span>
    	    <h3><?php echo $store['name'];?></h3>
    	    <p>地址: <?php echo $store['address'];?></p>
    	    <p class="teltext">电话: <?php echo $store['telphone'];?></p>
    	    <p>营业时间: <?php echo $store['open'];?></p>
    	</div>
</div>

</div>		
