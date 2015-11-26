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
									<?php if($model->hasErrors('promotion_title')) echo 'has-error';?>
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
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','类型'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'promotion_type', array('0' => yii::t('app','独享') , '1' => yii::t('app','共享')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_type')));?>
											<?php echo $form->error($model, 'promotion_type' )?>
										</div>
									</div><!-- 活动类型 -->
									<div class="form-group" >
									<?php if($model->hasErrors('change_point')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','兑换该特价活动所需的积分'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'change_point',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('change_point')));?>
											<?php echo $form->error($model, 'change_point' )?>
										</div>
									</div><!-- 需要的积分 -->
                                    <div class="form-group">
										<?php echo $form->label($model, yii::t('app','是否可用代金券'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'can_cupon', array('0' => yii::t('app','可以使用代金券') , '1' => yii::t('app','不能使用代金券')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('can_cupon')));?>
											<?php echo $form->error($model, 'can_cupon' )?>
										</div>
									</div><!-- 是否可用代金券 -->
                                    <div class="form-group">
										<?php echo $form->label($model, yii::t('app','活动针对对象'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'to_group', array('0' => yii::t('app','所有人') , '1' => yii::t('app','关注微信的人群') ,'2' => yii::t('app','会员')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('to_group')));?>
											<?php echo $form->error($model, 'to_group' )?>
										</div>
									</div><!-- 活动实施对象 -->
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
												<!-- <div class="input-group date form_datetime" data-date="2012-12-21T15:25:00Z">                                       
													<input type="text" size="16" readonly class="form-control">
													<span class="input-group-btn">
													<button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
													</span>
													<span class="input-group-btn">
													<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
													</span>
												</div> -->
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
											<?php echo $form->textArea($model, 'promotion_memo' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_memo')));?>
											<?php echo $form->error($model, 'promotion_memo' )?>
										</div>
									</div><!-- 图文说明 -->
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('normalpromotion/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
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
							
	<script>
// 	   $('#category_container').on('change','.category_selecter',function(){
// 	   		var id = $(this).val();
// 	   		var $parent = $(this).parent();
//                         var sid ='0000000000';
//                         var len=$('.category_selecter').eq(1).length;
//                         if(len > 0)
//                         {
//                             sid=$('.category_selecter').eq(1).val();
//                             //alert(sid);
//                         }
                       
// 	   		$(this).nextAll().remove();
// 	   		$.ajax({
	   			//url:'<php echo $this->createUrl('product/getChildren',array('companyId'=>$this->companyId));?>/pid/'+id,
// 	   			type:'GET',
// 	   			dataType:'json',
// 	   			success:function(result){
// 	   				if(result.data.length){
// 	   					var str = '<select class="form-control category_selecter" tabindex="-1" name="category_id_selecter">'+
	   					//'<option value="">--'+"<php echo yii::t('app','请选择');?>"+'--</option>';
// 	   					$.each(result.data,function(index,value){
// 	   						str = str + '<option value="'+value.id+'">'+value.name+'</option>';
// 	   					});
// 	   					str = str + '</select>';
// 	   					$parent.append(str);
// 	   					$('#Product_category_id').val('');
// 	   					$parent.find('span').remove();
// 	   				}else{
//                                                 //if(selname == 'category_id_selecter2')
//                                                     $('#Product_category_id').val(sid);                                                
// 	   				}
// 	   			}
// 	   		});
// 	   });
	
		function swfupload_callback(name,path,oldname)  {
			//alert(6789);
			$("#PrivatePromotion_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>