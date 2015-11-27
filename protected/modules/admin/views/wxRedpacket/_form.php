							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'wxredpacket-form',
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
									<?php if($model->hasErrors('redpacket_name')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','红包名称'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'redpacket_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('redpacket_name')));?>
											<?php echo $form->error($model, 'redpacket_name' )?>
										</div>
									</div><!-- 活动标题 -->
						
									<div class="form-group" >
									<?php if($model->hasErrors('total')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','红包发送个数'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'total',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('total')));?>
											<?php echo $form->error($model, 'total' )?>
										</div>
									</div><!-- 活动摘要 -->
									<div class="form-group">
											<label class="control-label col-md-3"><?php echo yii::t('app','红包领用截止日期');?></label>
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
													 <?php echo $form->textField($model,'end_time',array('class' => 'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('begin_time'))); ?>
												 </div> 
												<!-- /input-group -->
												
												<?php echo $form->error($model,'end_time'); ?>
											</div>
										</div>
                                    
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('wxRedpacket/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'NormalPromotion_promotion_memo',	//Textarea id
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
			$("#NormalPromotion_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>