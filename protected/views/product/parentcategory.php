<?php
/* @var $this ProductController */
	$parentCategorys = ProductCategory::getCategorys($this->companyId);	
?>

<link rel="stylesheet" type="text/css"  href="../css/product/category.css" />
<div class="fixed-top">
	<div class="category-top">全部分类</div>
	<div class="promptumenu_window">
	<ul class="promptu-menu">
		<li>
			<ol>
				<li class="parents">热点</li>
				<a href="<?php echo $this->createUrl('/product/index',array('type'=>1));?>">
				<li class="child <?php if($type==1) echo 'active';?>">推荐品</li>
				</a>
				<a href="<?php echo $this->createUrl('/product/index',array('type'=>2));?>">
				<li class="child <?php if($type==2) echo 'active';?>">套餐</li>
				</a>
				<a href="<?php echo $this->createUrl('/product/index',array('type'=>3));?>">
				<li class="child <?php if($type==3) echo 'active';?>"><img src="/wymenuv2/img/favorite.png">Top10</li>
				</a>
				<a href="<?php echo $this->createUrl('/product/index',array('type'=>4));?>">
				<li class="child <?php if($type==4) echo 'active';?>"><img src="/wymenuv2/img/ordernum.png">Top10</li>
				</a>
			</ol>
		</li>
		<?php if($parentCategorys):?>
		<?php foreach($parentCategorys as $categorys):?>
		<li>
			<ol>
			<li class="parents"><?php echo $categorys['category_name'];?></li>
			<?php foreach($categorys['children'] as $category):?>
			<a href="<?php echo $this->createUrl('/product/index',array('pid'=>$category['pid'],'categoryId'=>$category['lid']));?>">
				<li class="child float <?php if(!$type&&$category['lid']==$categoryId) echo 'active';?>"><?php echo Helper::truncate_utf8_string($category['category_name'],6);?></li>
			</a>
			<?php endforeach;?>
			<div class="clear"></div>
			</ol>
		</li>	
		<?php endforeach;?>
		<?php endif;?>
		<div class="clear"></div>
	</ul>
	</div>
</div>
<script>
	$(document).ready(function(){
		var liWidth = 0;
		$('.promptu-menu').children('li').each(function(){
			var len = $(this).find('.child').length;
			var num = Math.ceil(len/4);
			
			$(this).find('ol').css('width',num*130);
			$(this).find('.child').css('width',(100/num-2) +'%');
			liWidth +=$(this).width()+10;
			$('.promptu-menu').css('width',liWidth);
		});
	});
</script>
