	<?php $form=$this->beginWidget('CActiveForm', array(
			'id' => 'cupon-form',
			'errorMessageCssClass' => 'help-block',
			'htmlOptions' => array(
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data'
			),
	)); ?>
		<style>
		#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
		</style>
		<div class="form-body">
								
			<div class="form-group ">
				<?php echo $form->label($model, yii::t('app','礼品名称'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('title')));?>
					<?php echo $form->error($model, 'title' )?>
				</div>
			</div>
			<!-- 活动标题 -->
			<div class="form-group">
				<?php echo $form->label($model,'礼品图片',array('class'=>'control-label col-md-3')); ?>
				<div class="col-md-9">
				<?php
				$this->widget('application.extensions.swfupload.SWFUpload',array(
					'callbackJS'=>'swfupload_callback',
					'fileTypes'=> '*.jpg',
					'buttonText'=> yii::t('app','上传产品图片'),
					'companyId' => $model->dpid,
					'imgUrlList' => array($model->gift_pic),
				));
				?>
				<?php echo $form->hiddenField($model,'gift_pic'); ?>
				<?php echo $form->error($model,'gift_pic'); ?>
				</div>
			</div>
			<!-- 主图片 -->
			<div class="form-group" >
				<?php echo $form->label($model, yii::t('app','摘要'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textArea($model, 'intro',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('intro')));?>
					<?php echo $form->error($model, 'intro' )?>
				</div>
			</div>
			<!-- 活动摘要 -->
			<div class="form-group">
				<?php echo $form->label($model, yii::t('app','礼品面值'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'price',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('price')));?>
					<?php echo $form->error($model, 'price' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, yii::t('app','限领次数'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'count',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('count')));?>
					<?php echo $form->error($model, 'count' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, yii::t('app','礼品库存'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'stock',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('stock')));?>
					<?php echo $form->error($model, 'stock' )?>
				</div>
			</div>
            <div class="form-group">
					<label class="control-label col-md-3"><?php echo yii::t('app','活动有效期限');?></label>
					<div class="col-md-4">
						 <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
							 <?php echo $form->textField($model,'begin_time',array('class' => 'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('begin_time'))); ?>
							 <span class="input-group-addon"> ~ </span>
							 <?php echo $form->textField($model,'end_time',array('class'=>'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('end_time'))); ?>
						</div> 
						<!-- /input-group -->
						<?php echo $form->error($model,'begin_time'); ?>
						<?php echo $form->error($model,'end_time'); ?>
					</div>
				</div>
			<div class="form-group">
				<?php echo $form->label($model, yii::t('app','关注自动领取'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<div class="radio-list">
						<label class="radio-inline">
						<input type="radio" name="Gift[is_sent]" id="optionsRadios1" value="1" <?php if($model->is_sent) echo 'checked';?>> 是
						</label>
						<label class="radio-inline">
						<input type="radio" name="Gift[is_sent]" id="optionsRadios2" value="0" <?php if(!$model->is_sent) echo 'checked';?>> 否
						</label>
					</div>
				</div>
			</div>
			
			<div class="form-actions fluid">
				<div class="col-md-offset-3 col-md-9">
					<button type="submite" id="su" class="btn blue"><?php echo yii::t('app','确定');?></button>
					<a href="<?php echo $this->createUrl('gift/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
				</div>
			</div>
	<?php $this->endWidget(); ?>
	<script>
		function swfupload_callback(name,path,oldname)  {
			$("#Cupon_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>