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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','进销存管理'),'subhead'=>yii::t('app','盘点记录详情列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','盘点记录'),'url'=>$this->createUrl('stocktakinglog/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','盘点记录详情'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('stocktakinglog/index' , array('companyId' => $this->companyId,)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				//'action' => $this->createUrl('storageOrder/detailDelete' , array('companyId' => $this->companyId,'slid'=>$slid,'status'=>$status,)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','盘点记录详情列表');?></div>
					
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','品项名称');?></th>
								<th><?php echo yii::t('app','原始库存');?></th>
								<th><?php echo yii::t('app','盘点库存');?></th>
								<th><?php echo yii::t('app','盈亏差值');?></th>
								<th><?php echo yii::t('app','原因备注');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<div style="display: none;" id="storagedetail" val="1"></div>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td style="width:16%"><?php echo Common::getmaterialName($model->material_id);?></td>
								<td><?php echo $model->reality_stock;?></td>
								<td ><?php echo $model->taking_stock;?></td>
								<td><?php echo $model->number;?></td>
								<td><?php echo $model->reasion;?></td>
								<td class="center">
								
								</td>
							</tr>
						<?php endforeach;?>
						<?php else:?>
						<div style="display: none;" id="storagedetail" val="0"></div>
						<?php endif;?>
						</tbody>
					</table>
					
					
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('#storage-in').click(function(){
			var id = $(this).attr('storage-id');
			var storagedetail = $('#storagedetail').attr('val');
			
			if(storagedetail == 1){
			$.ajax({
					url:'<?php echo $this->createUrl('storageOrder/storageIn' , array('companyId'=>$this->companyId));?>',
					data:{sid:id},
					success:function(msg){
						if(msg=='true'){
						   alert('入库成功!');		
						}else{
							alert('入库失败!');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId,));?>";
					},
				});
			}else{
					alert('请添加需要入库的品项');
				}
		});
		$('#status-0').click(function(){
			var pid = $(this).attr('storage-id');
			var storagedetail = $('#storagedetail').attr('val');
			
			if(storagedetail == 1){
			if(confirm('确认送审该入库单')){
				$.ajax({
					url:'<?php echo $this->createUrl('storageOrder/storageVerify',array('companyId'=>$this->companyId,'status'=>4));?>',
					data:{type:4,pid:pid},
					success:function(msg){
						if(msg=='true'){
							alert('送审成功');
						}else{
							alert('送审失败');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId,));?>";
					}
				});
			}
			}else{
				alert('请添加需要入库的详细品项');
				}
		});
		$('#status-1').click(function(){
			var pid = $(this).attr('storage-id');
			var storagedetail = $('#storagedetail').attr('val');
			
			if(storagedetail == 1){
			if(confirm('确认驳回该入库单')){
				$.ajax({
					url:'<?php echo $this->createUrl('storageOrder/storageVerify',array('companyId'=>$this->companyId,'status'=>4));?>',
					data:{type:2,pid:pid},
					success:function(msg){
						if(msg=='true'){
							alert('驳回成功');
						}else{
							alert('驳回失败');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId,));?>";
					}
				});
			}
			}else{
				alert('请添加需要入库的详细品项');
				}
		});
		$('#status-2').click(function(){
			var pid = $(this).attr('storage-id');
			var storagedetail = $('#storagedetail').attr('val');
			
			if(storagedetail == 1){
			if(confirm('确认重新送审该入库单')){
				$.ajax({
					url:'<?php echo $this->createUrl('storageOrder/storageVerify',array('companyId'=>$this->companyId,'status'=>4));?>',
					data:{type:4,pid:pid},
					success:function(msg){
						if(msg=='true'){
							alert('重新审核成功');
						}else{
							alert('重新审核失败');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId,));?>";
					}
				});
			}
			}else{
				alert('请添加需要入库的详细品项');
				}
		});
		$('#status-4').click(function(){
			var pid = $(this).attr('storage-id');
			
			if(confirm('确认审核入库单')){
				$.ajax({
					url:'<?php echo $this->createUrl('storageOrder/storageVerify',array('companyId'=>$this->companyId,'status'=>1));?>',
					data:{type:1,pid:pid},
					success:function(msg){
						if(msg=='true'){
							alert('审核成功');
						}else{
							alert('审核失败');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId,));?>";
					}
				});
			}
			
		});

		
	});
	</script>	