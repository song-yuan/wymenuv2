<script type="text/javascript" src="<?php echo Yii::app()->baseUrl.'/plugins/jquery-1.10.2.min.js';?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl.'/js/product/slick.min.js';?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl.'/js/product/classie.js';?>"></script>
<ul id="gallery">
	 <?php foreach($pics as $pic):?>
	  <li>
		<img src="<?php echo $pic['pic_path']?>" alt="">
	  </li>
	  <?php endforeach;?>
 </ul>
<script type="text/javascript">
$('#gallery').slick({
      dots: true,
      infinite: true,
      speed: 1000,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      arrows: false
    });
</script>

