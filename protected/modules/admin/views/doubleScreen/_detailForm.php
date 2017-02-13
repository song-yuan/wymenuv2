							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'DoubleScreenDetail-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'type',array('0' => yii::t('app','图片') , '1' => yii::t('app','视频')) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
											<?php echo $form->error($model, 'type' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('url')) echo 'has-error';?> urlclass">
										<?php echo $form->label($model,'url',array('class'=>'control-label col-md-3')); ?>
										<div class="col-md-9">
										<?php
										$this->widget('application.extensions.swfupload.SWFUpload',array(
											'callbackJS'=>'swfupload_callback',
											'thumbWidth'=>1080,
											'thumbHeight'=>935,
											'fileTypes'=> '*.jpg',
											'buttonText'=> yii::t('app','上传图片'),
											'companyId' => $model->dpid,
											'imgUrlList' => array($model->url),
										));
										?>
										<?php echo $form->hiddenField($model,'url'); ?>
										<?php echo $form->error($model,'url'); ?>
										</div>
									</div>
									<div class="form-group urlclass2" style="display: none;">
										<label class="col-md-3 control-label" for="DoubleScreenDetail_url2">资源地址</label>
										<div class="col-md-4">
											<input class="form-control" onchange="yftBlur(this)" placeholder="资源地址" name="DoubleScreenDetail[url2]" id="DoubleScreenDetail_url2" type="text" maxlength="255" for="url2" <?php if($model->url && $model->type) echo 'value="'.$model->url.'"';?>>
											<input class="form-control" type="hidden" placeholder="资源地址" name="url2" id="url2" type="text" maxlength="255">
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('doubleScreen/detailIndex' , array('companyId' => $model->dpid,'groupname'=>$groupname ,'groupid'=>$groupid,'type'=>$type));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'Product_description',	//Textarea id
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
	function yftBlur(obj)
	{
	  var value=obj.value; 
	  $('#url2').val(value);  	
	 }
	$(document).ready(function(){
		var type = $('#DoubleScreenDetail_type').val();
		if(type==0){
		       $(".urlclass").show();
		       $(".urlclass2").hide();
		    }else{
		       $(".urlclass2").show();
		       $(".urlclass").hide();
		    
		}
	});
	   $('#DoubleScreenDetail_type').on('change',function(){
	   		var type = $(this).val();
	   		if(type==0){
			       $(".urlclass").show();
			       $(".urlclass2").hide();
			    }else{
			       $(".urlclass2").show();
			       $(".urlclass").hide();
			    
			}       
	   });
		
		function swfupload_callback(name,path,oldname)  {
			$("#DoubleScreenDetail_url").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>