	<?php $form=$this->beginWidget('CActiveForm', array(
			'id' => 'product-form',
			'errorMessageCssClass' => 'help-block',
			'htmlOptions' => array(
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data'
			),
	)); ?>
		<div class="form-body">
			<div class="form-group">
				<?php echo $form->label($model, 'title',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('title')));?>
					<?php echo $form->error($model, 'title' )?>
				</div>
			</div>
			<div class="form-group ">
				<?php echo $form->label($model,'vedio_pic',array('class'=>'control-label col-md-3')); ?>
				<div class="col-md-9">
				<?php
				$this->widget('application.extensions.swfupload.SWFUpload',array(
					'callbackJS'=>'swfupload_callback',
					'fileTypes'=> '*.jpg',
					'buttonText'=> yii::t('app','上传产品图片'),
					'companyId' => $model->dpid,
					'imgUrlList' => array($model->vedio_pic),
				));
				?>
				<?php echo $form->hiddenField($model,'vedio_pic'); ?>
				<?php echo $form->error($model,'vedio_pic'); ?>
				</div>
			</div>
			
			<div class="form-group ">
				<?php echo $form->label($model,'discuss_pic',array('class'=>'control-label col-md-3')); ?>
				<div class="col-md-9">
				<?php
				$this->widget('application.extensions.swfupload.SWFUpload',array(
					'callbackJS'=>'swfupload_callback1',
					'fileTypes'=> '*.jpg',
					'buttonText'=> yii::t('app','上传产品图片'),
					'companyId' => $model->dpid,
					'image_width'=>940,
					'image_height'=>700,
					'imgUrlList' => array($model->discuss_pic),
				));
				?>
				<?php echo $form->hiddenField($model,'discuss_pic'); ?>
				<?php echo $form->error($model,'discuss_pic'); ?>
				</div>
			</div>

			<div class="form-group">
				<?php echo $form->label($model, 'vedio_url',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'vedio_url',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('vedio_url')));?>
					<?php echo $form->error($model, 'vedio_url' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'default_content',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-8">
					<?php echo $form->textArea($model, 'default_content' , array('class' => 'form-control','placeholder'=>'无最新评论时默认显示该内容'));?>
					<?php echo $form->error($model, 'default_content' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-8">
					<?php echo $form->textArea($model, 'remark' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
					<?php echo $form->error($model, 'remark' )?>
				</div>
			</div>
			<div class="form-actions fluid">
				<div class="col-md-offset-3 col-md-9">
					<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
					<a href="<?php echo $this->createUrl('screen/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
				</div>
			</div>
	<?php $this->endWidget(); ?>
							
	<script>
		function swfupload_callback(name,path,oldname)  {
			$("#Screen_vedio_pic").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
		function swfupload_callback1(name,path,oldname)  {
			$("#Screen_discuss_pic").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>