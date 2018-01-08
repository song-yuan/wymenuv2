							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'fullSentPromotion-form',
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
									
										<?php echo $form->label($model, yii::t('app','标题'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('title')));?>
											<?php echo $form->error($model, 'title' )?>
										</div>
									</div><!-- 活动标题 -->
						
									<div class="form-group" >
									<?php if($model->hasErrors('infor')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','摘要'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'infor',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('infor')));?>
											<?php echo $form->error($model, 'infor' )?>
										</div>
									</div><!-- 活动摘要 -->
									<div class="form-group" >
									<?php if($model->hasErrors('full_cost')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','购买满足金额'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'full_cost',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('full_cost')));?>
											<?php echo $form->error($model, 'full_cost' )?>
										</div>
									</div><!-- 需要的积分 -->
									<!-- <div class="form-group" >
									<?php if($model->hasErrors('extra_cost')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','加价金额'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'extra_cost',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('extra_cost')));?>
											<?php echo $form->error($model, 'extra_cost' )?>
										</div>
									</div><!-- 需要的积分 -->
									<div class="form-group" >
									<?php if($model->hasErrors('sent_number')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','赠送数量限制'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'sent_number',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('sent_number')));?>
											<?php echo $form->error($model, 'sent_number' )?>
										</div>
									</div><!-- 需要的积分 -->
									<?php if(Yii::app()->user->role <=5):?>
					                <div class="form-group">
					                        <?php echo $form->label($model, yii::t('app','是否生效'),array('class' => 'col-md-3 control-label'));?>
					                        <div class="col-md-4">
					                                <?php echo $form->checkBoxList($model, 'is_available', array( '1' => yii::t('app','显示在POS机端'), '2' => yii::t('app','显示在微信堂食端'), '3' => yii::t('app','显示在微信外卖')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
					                                <?php echo $form->error($model, 'is_available' )?>
					                        </div>
					                </div><!-- 活动是否生效 -->
					                <?php else:?>
					                <div class="form-group">
					                        <?php echo $form->label($model, yii::t('app','是否生效'),array('class' => 'col-md-3 control-label'));?>
					                        <div class="col-md-4">
					                                <?php echo $form->checkBoxList($model, 'is_available', array( '1' => yii::t('app','生效'),) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
					                                <?php echo $form->error($model, 'is_available' )?>
					                        </div>
					                </div><!-- 活动是否生效 -->
					                <?php endif;?>
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
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="button" id="su" class="btn blue"><?php echo yii::t('app','下一步：');?></button>
											<a href="<?php echo $this->createUrl('fullSentPromotion/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'PrivatePromotion_promotion_memo',	//Textarea id
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
							
	<script type="text/javascript">    
	   
	 $(document).ready(function(){ 
	 $('#PrivatePromotion_to_group').change(function(){ 
	 //alert($(this).children('option:selected').val()); 
	 var p1=$(this).children('option:selected').val();//这就是selected的值 
		//alert(p1);
		 if(p1=="2"){
			 $("#yincang").show();
		 }else{
			$("#yincang").hide();
			 }
	
	 }) 
	 });

     $("#su").on('click',function() {
         //alert(11);
         var p1 = $('#PrivatePromotion_to_group').children('option:selected').val();
         var begintime = $('#PrivatePromotion_begin_time').val();
         var endtime = $('#PrivatePromotion_end_time').val();
         var aa = document.getElementsByName("chk");
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
        
         //else{
        //	 alert("<?php echo yii::t('app','请选择相应的会员等级！！！');?>");
          //   }
         //alert(str);
      //  }else{
        // alert(str);}
         $("#hidden1").val(str);
         $("#fullSentPromotion-form").submit();
     });
 
	
		function swfupload_callback(name,path,oldname)  {
			//alert(6789);
			$("#PrivatePromotion_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>