<?php
/* @var $this CompanyController */
Yii::app()->clientScript->registerCssFile('css/company/company.css');
?>

<h1 style="margin:20px;">公司介绍</h1>
<div class="company-name">企业名称:<?php echo $company->company_name;?></div>
<div class="logo"><img src="<?php echo $company->logo;?>"/></div>
<div class="intro">简介:<?php echo $company->description;?></div>
<div class="person">联系人:<?php echo $company->contact_name;?></div>
<div class="mobile">手 机:<?php echo $company->mobile;?></div>
<div class="mobile">电话:<?php echo $company->telephone;?></div>
<div class="address">地址:<?php echo $company->telephone;?></div>
<div class="homepage">网址:<a href ="<?php echo $company->homepage;?>"><?php echo $company->homepage;?></a></div>