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

	<div class="infoList">
		<h2>布雷斯特店（BREST ）</h2>
		<p><em>地址:</em> <span>59, RUE JEAN JAURÈS 29200   BREST  -  FRANCE</span></p>
		<p><em>电话:</em> <span>0033298446565</span></p>
		<p><em>营业时间:</em> <span>周一至周六 9:30至19:30</span></p>
	</div>

	<div class="infoList">
		<h2>卡昂（CAEN ）</h2>
		<p><em>地址:</em> <span>28, RUE ST JEAN 14000   CAEN   -   FRANCE</span></p>
		<p><em>电话:</em> <span>0033231156550</span></p>
		<p><em>营业时间:</em> <span>周一至周五 9:30至19:00  周六 9:35至20:00</span></p>
	</div>

</section>
</article>
<footer>
	<div class="con">
		<a href="javascript:;" id="tel"> 
			<img src="/vstyle/imgs/tel.png" />
			<u>+33142825000</u>
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
