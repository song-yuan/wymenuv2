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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','进销存'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','采购单列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId' => $this->companyId,'type' => 0)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => '',
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','采购单列表');?></div>
					<div class="actions">
						<div class="btn-group">
							<input type="number" class="form-control" name="content" id="content" placeholder='<?php if ($content) {echo $content;}else{echo '请输入订单号';} ?>' style="width: 250px;">
						</div>
						<div class="btn-group">
							<span  class="btn blue" id="pnamebtn"><i class="glyphicon glyphicon-search"></i> <?php echo yii::t('app','查询');?></span>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
					<?php if($models):?>
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','订单来源');?></th>
								<th><?php echo yii::t('app','订单号');?></th>
								<th><?php echo yii::t('app','下单时间');?></th>
								<th><?php echo yii::t('app','订单总额');?></th>
								<th><?php echo yii::t('app','支付方式');?></th>
								<th><?php echo yii::t('app','订单状态');?></th>
								<th><?php echo yii::t('app','处理状态');?></th>
								<th>&nbsp;</th>
								<th><?php echo yii::t('app','是否确认收款');?></th>
							</tr>
						</thead>
						<tbody>

						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $model['lid']/1;?></td>
								<td><?php echo $model['company_name'];?></td>
								<td><b><?php echo $model['account_no'];?></b></td>
								<td><?php echo $model['create_at'];?></td>
								<td><?php echo $model['reality_total'];?></td>
								<td><?php switch ($model['paytype']){
									case 1 : echo '<span style="color:green">线上支付</span>';break;
									case 2 : echo '<span style="color:red">货到付款</span>';break;
									default: echo '未知';break;
								}?></td>
								<td>
								<?php 
								if ($model['order_status']==8) {
									echo '<span style="color:blue">已驳回</span>';
								}else{
									switch ($model['pay_status']){
										case 0 : echo '<span style="color:red">未支付</span>';break;
										case 1 : echo '<span style="color:green">已支付</span>';break;
										default: echo '未知';break;
									}
								}
								?>
								</td>
								<td>
								<?php
									if ($model['order_status']==8) {
										echo '<span style="color:green">已处理</span>';
									}else{
										if ($model['goods_order_accountno']=='') {
											echo '<span style="color:red">待处理</span>';
										}else{
											echo '<span style="color:green">已处理</span>';
										}
									}
									
								?>
								</td>
                                <td class="center">
									<a href="<?php echo $this->createUrl('goodsorder/detailindex',array('lid' => $model['lid'] ,'companyId' => $this->companyId, 'dpid' => $model['dpid'],'name' =>$model['company_name'], 'papage' => $pages->getCurrentPage()+1));?>"><?php echo yii::t('app','查看明细');?></a>
								</td>
								<td><?php if($model['order_status']!=8):?><a href="<?php echo $this->createUrl('goodsorder/updateorder',array('companyId' => $this->companyId,'account_no'=>$model['account_no']));?>" class="btn green">确认收款</a><?php else:?>　<?php endif;?></td>
							</tr>
						<?php endforeach;?>
						</tbody>
						<?php else:?>
						<tr><td><?php echo yii::t('app','未查询到订单');?></td></tr>
						<?php endif;?>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共 ');?><?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?><?php echo yii::t('app','页');?>
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
	<script>
		$('#pnamebtn').click(function(event) {
			var pname = $('#content').val();
				location.href='<?php echo $this->createUrl('goodsorder/index',array('companyId'=>$this->companyId))?>/content/'+pname;

		});
		document.onkeydown=function(event){
            var e = event || window.event || arguments.callee.caller.arguments[0];

            if(e && e.keyCode==13){ // enter 键
                 //要做的事情
			var pname = $('#content').val();
			// alert(pname);

				location.href='<?php echo $this->createUrl('goodsorder/index',array('companyId'=>$this->companyId))?>/content/'+pname;

            }
        };
	</script>