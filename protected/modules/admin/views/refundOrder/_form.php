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
		<div class="form-group <?php if($model->hasErrors('manufacturer_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'manufacturer_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'manufacturer_id', Helper::genMfrInfoname() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('manufacturer_id')));?>
				<?php echo $form->error($model, 'manufacturer_id' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('organization_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'organization_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'organization_id',  CHtml::listData(Helper::genOrgCompany($this->companyId), 'dpid', 'company_name'),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('organization_id')));?>
				<?php echo $form->error($model, 'organization_id' )?>
			</div>
		</div>
        <div class="form-group <?php if($model->hasErrors('admin_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'admin_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'admin_id', Helper::genUsername($this->companyId) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('admin_id')));?>
				<?php echo $form->error($model, 'admin_id' )?>
			</div>
		</div>
        <div class="form-group <?php if($model->hasErrors('storage_account_no')) echo 'has-error';?>">
			<?php echo $form->label($model, 'storage_account_no',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<select class="form-control" name="RefundOrder[storage_account_no]" id="RefundOrder_storage_account_no">
					<option value="0">--请选择--</option>
				</select>
				<?php echo $form->error($model, 'storage_account_no' )?>
			</div>
		</div>
		
		<div class="form-group <?php if($model->hasErrors('refund_date')) echo 'has-error';?>">
			<?php echo $form->label($model, 'refund_date',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'refund_date',array('class' => 'form-control ui_timepicker','placeholder'=>$model->getAttributeLabel('refund_date')));?>
				<?php echo $form->error($model, 'refund_date' )?>
			</div>
		</div>
		
		<div class="form-group" <?php if($model->hasErrors('remark')) echo 'has-error';?>>
			<?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-5">
				<?php echo $form->textArea($model, 'remark', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
				<?php echo $form->error($model, 'remark' )?>
			</div>
		</div>
		<!--<div class="form-group <?php if($model->hasErrors('status')) echo 'has-error';?>">
			<?php echo $form->label($model, 'status',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'status', array('0' => yii::t('app','已审核') , '1' => yii::t('app','未审核')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('status')));?>
				<?php echo $form->error($model, 'status' )?>
			</div>
		</div>-->
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<!-- <a href="<?php echo $this->createUrl('refundOrder/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>   -->                          
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
$(document).ready(function () {
	   var dpid = $('#RefundOrder_organization_id').val();
	   getStorageOrder(dpid);
	   $(".ui_timepicker").datetimepicker({
		   showSecond: true,
		   timeFormat: 'hh:mm:ss',
		   stepHour: 1,
		   stepMinute: 1,
		   stepSecond: 1
	   });
	   
	   $('#RefundOrder_organization_id').change(function(){
		   var dpid = $('#RefundOrder_organization_id').val();
		   getStorageOrder(dpid);
	   });
	   
});



function getStorageOrder(dpid){
	var storageNo = '<?php echo $model->storage_account_no;?>';
	$.ajax({
		url:"<?php echo $this->createUrl('/admin/refundOrder/getStorageOrder',array('companyId'=>$this->companyId));?>",
		data:{dpid:dpid},
		success:function(msg){
			if(msg.length>0){
				var str = '';
				for(var i in msg){
					if(storageNo==msg[i].storage_account_no){
						str += '<option value="'+msg[i].storage_account_no+'" selected>'+msg[i].storage_account_no+'</option>';
					}else{
						str += '<option value="'+msg[i].storage_account_no+'">'+msg[i].storage_account_no+'</option>';
					}
				}
				$('#RefundOrder_storage_account_no').append(str);
			}
		},
		dataType:'json'
	});
}
</script>