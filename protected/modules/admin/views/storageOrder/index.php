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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','入库单'),'subhead'=>yii::t('app','入库单列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','入库单'),'url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('storageOrder/delete' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','入库单列表');?></div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('storageOrder/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
						<a href="<?php echo $this->createUrl('bom/stock' , array('companyId' => $this->companyId));?>" class="btn blue"> <?php echo yii::t('app','返回');?></a>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','厂商名称');?></th>
								<th><?php echo yii::t('app','组织名称');?></th>
								<th><?php echo yii::t('app','入库单号');?></th>
								<th><?php echo yii::t('app','管理员');?></th>
								<th><?php echo yii::t('app','订货单号');?></th>
								<th><?php echo yii::t('app','入库日期');?></th>
								<th><?php echo yii::t('app','备注');?></th>
								<th><?php echo yii::t('app','审核状态');?></th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td style="width:16%"><?php echo $model->mfrInfor->manufacturer_name;?></td>
								<td ><?php echo $model->organization->organization_name;?></td>
								<td><?php echo $model->storage_account_no;?></td>
								<td><?php echo $model->admin->username;?></td>
								<td><?php echo $model->purchase_account_no;?></td>
								<td><?php echo $model->storage_date;?></td>
								<td><?php echo $model->remark;?></td>
								<td><?php if ($model->status==0) echo "未审核"; elseif ($model->status==1) echo "已审核"?></td>
								<td class="center">
									<a href="<?php echo $this->createUrl('/admin/storageOrderDetail/index',array('id' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								</td>
								<td class="center">
								<a href="<?php echo $this->createUrl('storageOrder/update',array('id' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
			<!-- test start -->
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:16%">大白兔</td>
								<td >哇哈哈</td>
								<td>132535109856845282599</td>
								<td>admin</td>
								<td>324264201604114387950</td>
								<td>2016.04.11</td>
								<td></td>
								<td>未审核</td>
								<td class="center">
									<a href="<?php echo $this->createUrl('/admin/storageOrderDetail/index',array('companyId'=>$this->companyId));?>"><?php echo yii::t('app','订单详情');?></a>
								</td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:16%">山西老陈醋</td>
								<td >糖心店</td>
								<td>132535109856845282599</td>
								<td>admin</td>
								<td>324264201604114387950</td>
								<td>2016.04.11</td>
								<td></td>
								<td>未审核</td>
								<td class="center">
									<a href="<?php echo $this->createUrl('/admin/storageOrderDetail/index',array('companyId'=>$this->companyId));?>"><?php echo yii::t('app','订单详情');?></a>
								</td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:16%">砂糖</td>
								<td >小龙虾</td>
								<td>132535109856845282599</td>
								<td>admin</td>
								<td>324264201604114387950</td>
								<td>2016.04.11</td>
								<td></td>
								<td>未审核</td>
								<td class="center">
									<a href="<?php echo $this->createUrl('/admin/storageOrderDetail/index',array('companyId'=>$this->companyId));?>"><?php echo yii::t('app','订单详情');?></a>
								</td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:16%">米醋</td>
								<td >哇哈哈</td>
								<td>132535109856845282599</td>
								<td>admin</td>
								<td>324264201604114387950</td>
								<td>2016.04.11</td>
								<td></td>
								<td>未审核</td>
								<td class="center">
									<a href="<?php echo $this->createUrl('/admin/storageOrderDetail/index',array('companyId'=>$this->companyId));?>"><?php echo yii::t('app','订单详情');?></a>
								</td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:16%">西王玉米胚芽油</td>
								<td >糖心店</td>
								<td>132535109856845282599</td>
								<td>admin</td>
								<td>324264201604114387950</td>
								<td>2016.04.11</td>
								<td></td>
								<td>未审核</td>
								<td class="center">
									<a href="<?php echo $this->createUrl('/admin/storageOrderDetail/index',array('companyId'=>$this->companyId));?>"><?php echo yii::t('app','订单详情');?></a>
								</td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="" name="ids[]" /></td>
								<td style="width:16%">小磨香油</td>
								<td >小龙虾</td>
								<td>132535109856845282599</td>
								<td>admin</td>
								<td>324264201604114387950</td>
								<td>2016.04.11</td>
								<td></td>
								<td>未审核</td>
								<td class="center">
									<a href="<?php echo $this->createUrl('/admin/storageOrderDetail/index',array('companyId'=>$this->companyId));?>"><?php echo yii::t('app','订单详情');?></a>
								</td>
								<td class="center">
								<a href="#">编辑</a>
								</td>
							</tr>
			<!-- test end -->
						</tbody>
					</table>
					<!-- 分页（测式） -->
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<div class="dataTables_info">共 1 页 , 6 条数据 , 当前是第 1 页</div>
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
		$('#material-form').submit(function(){
			if(!$('.checkboxes:checked').length){
				alert("<?php echo yii::t('app','请选择要删除的项');?>");
				return false;
			}
			return true;
		});
		$('.s-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('storageOrder/status',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('.r-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('storageOrder/recommend',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
	</script>	