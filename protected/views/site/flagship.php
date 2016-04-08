
<article>
<header>
	<a href="javascript:;" class="logo">
		<img src="/vstyle/imgs/logo.png" width="100%" />
	</a>
</header>
<section class="container">
	<div class="info">
		<h1>巴黎春天百货 奥斯曼旗舰店</h1>
		<ul>
			<li>
				<span>
					地址 : 
				</span>
				<p>
					64, BOULEVARD HAUSSMANN 75009 <br />PARIS – FRANCE
				</p>
			</li>
			<li>
				<span>营业时间 : </span>
				<p>周一至周六 9:35至20:00</p>
			</li>
			<li>
				<span>电话 : </span>
				<p>+33142825000</p>
			</li>
		</ul>
	</div>
	
	<a href="javascript:;">
		<img src="/vstyle/imgs/map.jpg" width="100%" />
	</a>
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
