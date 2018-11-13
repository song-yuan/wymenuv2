<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'wxlevel-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
<div class="form-body">
	<div class="form-group">
		<?php echo $form->label($model, 'wr_name',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'wr_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('wr_name')));?>
			<?php echo $form->error($model, 'wr_name' )?>
										</div>
									</div>
                                    <div class="form-group">
										<?php echo $form->label($model, 'recharge_money',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'recharge_money',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('recharge_money')));?>
			<?php echo $form->error($model, 'recharge_money' )?>
										</div>
									</div>
                                    <div class="form-group">
										<?php echo $form->label($model, 'recharge_pointback',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'recharge_pointback',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('recharge_pointback')));?>
			<?php echo $form->error($model, 'recharge_pointback' )?>
										</div>
									</div>
                                    <div class="form-group">
										<?php echo $form->label($model, 'recharge_cashback',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'recharge_cashback',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('recharge_cashback')));?>
			<?php echo $form->error($model, 'recharge_cashback' )?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->label($model, 'recharge_number',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'recharge_number',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('recharge_number')));?>
			<?php echo $form->error($model, 'recharge_number' )?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->label($model, 'recharge_dpid',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<select id="select2_sample2" class="form-control select2" name="WeixinRecharge[recharge_dpid][]" multiple>
				<?php foreach ($companys as $company):?>
				<option value="<?php echo $company['dpid'];?>" <?php if(in_array($company['dpid'], $redpids)){echo 'selected="selected"';}?>><?php echo $company['company_name'];?></option>
				<?php endforeach;?>
			</select>
			</div>
		</div>
        <div class="form-group">
			<?php echo $form->label($model, 'is_available',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
			<?php echo $form->dropDownList($model, 'is_available', array( '0' =>yii::t('app','是'),'1' => yii::t('app','否') ) , array('id'=>'is_available', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
			<?php echo $form->error($model, 'is_available' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
			<!-- <a href="<?php echo $this->createUrl('wxrecharge/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>  -->                          
		</div>
	</div>
<?php $this->endWidget(); ?>
							