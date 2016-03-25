<?php
/* @var $this GiftcardController */
/* @var $data Giftcard */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('giftcard_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->giftcard_id), array('view', 'id'=>$data->giftcard_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('brand_id')); ?>:</b>
	<?php echo CHtml::encode($data->brand_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stock')); ?>:</b>
	<?php echo CHtml::encode($data->stock); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count')); ?>:</b>
	<?php echo CHtml::encode($data->count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('intro')); ?>:</b>
	<?php echo CHtml::encode($data->intro); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('giftcard_pic_small')); ?>:</b>
	<?php echo CHtml::encode($data->giftcard_pic_small); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('giftcard_pic_large')); ?>:</b>
	<?php echo CHtml::encode($data->giftcard_pic_large); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delete_flag')); ?>:</b>
	<?php echo CHtml::encode($data->delete_flag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('exchangeable')); ?>:</b>
	<?php echo CHtml::encode($data->exchangeable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('consume_point')); ?>:</b>
	<?php echo CHtml::encode($data->consume_point); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activity_point')); ?>:</b>
	<?php echo CHtml::encode($data->activity_point); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shop_flag')); ?>:</b>
	<?php echo CHtml::encode($data->shop_flag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publish')); ?>:</b>
	<?php echo CHtml::encode($data->publish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_time')); ?>:</b>
	<?php echo CHtml::encode($data->start_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_time')); ?>:</b>
	<?php echo CHtml::encode($data->end_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('group_id')); ?>:</b>
	<?php echo CHtml::encode($data->group_id); ?>
	<br />

	*/ ?>

</div>