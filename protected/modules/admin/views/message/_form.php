<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'message-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data',
		),
)); ?>
<div class="form-body">
	<div class="form-group" ?>
		<label for="" class ='col-md-3 control-label'>选择总公司</label>
		<div class="col-md-4" >
			<select name="dpid" id="select" class ='form-control'>
				<option value=""> - 请选择总公司 - </option>
				<?php foreach ($dpids as $key => $dpid): ?>
					<option value="<?php echo $dpid['dpid'] ;?>" <?php if ($dpid['dpid']==$model['dpid'])echo 'selected'; ?> >  <?php echo $dpid['company_name']; ?> </option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->label($model, 'all_message_no',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'all_message_no',array('class' => 'form-control','placeholder'=>'填写本套餐的短信总条数'));?>
			<?php echo $form->error($model, 'all_message_no' )?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->label($model, 'send_message_no',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'send_message_no',array('class' => 'form-control','placeholder'=>'填写本套餐的赠送短信条数'));?>
			<?php echo $form->error($model, 'send_message_no' )?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->label($model, 'downdate',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'downdate',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('downdate')));?>
			<?php echo $form->error($model, 'downdate' )?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->label($model, 'money',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'money',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('money')));?>
			<?php echo $form->error($model, 'money' )?>
		</div>
	</div>


	<div class="form-actions fluid">
		<div class="col-md-offset-3 col-md-9">
			<button type="submit" class="btn blue" onclick='return check()'><?php echo yii::t('app','确定');?></button
		</div>
	</div>

	<?php $this->endWidget(); ?>
	<script>
	function check(){
		var isture = $('#select').val();
		if(isture==''){
			layer.msg('请选择总公司');
			return false;
		}
	}

	</script>