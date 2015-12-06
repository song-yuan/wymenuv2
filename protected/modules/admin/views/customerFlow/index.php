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
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'head'=>yii::t('app','营销分析'),'subhead'=>'查看客流分析','breadcrumbs'=>array(array('word'=>'营销分析','url'=>''),array('word'=>'查看客流分析','url'=>''))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
		<div class="row">
		
		<div class="col-md-12">
		<div class="tabbable tabbable-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('synchronous/index',array('companyId'=>$this->companyId ,'type'=>"manul"));?>'" data-toggle="tab"><?php echo yii::t('app','查看客流分析');?></a></li>
			</ul>
		

			<div class="tab-content">
				<div class="col-md-12">
				
				
					
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','客流分析');?></div>
						</div>
						<div class="portlet-body form">
						<div class="form-body">		
						<div class="form-group">
							
						</div>
							<div class="alert alert-danger">
								<div class="">
                                                                    1：您无权限查看此操作！！！<br>                                                                    
								</div>
							</div>
						</div>
						</div>
						</div>
					

					
				
				</div>
			</div>
		</div>
		</div>
		</div>
</div>	<!-- END EXAMPLE TABLE PORTLET-->
	
