<article>
<header>
	<a href="javascript:;" class="logo">
		<img src="/vstyle/imgs/logo.png" width="100%" />
	</a>
</header>
<section class="container">
	<div class="info">
		<img src="/vstyle/imgs/user_way.jpg" width="100%" />
	</div>

	<div class="infoAbout">
		按“+”按钮,推送您的地址信息<br />即可获得您附近 春天百货 Printemps专卖店位置
	</div>


</section>
</article>
<footer>
	<div class="con">
		<a href="javascript:;" id="tel"> 
			<img src="/vstyle/imgs/tel.png" />
			<u>+33 142825000</u> 
		</a>
		<em>联系方式</em>
	</div>
	<img src="/vstyle/imgs/footer.jpg" width="100%" class="opacity" />
</footer>

<script type="text/javascript">

var telElement = document.getElementById("tel");
var telU_Element = telElement.getElementsByTagName("u")[0];
var tel = telU_Element.innerHTML.replace(/\s/ig,'');
telElement.setAttribute("href","tel:" + tel);

</script>
