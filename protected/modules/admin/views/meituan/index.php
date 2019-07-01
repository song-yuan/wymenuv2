<style type="text/css">
span.tab{
			color: black;
			border-right:1px dashed white;
			margin-right:10px;
			padding-right:10px;
			display:inline-block;
		}
		span.tab-active{
			color:white;
		}
		.ku-item{
			width:100px;
			height:100px;
			margin-right:20px;
			margin-top:20px;
			margin-left:20px;
			border-radius:5px !important;
			/*border:2px solid black;*/
			/*box-shadow: 5px 5px 5px #888888;*/
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.ku-purple{
			/*background-color:#852b99;*/
		}
		.ku-grey{
			background-color:rgb(68,111,120);
		}
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
	
</style>	
	<div class="page-content">
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','美团外卖'),'url'=>$this->createUrl('meituan/index' , array('companyId'=>$this->companyId)))),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('waimai/list' , array('companyId' => $this->companyId,'type' => '0')))));?>
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('meituan/index' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
		<div class="form-body">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','美团外卖');?></div>
				</div>
				<div class="portlet-body clearfix">
					<div class="panel_body form row">
						<?php if($this->comptype==1):?>
							<?php if($model):?>
	                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
	                            <a href="<?php echo $this->createUrl('meituan/productDy',array('companyId'=>$this->companyId));?>">
	                                <div class="list_big">菜品对应</div>
	                                <div class="list_small">美团外卖中菜品与收款机菜品相对应</div>
	                            </a> 
	                        </div>
	                        <?php else:?>
	                        <div class="col-xs-12">
	                        <span>请先联系公司绑定店铺后再来进行菜品对应。</span>
	                        </div>
	                        <?php endif;?>
						<?php else:?>
							<div class="form-group">
							<?php echo $form->label($model, 'app_id',array('class' => 'col-md-3 control-label'));?>
								<div class="col-md-4">
								<?php echo $form->textField($model, 'app_id', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('app_id')));?>
								</div>
							</div>
							<div class="form-group">
							<?php echo $form->label($model, 'app_secret',array('class' => 'col-md-3 control-label'));?>
								<div class="col-md-4">
								<?php echo $form->textField($model, 'app_secret', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('app_secret')));?>
								</div>
							</div>
							<div class="form-actions fluid">
								<div class="col-md-offset-3 col-md-9">
									<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>                   
								</div>
							</div>
						<?php endif;?>
                    </div>
				</div>
			</div>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>