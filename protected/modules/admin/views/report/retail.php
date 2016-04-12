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
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','厂商零售查询'),'subhead'=>yii::t('app','查询列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','厂商零售查询'),'url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','查询列表');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php //echo CHtml::dropDownList('selectCategory', $orgclassId, $categories , array('class'=>'form-control'));?>
						</div>
						<a href="<?php echo $this->createUrl('report/index' , array('companyId' => $this->companyId));?>" class="btn blue"> <?php echo yii::t('app','返回');?></a>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','日期');?></th>
								<th><?php echo yii::t('app','厂商名称');?></th>
								<th><?php echo yii::t('app','品项名称');?></th>
								<th><?php echo yii::t('app','销售总额');?></th>
							</tr>
						</thead>
						<tbody>
						<?php //if($models) :?>
						<?php //foreach ($models as $model):?>
							<!-- <tr class="odd gradeX">
								<td><?php //echo $model->mfrInfor->manufacturer_name;?></td>
								<td><?php //echo $model->admin->username;?></td>
								<td><?php //echo $model->purchase_account_no;?></td>
								<td><?php //echo $model->organization_id;?></td>
							</tr> -->
						<?php //endforeach;?>
						<?php //endif;?>
							<tr class="odd gradeX">
								<td>2016.04.07</td>
								<td>大白兔</td>
								<td>糖</td>
								<td>2000</td>
							</tr>
							<tr class="odd gradeX">
								<td>2016.04.07</td>
								<td>巧媳妇</td>
								<td>醋</td>
								<td>5000</td>
							</tr>
							<tr class="odd gradeX">
								<td>2016.04.07</td>
								<td>渤海盐场</td>
								<td>盐</td>
								<td>3000</td>
							</tr>
						</tbody>
					</table>
					<!-- 分页（测式） -->
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<div class="dataTables_info">共 1 页 , 3 条数据 , 当前是第 1 页</div>
						</div>
						<div class="col-md-7 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap">
								<ul class="pagination pull-right" id="yw0">
									<li class=" disabled"><a href="#">&lt;&lt;</a></li>
									<li class=" disabled"><a href="#">&lt;</a></li>
									<li class=" active"><a href="#">1</a></li>
									<li class=""><a href="#">&gt;</a></li>
									<li class=""><a href="#">&gt;&gt;</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- 分页（测试） 结束 -->
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?> , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
								</div>
							</div>
							<div class="col-md-7 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap">
								<?php $this->widget('CLinkPager', array(
									'pages' => $pages,
									'header'=>'',
									'firstPageLabel' => '<<',
									'lastPageLabel' => '>>',
									'firstPageCssClass' => '',
									'lastPageCssClass' => '',
									'maxButtonCount' => 8,
									'nextPageCssClass' => '',
									'previousPageCssClass' => '',
									'prevPageLabel' => '<',
									'nextPageLabel' => '>',
									'selectedPageCssClass' => 'active',
									'internalPageCssClass' => '',
									'hiddenPageCssClass' => 'disabled',
									'htmlOptions'=>array('class'=>'pagination pull-right')
								));
								?>
								</div>
							</div>
						</div>
						<?php endif;?>					
					
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('purchaseOrder/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
	</script>	