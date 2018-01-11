<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'material-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>

	<div class="form-body">
		
		<div class="form-group <?php if($model->hasErrors('opretion_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'opretion_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'opretion_id',Helper::getOpretion($this->companyId),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('opretion_id')));?>
				<?php echo $form->error($model, 'opretion_id' )?>
			</div>
		</div>
       <div class="form-group <?php if($model->hasErrors('reason_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'reason_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropdownlist($model,'reason_id' ,$retreats ,array('class' => 'form-control', ));?>
				<?php echo $form->error($model, 'reason_id' )?>
				<input class="form-control" name="Inventory_reason_id" id="Inventory_reason_id" type="hidden" value="<?php echo $model->reason_id;?>"></input>
			</div>
		</div>
		<div class="form-group" <?php if($model->hasErrors('remark')) echo 'has-error';?>>
			<?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-5">
				<?php echo $form->textArea($model, 'remark', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
				<?php echo $form->error($model, 'remark' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定:下一步');?></button>
				<a href="<?php echo $this->createUrl('inventory/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>
<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
	'id'=>'',	//Textarea id
	'language'=>'zh_CN',
	// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
	'items' => array(
		'height'=>'200px',
		'width'=>'100%',
		'themeType'=>'simple',
		'resizeType'=>1,
		'allowImageUpload'=>true,
		'allowFileManager'=>true,
	),
)); ?>
						
<script>
		$('#reason_id').change(function(){
			var rid = $(this).val();
			$('#Inventory_reason_id').val(rid);
		});

	   $(function () {
		   $(".ui_timepicker").datetimepicker({
			   //showOn: "button",
			   //buttonImage: "./css/images/icon_calendar.gif",
			   //buttonImageOnly: true,
			   showSecond: true,
			   timeFormat: 'hh:mm:ss',
			   stepHour: 1,
			   stepMinute: 1,
			   stepSecond: 1
		   })
	   });
</script>