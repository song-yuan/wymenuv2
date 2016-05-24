							<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=hzj3D9srpRthGaFjOeBGvOG6"></script>
							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'company-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<?php echo $form->label($model, 'company_name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'company_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('company_name')));?>
											<?php echo $form->error($model, 'company_name' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('logo')) echo 'has-error';?>">
										<?php echo $form->label($model,'logo',array('class'=>'control-label col-md-3')); ?>
										<div class="col-md-9">
										<?php
										$this->widget('application.extensions.swfupload.SWFUpload',array(
											'callbackJS'=>'swfupload_callback',
											'fileTypes'=> '*.jpg',
											'buttonText'=> yii::t('app','上传产品图片'),
											'imgUrlList' => array($model->logo),
										));
										?>
										<?php echo $form->hiddenField($model,'logo'); ?>
										<?php echo $form->error($model,'logo'); ?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'contact_name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'contact_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('contact_name')));?>
											<?php echo $form->error($model, 'contact_name' )?>
										</div>
									</div>
									<?php if($role=="2"):?>
									<div class="form-group"> 
										<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
 										<div class="col-md-4"> 
											<?php echo $form->dropDownList($model, 'type',array( '1' => yii::t('app','店铺') , '2' => yii::t('app','仓库')) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
											<?php echo $form->error($model, 'type' )?>
										</div> 
									</div>
									<div class="form-group"> 
										<?php echo $form->label($model, 'is_membercard_recharge',array('class' => 'col-md-3 control-label'));?>
 										<div class="col-md-4"> 
											<?php echo $form->dropDownList($model, 'is_membercard_recharge',array('1' => yii::t('app','是'), '0' => yii::t('app','否') ) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_membercard_recharge')));?>
											<?php echo $form->error($model, 'is_membercard_recharge' )?>
										</div> 
									</div>
									<?php endif;?>
									
									<div class="form-group">
										<?php echo $form->label($model, 'mobile',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'mobile',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mobile')));?>
											<?php echo $form->error($model, 'mobile' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'telephone',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'telephone',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('telephone')));?>
											<?php echo $form->error($model, 'telephone' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'email',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'email',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('email')));?>
											<?php echo $form->error($model, 'email' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'address',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<div class="input-group">
												<?php echo $form->textField($model, 'address',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('address')));?>
												<span class="input-group-btn">
												<button class="btn blue getLocation" type="button">获取位置</button>
												</span>
											</div>
											<?php echo $form->error($model, 'address' )?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">地图位置</label>
										<div class="col-md-4">
											<div id="allmap" style="width:400px;height:200px;"></div>
										</div>
									</div>
									
									<div class="form-group">
										<?php echo $form->label($model, 'distance',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<div class="input-group">
												<?php echo $form->textField($model, 'distance',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('distance')));?>
												<span class="input-group-addon">km</span>
											</div>
											<?php echo $form->error($model, 'distance' )?>
										</div>
									</div>
									
									<div class="form-group">
										<?php echo $form->label($model, 'queuememo',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'queuememo',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('queuememo')));?>
											<?php echo $form->error($model, 'queuememo' )?>
										</div>
									</div>
									
									<div class="form-group">
										<?php echo $form->label($model, 'homepage',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'homepage',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('homepage')));?>
											<?php echo $form->error($model, 'homepage' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'domain',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'domain',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('domain')));?>
											<?php echo $form->error($model, 'domain' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'description',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-8">
											<?php echo $form->textArea($model, 'description' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('description')));?>
											<?php echo $form->error($model, 'description' )?>
										</div>
									</div>
									<?php echo $form->hiddenField($model, 'lng',array('class' => 'form-control'));?>
									<?php echo $form->hiddenField($model, 'lat',array('class' => 'form-control'));?>
                                                                        <!--
									<div class="form-group">
										<?php echo $form->label($model, 'printer_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'printer_id', array('0' => yii::t('app','-- 请选择 --')) +$printers ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('printer_id')));?>
											<?php echo $form->error($model, 'printer_id' )?>
										</div>
									</div>
									-->									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('company/index', array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'Company_description',	//Textarea id
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
		function swfupload_callback(name,path,oldname)  {
			$("#Company_logo").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
		function theLocation(result){
			var cityName = result.name;
			map.centerAndZoom(cityName,11);
		}
		var map = new BMap.Map("allmap");
		$(document).ready(function(){
			// 百度地图API功能
			var lng = $('#Company_lng').val();
			var lat = $('#Company_lat').val();
			
			if(parseInt(lng) && parseInt(lat)){
				var point = new BMap.Point(lng,lat);
				map.centerAndZoom(point,16);
				map.addOverlay(new BMap.Marker(point));
			}else{
				var myCity = new BMap.LocalCity();
				myCity.get(theLocation);
			}
			
			map.enableScrollWheelZoom(true);
			
			$('.getLocation').click(function(){
				var address = $('#Company_address').val();
				// 创建地址解析器实例
				var myGeo = new BMap.Geocoder();
				// 将地址解析结果显示在地图上,并调整地图视野
				myGeo.getPoint(address, function(point){
					if (point) {
						$('#Company_lng').val(point.lng);
						$('#Company_lat').val(point.lat);
						map.centerAndZoom(point, 16);
						map.addOverlay(new BMap.Marker(point));
					}else{
						
					}
				}, "上海市");
			});
		});
	</script>							