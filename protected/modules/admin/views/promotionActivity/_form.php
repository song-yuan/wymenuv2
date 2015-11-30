							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'privatepromotion-form',
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
									<?php if($model->hasErrors('activity_title')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','活动名称'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'activity_title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('activity_title')));?>
											<?php echo $form->error($model, 'activity_title' )?>
										</div>
									</div><!-- 活动标题 -->
									<div class="form-group">
										<?php if($model->hasErrors('main_picture')) echo 'has-error';?>
										<?php echo $form->label($model,'main_picture',array('class'=>'control-label col-md-3')); ?>
										<div class="col-md-9">
										<?php
										$this->widget('application.extensions.swfupload.SWFUpload',array(
											'callbackJS'=>'swfupload_callback',
											'fileTypes'=> '*.jpg',
											'buttonText'=> yii::t('app','上传产品图片'),
											'companyId' => $model->dpid,
											'imgUrlList' => array($model->main_picture),
										));
										?>
										<?php echo $form->hiddenField($model,'main_picture'); ?>
										<?php echo $form->error($model,'main_picture'); ?>
										</div>
									</div><!-- 主图片 -->
						
									<div class="form-group" >
									<?php if($model->hasErrors('activity_abstract')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','活动摘要'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'activity_abstract',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('activity_abstract')));?>
											<?php echo $form->error($model, 'activity_abstract' )?>
										</div>
									</div><!-- 活动摘要 -->
									
									
                                    <div class="form-group">
										<?php echo $form->label($model, yii::t('app','是否关注即推送'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_first_push', array('0' => yii::t('app','是') , '1' => yii::t('app','否')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_first_push')));?>
											<?php echo $form->error($model, 'is_first_push' )?>
										</div>
									</div><!-- 是否可用代金券 -->
                                  
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','是否扫码推送'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_scan_push', array('0' => yii::t('app','是') , '1' => yii::t('app','否')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_scan_push')));?>
											<?php echo $form->error($model, 'is_scan_push' )?>
										</div>
									</div><!-- 活动是否生效 -->
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
										<?php echo $form->label($model, yii::t('app','图文说明'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-8">
											<?php echo $form->textArea($model, 'activity_memo' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('activity_memo')));?>
											<?php echo $form->error($model, 'activity_memo' )?>
										</div>
									</div><!-- 图文说明 -->
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="button" id="su" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('promotionActivity/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'PromotionActivity_activity_memo',	//Textarea id
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
    $("#su").on('click',function() {
        //alert(11);
       // var p1 = $('#PrivatePromotion_to_group').children('option:selected').val();
        var begintime = $('#PromotionActivity_begin_time').val();
        var endtime = $('#PromotionActivity_end_time').val();
        //var aa = document.getElementsByName("chk");
        var str=new Array();
        //alert(p1);
        //var ss = "";
      // if(aa.checked){
        //alert(begintime);
        //alert(endtime);
        if(endtime<=begintime){
       	 alert("<?php echo yii::t('app','活动结束时间应该大于开始时间!!!');?>");
       	 return false;
        }

        $("#privatepromotion-form").submit();
    });
		function swfupload_callback(name,path,oldname)  {
			//alert(6789);
			$("#PromotionActivity_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>