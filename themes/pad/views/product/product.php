<?php
/* @var $this ProductController */
	$baseUrl = Yii::app()->baseUrl;
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product/ui-btn.css');
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product/ui-img.css');
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product/ui-list.css');
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product/ui-base.css');
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product/ui-box.css');
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product/ui-color.css');
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product/pic.css');
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product/ui-media.css'); 
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product.css');
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product/reset.css');
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/product/slick.css');

	Yii::app()->clientScript->registerScriptFile($baseUrl.'/js/product/zepto.js');
	Yii::app()->clientScript->registerScriptFile($baseUrl.'/js/product/base64.js'); 
	Yii::app()->clientScript->registerScriptFile($baseUrl.'/js/product/pic.js');
?>

	<?php $this->renderPartial('parentcategory',array('categoryId'=>$categoryId,'type'=>$type,'siteNoId'=>$siteNoId));?>
	<link href='<?php echo $baseUrl.'/css/product/reset.css';?>' rel='stylesheet' type='text/css'>
	<link href=<?php echo $baseUrl.'/css/product/slick.css';?> rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="<?php echo $baseUrl.'/js/product/slick.min.js';?>"></script>
	<script type="text/javascript" src="<?php echo $baseUrl.'/js/product/classie.js';?>"></script>
	<script type="text/javascript" src="<?php echo $baseUrl.'/js/product/jquery.form.js';?>"></script>
	<script type="text/javascript" src="<?php echo $baseUrl.'/js/product/productpad.js';?>"></script>
	<div id="page_0" class="up ub ub-ver" tabindex="0">
	<!--content开始-->
    <div id="content" class="ub-f1 tx-l t-bla ub-img6 res10">
		<div id="forum_list">
			<div class="outDiv" id="leftPic">
			</div>
			<div class="outDiv" id="rightPic">
			</div>
		</div>
    </div>
    <!--content结束-->
</div>
<form id="padOrderForm" action="confirmPadOrder" method="post">
<div class="product-pad-mask">
	<div class="mask-trangle"></div>
	<div class="product-mask-info">点单信息</div>
	<div class="info">
	</div>
	<div class="product-bottom">
		<button id="updatePadOrder">下单并打印</button>
	</div>
</div>
</form>
<!-- 加入订单动画 -->
<div class="aniele"></div>

<div class="large-pic">
</div>
<script type="text/javascript">
	var cat = '<?php echo $categoryId;?>';
	var t = '<?php echo $type;?>';
	var isPad = '<?php echo $isPad;?>';
	window.onload=function(type,catgory,pad)
	{
		type = t;
		catgory = cat;
		pad = isPad;
		getPicList(type,catgory,1);
	}	
	$(document).ready(function(){
		$('select[name="category"]').change(function(){
			var val = $(this).val();
			var obj = $('div[category="'+val+'"]:first');
			var height = obj.offset().top;
			$('body').scrollTop(height);
		});
		$(window).scroll(function(){
			$('.blockCategory').each(function(){
				var top = $(document).scrollTop();
				var categoryTop = $(this).offset().top;
				var height = $(this).height();
				if(parseInt(height)+parseInt(categoryTop) > parseInt(top)){
					var categoryId = $(this).attr('category');
					$('select option[value="'+categoryId+'"]').attr('selected',true);
					return false;
				}
			});
		});
	});
</script>