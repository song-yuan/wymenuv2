<?php
/* @var $this ProductController */
		$totalCatgorys = array();
		$command = Yii::app()->db;
		$sql = 'select lid,category_name from nb_product_category where dpid=:companyId and pid=0 and delete_flag=0';
		$parentCategorys = $command->createCommand($sql)->bindValue(':companyId',$this->companyId)->queryAll();
		//var_dump($parentCategorys);exit;
		foreach($parentCategorys as $category){
			$csql = 'select lid,category_name from nb_product_category where dpid=:companyId and pid=:pid and delete_flag=0';
			$categorys = $command->createCommand($csql)->bindValue(':companyId',$this->companyId)->bindValue(':pid',$category['lid'])->queryAll();
			$category['category_name'] = $categorys;
			array_push($totalCatgorys,$category);
		}
		$parentCategorys = $totalCatgorys;
?>
<?php if($parentCategorys):?>
<?php //var_dump($parentCategorys);exit;?>
<div class="category">
<?php foreach($parentCategorys as $categorys):?>
	<div >
    <div class="pcat"><?php echo $categorys['category_name'];?></div>
	<?php foreach($categorys['children'] as $category):?>
	<a href="<?php echo $this->createUrl('/waiter/product/index',array('pid'=>$categorys['category_id'],'category'=>$category['category_id'],'cid'=>$this->companyId,'code'=>$this->seatNum));?>"><div class="catename"><?php echo $category['category_name'];?></div></a>
	<?php endforeach;?>
	<div class="clear"></div>
	</div>
<?php endforeach;?>
</div>
<?php endif;?>