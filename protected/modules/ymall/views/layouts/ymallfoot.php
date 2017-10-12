
	<nav class="mui-bar mui-bar-tab">
			<a class="mui-tab-item <?php $cname=Yii::app()->controller->id;if ($cname=='product')echo 'mui-active';?> " id="product">
				<span class="mui-icon mui-icon-home"></span>
				<span class="mui-tab-label">首页</span>
			</a>
			<a class="mui-tab-item <?php $cname=Yii::app()->controller->id;if ($cname=='')echo 'mui-active';?> " id="kind">
				<span class="mui-icon mui-icon-list"></span>
				<span class="mui-tab-label">分类</span>
			</a>
			<a class="mui-tab-item <?php $cname=Yii::app()->controller->id;if ($cname=='ymallcart')echo 'mui-active';?> " id="cart">
				<span class="mui-icon iconfont icon-cart"><span class="mui-badge">9</span></span>
				<span class="mui-tab-label">购物车</span>
			</a>
			<a class="mui-tab-item <?php $cname=Yii::app()->controller->id;if ($cname=='myinfo'||$cname=='address')echo 'mui-active';?> " id="my">
				<span class="mui-icon mui-icon-contact"></span>
				<span class="mui-tab-label">我的</span>
			</a>
	</nav>
	</body>

	<script type="text/javascript" charset="utf-8">
		mui.init();
	</script>
	<script>
	var button = document.getElementById('product');
	button.addEventListener('tap',function(){
	location.href="<?php echo $this->createUrl('product/index',array('companyId'=>$this->companyId));?>";
	});

	var button = document.getElementById('cart');
	button.addEventListener('tap',function(){
	location.href="<?php echo $this->createUrl('ymallcart/index',array('companyId'=>$this->companyId));?>";
	});
	var button1 = document.getElementById('my');
	button1.addEventListener('tap',function(){
	location.href="<?php echo $this->createUrl('myinfo/index',array('companyId'=>$this->companyId));?>";
	});
	</script>
</body>


