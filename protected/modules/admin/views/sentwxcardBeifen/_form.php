							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'sentwxcardpromotion-form',
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
											<?php echo $form->textField($model, 'promotion_title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_title')));?>
											<?php echo $form->error($model, 'promotion_title' )?>
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
									<?php if($model->hasErrors('promotion_abstract')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','摘要'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'promotion_abstract',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_abstract')));?>
											<?php echo $form->error($model, 'promotion_abstract' )?>
										</div>
									</div><!-- 活动摘要 -->
									<!-- <div class="form-group">
										<?php echo $form->label($model, yii::t('app','类型'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'promotion_type', array('0' => yii::t('app','独享') , '1' => yii::t('app','共享')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_type')));?>
											<?php echo $form->error($model, 'promotion_type' )?>
										</div>
									</div><!-- 活动类型 -->
									
									<!-- <div class="form-group">
										<?php echo $form->label($model, yii::t('app','活动针对对象'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'to_group', array( '1' => yii::t('app','关注微信人群'), '2' => yii::t('app','会员等级') , '3' => yii::t('app','会员个人')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('to_group')));?>
											<?php echo $form->error($model, 'to_group' )?>
										</div>
									</div>
                                     -->
									<?php if($model->to_group=="2"):{?>
									<div id="yincang" style="display: ;" class="form-group ">
										<label class="col-md-3 control-label"><?php echo yii::t('app','会员等级');?></label>
										<div class="col-md-4" style="border:1px solid red;">
										<?php if($brdulvs) :{?>
										<?php $i=1;?>
										<?php foreach ($brdulvs as $brdulv):?>
										
											<tr class="odd gradeX">
												<td><input type="checkbox" id="<?php echo $i;?>" class="checkboxes " <?php if(!empty($userlvs)){foreach ($userlvs as $userlv){if($userlv['brand_user_lid'] == $brdulv->lid) echo 'checked' ;}}else echo "123";?>   value="<?php echo $brdulv->lid;?>" name="chk" /></td>
												<td><?php echo $i,$brdulv->level_name; ?></td>
												
											</tr>
										<?php $i=$i+1;?>
										<?php endforeach;?>
										<?php }endif;?>
										</div>
										<input type="hidden" id="hidden1" name="hidden1" value="" />
									</div>
								<?php }elseif($model->to_group!="2"):{?>
										<div id="yincang" style="display:none ;" class="form-group ">
										<label class="col-md-3 control-label"><?php echo yii::t('app','会员等级');?></label>
										<div class="col-md-4" style="border:1px solid red;">
										<?php if($brdulvs) :{?>
										<?php $i=1;?>
										<?php foreach ($brdulvs as $brdulv):?>
										
											<tr class="odd gradeX">
												<td><input type="checkbox" id="<?php echo $i;?>" class="checkboxes" check="" value="<?php echo $brdulv->lid;?>" name="chk" /></td>
												<td><?php echo $i,$brdulv->level_name; ?></td>
												
											</tr>
										<?php $i=$i+1;?>
										<?php endforeach;?>
										<?php }endif;?>
										</div>
										<input type="hidden" id="hidden1" name="hidden1" value="" />
									</div>
								<?php }endif;?>
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','是否生效'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_available', array('0' => yii::t('app','生效') , '1' => yii::t('app','不生效')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
											<?php echo $form->error($model, 'is_available' )?>
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
										<?php echo $form->label($model, yii::t('app','发送信息'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-8">
											<?php echo $form->textArea($model, 'promotion_message' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_message')));?>
											<?php echo $form->error($model, 'promotion_message' )?>
										</div>
									</div><!-- 图文说明 -->	
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','规则说明'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-8">
											<?php echo $form->textArea($model, 'promotion_memo' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_memo')));?>
											<?php echo $form->error($model, 'promotion_memo' )?>
										</div>
									</div><!-- 图文说明 -->
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="button" id="su" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<!-- <a href="<?php echo $this->createUrl('buysentpromotion/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>    -->                           
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'BuysentPromotion_promotion_memo',	//Textarea id
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

     $("#su").on('click',function() {
         //alert(11);
         var begintime = $('#BuysentPromotion_begin_time').val();
         var endtime = $('#BuysentPromotion_end_time').val();
         if(endtime<=begintime){
        	 alert("<?php echo yii::t('app','活动结束时间应该大于开始时间!!!');?>");
        	 return false;
         }
         $("#sentwxcardpromotion-form").submit();
     });
 
	
		function swfupload_callback(name,path,oldname)  {
			//alert(6789);
			$("#SentwxcardPromotion_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>