<div id="personal">
	<div class="personal_content">
		<div class="personal_title">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/personal.jpg" width="100%" />
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/logo.png" width="100%" style="margin: 4% 0 0 0 "/>
		</div>
		<div class="form">
			<ul>
				<li>
					<span>
						<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/sex.png" />
					</span>
					<select class="sex">
						<option value="0"></option>
						<option value="1">先生</option>
						<option value="2">女士</option>
					</select>	
				</li>
				<li>
					<span>
						<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/name_l.png" />
					</span>
					<input type="text" class="name_l"/>
				</li>
				<li>
					<span>
						<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/name_f.png" />
					</span>
					<input type="text" class="name_f"/>
				</li>
				<li>
					<span class="book">
						<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/date.png" />
					</span>
					<input type="date" class="date"/>
					
				</li>
				<li>
					<span class="book">
						<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/time.png" />
					</span>
					<select class="time">
						<option value="0"></option>
						<option value="1">10:00</option>
						<option value="2">11:00</option>
					</select>
				</li>
				<li>
					<span class="book">
						<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/contact.png" />
					</span>
					<select class="contact">
						<option value="0"></option>
						<option value="1">1</option>
						<option value="2">2</option>
					</select>	
				</li>
				<li>
					<input type="text" class="method"/>
				</li>
				<li>
					<span>
						<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/pro_type.png" />
					</span>
				</li>
				<li>
				    <div class="radio">
					    <div class="radio-choose">
					    	<div class="radio-btn"><i><input type="radio" name="radio-btn" checked="checked"></i>
					    	</div>
			            	<img class="radio_img" src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/type1.png" />
			            </div>

			            <div class="radio-choose">
				            <div class="radio-btn"><i><input type="radio" name="radio-btn" checked="checked"></i>
				            </div>
				            <img class="radio_img" src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/type2.png" />
				        </div>
				        <div class="radio-choose">
				            <div class="radio-btn"><i><input type="radio" name="radio-btn" checked="checked"></i>
				            </div>
				            <img class="radio_img" src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/type3.png" />
			            </div>

			            <div class="radio-choose">
				            <div class="radio-btn"><i><input type="radio" name="radio-btn" checked="checked"></i>
				            </div>
				            <img class="radio_img" src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/type4.png" />
				        </div>

				        <div class="radio-choose">
				            <div class="radio-btn"><i><input type="radio" name="radio-btn" checked="checked"></i>
				            </div>
				            <img class="radio_img" src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/type5.png" />
			            </div>

			            <div class="radio-choose">
				            <div class="radio-btn"><i><input type="radio" name="radio-btn" checked="checked"></i>
				            </div>
				            <img class="radio_img" src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/type6.png" />
				        </div>

			        </div>

				</li>

				<li>
					<br/>
					<span>
						<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/pro_type.png" />
					</span>
				</li>
				<li>
				</li>
				<li>
				</li>	
				

			</ul>	
		</div>	
	</div>
	<div class="personal_footer">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spring/personal/submit_btn.png" width="100%" class="submit_btn"/>
	</div>		
</div>

<script>
$(".radio-btn").on("click", function () {
    var _this = $(this),
        block = _this.parent().parent();
    block.find("input:radio").attr("checked", false);
    block.find(".radio-btn").removeClass("checkedRadio");
    _this.addClass("checkedRadio");
    _this.find("input:radio").attr("checked", true);
});
</script>	