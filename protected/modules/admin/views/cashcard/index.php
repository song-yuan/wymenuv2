<!-- 		<script type="text/javascript" src="metronic/plugins/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="metronic/plugins/select2/select2_metro.css" />
		<link rel="stylesheet" type="text/css" href="metronic/plugins/select2/inserthtml.com.radios.css" />
		<script src="metronic/plugins/bootbox/bootbox.min.js" type="text/javascript" ></script>
		 --><!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
				<div class="modal-footer">
					<button type="button" class="btn blue">Save changes</button>
					<button type="button" class="btn default" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
			<!-- /.modal -->
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'breadcrumbs'=>array(array('word'=>yii::t('app','线上活动'),'url'=>$this->createUrl('discount/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>'营销品设置','url'=>''),array('word'=>'整体设置','url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('discount/list' , array('companyId' => $this->companyId,'type'=>1)))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
		<div class="row">
		
		<div class="col-md-12">
		<div class="tabbable tabbable-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cashcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','整体设置');?></a></li>
				<!-- <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('normalpromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','普通优惠');?></a></li> -->
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('privatepromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','特价优惠');?></a></li>
				<!-- <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('fullSentPromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','满送优惠');?></a></li>
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('fullMinusPromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','满减优惠');?></a></li> -->
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cupon/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','代金券');?></a></li>
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('gift/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','礼品券');?></a></li>
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('wxcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','微信卡券');?></a></li>
			</ul>
		
			  <div class="tab-content">
				<div class="col-md-12">
				<?php if($a=="1"):{?>
				<?php if($models) :{?>
				<?php foreach ($models as $model):?>
				<?php ?>
				<?php $form=$this->beginWidget('CActiveForm', array(
						'id' => 'totalpromotion-form',
						'action' => $this->createUrl('cashcard/update' , array('lid' => $model->lid ,'companyId' => $this->companyId)),
						'errorMessageCssClass' => 'help-block',
						'htmlOptions' => array(
							'class' => 'form-horizontal',
							'enctype' => 'multipart/form-data'
						),
				)); ?>
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','优惠活动整体设置');?></div>
						</div>
						<div class="portlet-body form">
						<div class="form-body">
						
							<div class="form-group">
										<?php echo $form->label($model, yii::t('app','普通优惠是否有效'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_normal_promotion', array('0' => yii::t('app','所有普通优惠对客人可用') , '1' => yii::t('app','禁止客人使用所有普通优惠')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_normal_promotion')));?>
											<?php echo $form->error($model, 'is_normal_promotion' )?>
										</div>
									</div><!-- 普通优惠是否有效 -->
							<div class="form-group">
										<?php echo $form->label($model, yii::t('app','特价优惠是否有效'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_private_promotion', array('0' => yii::t('app','所有特价优惠对客人可用') , '1' => yii::t('app','禁止客人使用所有特价优惠')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_private_promotion')));?>
											<?php echo $form->error($model, 'is_private_promotion' )?>
										</div>
									</div><!-- 特价优惠是否有效 -->
							<div class="form-group">
										<?php echo $form->label($model, yii::t('app','设置代金券使用次数（每天）'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'is_cupon',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('负1表示无限制，0表示禁止使用，大于0表示每天限制次数')));?>
											<?php echo $form->error($model, 'is_cupon' )?>
										</div>
										<div class="col-md-4" style="color: red;"><?php echo  yii::t('app','注：-1表示无限制，0表示禁止使用，大于0表示每天限制次数'); ?></div>
									</div><!-- 代金券是否有效 -->
							<div class="form-group">
										<?php echo $form->label($model, yii::t('app','充值返现是否有效'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_cash', array('0' => yii::t('app','充值返现对客人可用') , '1' => yii::t('app','充值不返现')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_cash')));?>
											<?php echo $form->error($model, 'is_cash' )?>
										</div>
									</div><!-- 充值返现是否有效 -->		
							<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定修改并保存');?></button>
										<!--  <a href="<?php echo $this->createUrl('cashcrad/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>  -->                            
										</div>
									</div>
						</div>
						</div>
						</div>
						
						
							<!-- 
							<div class="form-group">
								<DIV class = "col-md-3 control-label" ><?php  echo yii::t('app','普通优惠是否有效');?></div>
								<div class="col-md-4">
									<div class="form-control" >
										<php switch ($model->is_normal_promotion){case 0:echo yii::t('app','有效');break;case 1:echo yii::t('app','无效');break;default:echo "";break;};?>
									</div>
									<div class="center" style="width: 180px;">
                    				<input type="checkbox" id="cashcard1" /><label for="checkbox-10-1"></label>
                					</div>	
								</div>
							</div>
							<div class="form-group">
								<DIV class = "col-md-3 control-label" ><?php  echo yii::t('app','特价优惠是否有效');?></div>
								<div class="col-md-4">
									<div class="form-control" >
										<php switch ($model->is_private_promotion){case 0:echo yii::t('app','有效');break;case 1:echo yii::t('app','无效');break;default:echo "";break;};?>
									</div>
									<div class="center" style="width: 180px;">
                    				<input type="checkbox" id="cashcard2" /><label for="checkbox-10-1"></label>
                					</div>	
								</div>
							</div>
							<div class="form-group">
								<DIV class = "col-md-3 control-label" ><?php  echo yii::t('app','代金券是否有效');?></div>
								<div class="col-md-4">
									<div class="form-control" >
										<php switch ($model->is_cupon){case 0:echo yii::t('app','有效');break;case 1:echo yii::t('app','无效');break;default:echo "";break;};?>
									</div>
									<div class="center" style="width: 180px;">
                    				<input type="checkbox" id="cashcard3" /><label for="checkbox-10-1"></label>
                					</div>	
								</div>
							</div>
							<div class="form-group">
								<DIV class = "col-md-3 control-label" ><?php  echo yii::t('app','充值返现是否有效');?></div>
								<div class="col-md-4">
									<div class="form-control" >
										<php switch ($model->is_cash){case 0:echo yii::t('app','有效');break;case 1:echo yii::t('app','无效');break;default:echo "";break;};?>
									</div>
									<div class="center" style="width: 180px;">
                    				<input type="checkbox" id="cashcard4" /><label for="checkbox-10-1"></label>
                					</div>	
								</div>
							</div>
							
							<div class="form-actions fluid">
								<div class="col-md-offset-3 col-md-9">
									<a href="<php echo $this->createUrl('cashcard/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>" class="btn blue"><?php echo yii::t('app','编辑');?></a>
								</div>
							</div> -->
				<?php $this->endWidget(); ?>
				<?php endforeach;?>
				<?php }endif;?>
				<?php }elseif($a=="2"):{?>
						
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','优惠活动整体设置');?></div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<?php $form=$this->beginWidget('CActiveForm', array(
								'id' => 'totalpromotion-form',
								'action' => $this->createUrl('cashcard/create' , array('lid' => $model->lid ,'companyId' => $this->companyId)),
								'errorMessageCssClass' => 'help-block',
								'htmlOptions' => array(
									'class' => 'form-horizontal',
									'enctype' => 'multipart/form-data'
								),
							)); ?>
							<div class="form-body">
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','普通优惠是否有效'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_normal_promotion', array('0' => yii::t('app','有效') , '1' => yii::t('app','无效')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_normal_promotion')));?>
											<?php echo $form->error($model, 'is_normal_promotion' )?>
										</div>
									</div><!-- 普通优惠是否有效 -->
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','特价优惠是否有效'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_private_promotion', array('0' => yii::t('app','有效') , '1' => yii::t('app','无效')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_private_promotion')));?>
											<?php echo $form->error($model, 'is_private_promotion' )?>
										</div>
									</div><!-- 特价优惠是否有效 -->
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','设置代金券使用次数（每天）'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'is_cupon',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('负1表示无限制，0表示禁止使用，大于0表示每天限制次数')));?>
											<?php echo $form->error($model, 'is_cupon' )?>
										</div>
										<div class="col-md-4" style="color: red;"><?php echo  yii::t('app','注：-1表示无限制，0表示禁止使用，大于0表示每天限制次数'); ?></div>
									</div><!-- 代金券是否有效 -->
									<div class="form-group">
										<?php echo $form->label($model, yii::t('app','返现和充值是否有效'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_cash', array('0' => yii::t('app','有效') , '1' => yii::t('app','无效')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_cash')));?>
											<?php echo $form->error($model, 'is_cash' )?>
										</div>
									</div><!-- 返现和充值是否有效 -->
										
										<div class="form-actions fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-offset-3 col-md-9">
														<button type="submit" class="btn blue"><?php echo yii::t('app','首次设置并保存');?></button>
													</div>
												</div>
											</div>
										</div>
							</div>
						</div>
					</div>
						
							<?php $this->endWidget(); ?>
							<?php }endif;?>
							 
							
							
							
		
				
				</div>
				</div>
				</div>
			</div>
		</div>
        </div>
		
	
					<!-- END EXAMPLE TABLE PORTLET-->
				


