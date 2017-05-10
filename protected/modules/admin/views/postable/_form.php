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
	<style>
	#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
	</style>
	<div class="form-body">
		
		<div class="form-group" <?php if($model->hasErrors('content')) echo 'has-error';?>>
			<?php echo $form->label($model, 'content',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-6">
				<?php echo $form->textArea($model, 'content', array('class' => 'form-control', 'rows'=>'5','placeholder'=>$model->getAttributeLabel('content')));?>
				<?php echo $form->error($model, 'content' )?>
			</div>
		</div>
		
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<!-- <a href="<?php echo $this->createUrl('postable/index' , array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a> -->
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>
					
<script>
     	   $('#category_container').on('change','.category_selecter',function(){
	   		var id = $(this).val();
	   		var $parent = $(this).parent();
                        var sid ='0000000000';
                        var len=$('.category_selecter').eq(1).length;
                        if(len > 0)
                        {
                            sid=$('.category_selecter').eq(1).val();
                            //alert(sid);
                        }
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