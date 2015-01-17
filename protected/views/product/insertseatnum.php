<?php
/* @var $this ProductController */
Yii::app()->clientScript->registerCssFile('css/insertseatnum.css');
?>
<form action="" method="post">
<div class="form-group">
  <div class="left-text">请输入服务员给您的开台号才能点单！！</div>
  <div class="right-ipt"><div class="error"><?php echo $error;?></div><input type="text" class="inpt" name="seatnum" value="" maxlength="10" placeholder="开台号" /></div>
<input type="hidden" name="referUrl" value="<?php echo $url;?>"/>
</div>
<div class="form-group">
  <div class="subdiv"><input type="submit" class="submitbtn" value="确 定" /></div>
</div>
</form>