	<!-- BEGIN PAGE --> 
	<style>
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
	</style> 
		<div class="page-content">
			
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE HEADER-->   
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('companyset/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','登陆界面设置'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('companyset/list' , array('companyId' => $this->companyId,)))));?>
			
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box purple">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><span class="tab tab-active"><?php echo yii::t('app','登陆界面设置');?></span></div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  