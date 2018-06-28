<style>
	.modal-dialog{
		width: 1124px;
		height: 80%;
	}
	.error{
		color:red;
	}
</style>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">未找到连接</h4>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
				<div class="modal-footer">
					<button type="button" class="btn default" data-dismiss="modal">关闭</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','进销存管理'),'subhead'=>yii::t('app','入库单详情列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('tmall/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','入库单管理'),'url'=>$this->createUrl('storageOrder/ckindex' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','入库单详情'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('storageOrder/ckindex' , array('companyId' => $this->companyId)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('storageOrder/ckdetailDelete' , array('companyId' => $this->companyId,'slid'=>$slid,'status'=>$status,)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','入库单详情列表');?></div>
					<div class="actions">
	                    <?php if($storage->status!=3):?>
	                    <a href="javascript:void(0);" class="btn blue add_btn " pid="<?php echo $slid;?>" compid="<?php echo $dpid;?>"><i class="fa fa-pencil"></i> <?php echo yii::t('app','批量添加');?></a>
	                    <a href="<?php echo $this->createUrl('storageOrder/ckdetailcreate' , array('companyId' => $this->companyId, 'lid'=>$slid));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
	                    <div class="btn-group">
	                    	<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
	                    </div>
						<?php endif;?>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','商品名称');?></th>
								<th><?php echo yii::t('app','入库价格');?></th>
								<th><?php echo yii::t('app','入库数量');?></th>
								<th><?php echo yii::t('app','赠品数量');?></th>
								<th><?php echo yii::t('app','批次编号');?></th>
								<th><?php echo yii::t('app','库存天数');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<div style="display: none;" id="storagedetail" val="1"></div>
						<?php 
							foreach ($models as $model):
							$goods = Common::getgoodsName($model->material_id);
						?>
							<tr class="goods odd gradeX" is-batch="<?php echo $goods['is_batch'];?>">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]"/></td>
								<td style="width:16%"><span class="goods_name"><?php echo $goods['goods_name'];?></span></td>
								<td class="price"><?php echo $model->price;?></td>
								<td ><?php echo $model->stock;?></td>
								<td><?php echo $model->free_stock;?></td>
								<td class="batch_code"><?php echo $model->batch_code;?></td>
								<td><?php echo $model->stock_day;?></td>
								<td class="center">
								<?php if($status == 0 || $status == 2):?>
									<a href="<?php echo $this->createUrl('storageOrder/ckdetailupdate',array('lid' => $model->lid , 'slid'=>$model->storage_id,  'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								<?php endif;?>
								</td>
							</tr>
						<?php endforeach;?>
						<?php else:?>
						<div style="display: none;" id="storagedetail" val="0"></div>
						<?php endif;?>
							<tr>
								<td colspan="8" style="text-align: right;">
								<?php if($storage->status==0):?>
								<input id="status-0" type="button" class="btn blue" value="确认入库" storage-id="<?php echo $storage->lid;?>" cfv="1"/>
								<?php else:?>
								<span style="color:red">已入库</span>
								<?php endif;?>
								</td>
							</tr>
						</tbody>
					</table>
					</div>
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
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		var a = 0;
		$('#status-0').click(function(){
			var canStorage = true;
			var pid = $(this).attr('storage-id');
			var storagedetail = $('#storagedetail').attr('val');
			var cfv = $(this).attr('cfv');
			$(".goods").each(function(){
				var isbatch = $(this).attr('is-batch');
				var batchcode = $(this).find('.batch_code').html();
				if(isbatch==1&&batchcode==''){
					canStorage = false;
					$(this).find('.goods_name').addClass('error');
				}else{
					$(this).find('.goods_name').removeClass('error');
				}
			});
			if(!canStorage){
				alert('请输入批次号');
				return;
			}
			if(confirm("确认入库？")){
				if(storagedetail == 1 && a == 0 && cfv){
					$.ajax({
						url:'<?php echo $this->createUrl('storageOrder/ckstorageIn' , array('companyId'=>$this->companyId));?>',
						data:{sid:pid},
						success:function(msg){
							if(msg=='true'){
							   alert('入库成功!');	
							   location.href="<?php echo $this->createUrl('storageOrder/ckindex' , array('companyId'=>$this->companyId,));?>";
							}else{
								alert('入库失败2!');
							}
						},
					});
					$(this).attr('cfv',0);	a = 1 ;
				}else{
					alert('请添加需要入库的详细品项或重复提交');
				}
			}
			
		});

    var $modal = $('.modal');
    $('.add_btn').on('click', function(){
    	pid = $(this).attr('pid');
    	compid = $(this).attr('compid');
    	
        $modal.find('.modal-content').load('<?php echo $this->createUrl('storageOrder/ckbatchcreate',array('companyId'=>$this->companyId));?>/pid/'+pid,'' , function(){
          $modal.modal();
        });
    });

		
	});
    
</script>	