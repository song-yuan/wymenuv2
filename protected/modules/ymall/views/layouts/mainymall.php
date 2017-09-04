<?php


/* @var $this \yii\web\View */
/* @var $content string */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>壹点吃商城</title>
    <link rel="stylesheet" type="text/css" href="../../../../css/ymall/ymall.css"/>
    <script type="text/javascript" src="../../../../plugins/jquery-1.10.2.min.js"></script> 
    <script type="text/javascript" src="../../../../scripts/flipsnap.js"></script>
</head>
<body>
    <div class="ymall">
    <?php echo $content ?>
    </div>
    <?php $this->beginContent('/layouts/productmain');?>
	<?php $this->endContent();?>
</body>
</html>

