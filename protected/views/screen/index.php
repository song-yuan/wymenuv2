<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('视频列表');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/screen.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/player/cyberplayer.js"></script>

<div class="section">
	<?php if($screens):?>
	<ul class="vedio">
		<?php foreach($screens as $screen):?>
		<li><a href="<?php echo $this->createUrl('/screen/infor',array('companyId'=>$this->companyId,'screenId'=>$screen['lid']));?>"><img src="<?php echo $screen['vedio_pic']?$screen['vedio_pic']: $baseUrl.'/img/profile/profile-img.png';?>"/><br /> <span><?php echo $screen['title'];?></span></a></li>
		<?php endforeach;?>
	</ul>
	<?php else:?>
	<h2>无视频资源</h2>
	<?php endif;?>
</div>