							<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
							<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/PCASClass.js');?>
							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'company-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
							<style>
								.selectedclass{
									font-size: 14px;
									color: #333333;
									height: 34px;
									line-height: 34px;
									padding: 6px 12px;
								}
							</style>
							<?php if(Yii::app()->user->role <11) $roleboom = false;else $roleboom = true;?>
								<div class="form-body">
									<div class="form-group">
										<?php echo $form->label($model, 'company_name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'company_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('company_name'),'disabled'=>$roleboom));?>
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
											'buttonText'=> yii::t('app','上传图片'),
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
											<?php echo $form->textField($model, 'contact_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('contact_name'),'disabled'=>$roleboom));?>
											<?php echo $form->error($model, 'contact_name' )?>
										</div>
									</div>
									<?php if($role<="9"&&$role>="3"){?>
									<?php if($type=='-1'):?>
									<div class="form-group"> 
										<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
 										<div class="col-md-4"> 
											<?php echo $form->dropDownList($model, 'type',array( '1' => yii::t('app','店铺') , '2' => yii::t('app','仓库')) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
											<?php echo $form->error($model, 'type' )?>
										</div> 
									</div>
									
									<div class="form-group">
									<LABEL class="col-md-3 control-label ">是否开通线上支付</LABEL>
										<div class="col-md-4">
											<SELECT class="form-control pay_online">
												<OPTION value="0">不开通</OPTION>
												<OPTION value="2">开通线上支付（个人）</OPTION>
												<OPTION value="1">开通线上支付（总部）</OPTION>
											</SELECT>
										</div>
									</div>
									<?php elseif($type != '-1'):?>
									<div class="form-group"> 
										<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
 										<div class="col-md-4"> 
											<?php echo $form->dropDownList($model, 'type',array( '0' => yii::t('app','公司'),'1' => yii::t('app','店铺') , '2' => yii::t('app','仓库')) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type'),'disabled'=>true,));?>
											<?php echo $form->error($model, 'type' )?>
										</div> 
									</div>
									
									<?php endif;?>
									<?php if($type !='2'):?>
									<?php if(!empty($groups)):?>
									<div class="form-group">
									<LABEL class="col-md-3 control-label ">选择店铺标签</LABEL>
										<div class="col-md-4">
											<SELECT name="com[group]" class="form-control pay_online">
											<option>---请选择---</option>
											<?php foreach($groups as $group):?>
												<OPTION value="<?php echo $group['lid'];?>" <?php if(!empty($company)): if($company['area_group_id']==$group['lid']):?> selected = "selected"<?php endif;endif;?>><?php echo $group['group_name'];?></OPTION>
											<?php endforeach;?>
											</SELECT>
										</div>
									</div>
									<?php endif;?>
									<?php if(!empty($prices)):?>
									<div class="form-group">
									<LABEL class="col-md-3 control-label ">选择店铺价格体系</LABEL>
										<div class="col-md-4">
											<SELECT name="com[price]" class="form-control pay_online">
											<option>---请选择---</option>
											<?php foreach($prices as $price):?>
												<OPTION value="<?php echo $price['lid'];?>" <?php if(!empty($property)): if($property['price_group_id']==$price['lid']):?> selected = "selected"<?php endif;endif;?>><?php echo $price['group_name'];?></OPTION>
											<?php endforeach;?>
											</SELECT>
										</div>
									</div>
									<?php endif;?>
									<div id="yincang" style="display:black;">
									<div class="form-group"> 
										<?php echo $form->label($model, 'is_membercard_recharge',array('class' => 'col-md-3 control-label'));?>
 										<div class="col-md-4"> 
											<?php echo $form->dropDownList($model, 'is_membercard_recharge',array('1' => yii::t('app','是'), '0' => yii::t('app','否') ) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_membercard_recharge')));?>
											<?php echo $form->error($model, 'is_membercard_recharge' )?>
										</div> 
									</div>
									
									<div class="form-group"> 
										<?php echo $form->label($model, 'membercard_code',array('class' => 'col-md-3 control-label'));?>
 										<div class="col-md-4"> 
											<?php echo $form->textField($model, 'membercard_code' ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('membercard_code')));?>
											<?php echo $form->error($model, 'membercard_code' )?>
										</div> 
									</div>
									<div class="form-group"> 
										<?php echo $form->label($model, 'membercard_enable_date',array('class' => 'col-md-3 control-label'));?>
 										<div class="col-md-4"> 
											<?php echo $form->textField($model, 'membercard_enable_date' ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('membercard_enable_date'),'onkeyup'=>"if(isNaN(value))execCommand('undo')", 'onafterpaste'=>"if(isNaN(value))execCommand('undo')"));?>
											<?php echo $form->error($model, 'membercard_enable_date' )?>
										</div> 
									</div>
									<div class="form-group"> 
										<?php echo $form->label($model, 'membercard_points_type',array('class' => 'col-md-3 control-label'));?>
 										<div class="col-md-4"> 
											<?php echo $form->dropDownList($model, 'membercard_points_type' ,array( '0' => yii::t('app','不积分') , '1' => yii::t('app','按订单原价积分') , '2' => yii::t('app','按订单实收价积分')),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('membercard_points_type')));?>
											<?php echo $form->error($model, 'membercard_points_type' )?>
										</div> 
									</div>
									</div>
									<?php endif;?>
									<?php }else{?>
										<?php if(Yii::app()->user->role <5 || $type2 == 'create'):?>
											
											<div class="form-group">
											<LABEL class="col-md-3 control-label ">是否开通线上支付</LABEL>
												<div class="col-md-4">
													<SELECT class="form-control pay_online">
														<OPTION value="0">不开通</OPTION>
														<OPTION value="2">开通线上支付（个人）</OPTION>
														<OPTION value="1">开通线上支付（总部）</OPTION>
													</SELECT>
												</div>
											</div>
											<?php else:?>
											
											
										<?php endif;?>
									<?php }?>
									<input type="hidden" id="pay_online" name="pay_online" value="" />
									<div class="form-group">
										<?php echo $form->label($model, 'mobile',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'mobile',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mobile'),'disabled'=>$roleboom));?>
											<?php echo $form->error($model, 'mobile' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, '外卖电话',array('class' => 'col-md-3 control-label'));?>
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
										<label class="col-md-3 control-label">请选择省市县区</label>
										<div class="col-md-6">
											<select id="province" name="province" class="selectedclass"></select>
											<select id="city" name="city" class="selectedclass"></select>
											<select id="area" name="area" class="selectedclass"></select>
										</div>
									</div>
									<input type="hidden" id="province1" name="province1" value="" />
									<input type="hidden" id="city1" name="city1" value="" />
									<input type="hidden" id="area1" name="area1" value="" />
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
									<?php echo $form->hiddenField($model, 'lng',array('class' => 'form-control'));?>
									<?php echo $form->hiddenField($model, 'lat',array('class' => 'form-control'));?>
									
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
											<button type="button" id="su" class="btn blue"><?php echo yii::t('app','确定');?></button>
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
	
	new PCAS("province","city","area","<?php echo $model->province;?>","<?php echo $model->city;?>","<?php echo $model->county_area;?>");
	 $('#Company_type').change(function(){ 
	 //alert($(this).children('option:selected').val()); 
	 var p1=$(this).children('option:selected').val();//这就是selected的值 
		//alert(p1);
		 if(p1=="1"){
			 $("#yincang").show();
		 }else{
			$("#yincang").hide();
			 }
	
	 });

		function swfupload_callback(name,path,oldname)  {
			$("#Company_logo").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
		function theLocation(result){
			var cityName = result.name;
			map.centerAndZoom(cityName,11);
		}
		// 腾讯地图API功能
		var geocoder,map,marker = null;
		var init = function() {
			    var center = new qq.maps.LatLng(31.21323,121.31706);
			    map = new qq.maps.Map($('#allmap')[0],{
			        center: center,
			        zoom: 13
			    });
			    var marker = new qq.maps.Marker({
			        position: center,
			        map: map
			    });
			    //调用地址解析类
			    geocoder = new qq.maps.Geocoder({
			        complete : function(result){
			            map.setCenter(result.detail.location);
			            var lat = result.detail.location.lat;
			            var lng = result.detail.location.lng;
			            $('#Company_lng').val(lng);
			            $('#Company_lat').val(lat);
			            marker.setVisible(false);
			            marker = new qq.maps.Marker({
			                map:map,
			                position: result.detail.location
			            });
			        }
			    });
			}
		function codeAddress(address) {
		    //通过getLocation();方法获取位置信息值
		    geocoder.getLocation(address);
		}
		$(document).ready(function(){
			
			init();
			var province = $('#province').children('option:selected').val();
	        var city = $('#city').children('option:selected').val();
	        var area = $('#area').children('option:selected').val();
			var address = $('#Company_address').val();
			if(city == '市辖区'|| city == '省直辖县级行政区划' || city == '市辖县'){
				city = '';
				}
			if(area == '市辖区'){
				area = '';
				}
			var real_address = province+city+area+address;	
			codeAddress(real_address);
			
			$('.getLocation').click(function(){
				province = $('#province').children('option:selected').val();
		        city = $('#city').children('option:selected').val();
		        area = $('#area').children('option:selected').val();
				address = $('#Company_address').val();
				if(city == '市辖区'|| city == '省直辖县级行政区划' || city == '市辖县'){
					city = '';
					}
				if(area == '市辖区'){
					area = '';
					}
				real_address = province+city+area+address;
				// 创建地址解析器实例
				codeAddress(real_address);
			});
		     $("#su").on('click',function(){

		    	 var pay_online = $('.pay_online').children('option:selected').val();
		         //alert(pay_online);
		         //return false;
		         var province = $('#province').children('option:selected').val();
		         var city = $('#city').children('option:selected').val();
		         var area = $('#area').children('option:selected').val();
		         
		         var dayendzero = "00:00";
		       
		         if(province == null || province == 'undefind' || province == ''){
		        	 alert("<?php echo yii::t('app','请填写店铺所处省市信息。。。');?>");
		        	 return false;
		         }
		         //alert(province);return false;
		         $("#pay_online").val(pay_online);
		         $("#province1").val(province);
		         $("#city1").val(city);
		         $("#area1").val(area);
		         $("#company-form").submit();
		     });

		});
	</script>							