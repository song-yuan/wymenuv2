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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','订单管理'),'subhead'=>yii::t('app','座位列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','订单管理'),'url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
				<div class="panel panel-success">
						<div class="panel-body">
							<form class="form-inline" id="payForm" role="form" action="">
								<div class="form-group">
									<label class="sr-only" for="exampleInputEmail2"><?php echo yii::t('app','座次号');?></label>
									<input type="text" class="form-control" id="site_no" placeholder="<?php echo yii::t('app','座次号');?>">
								</div>
								<span class="label label-primary" id="total" style="display:none;">&nbsp;</span>
								<a href="#"  id="viewButton" class="btn blue" ><?php echo yii::t('app','订单明细');?></a> 
								<a href="javascript:;"  onclick="location.reload();" class="btn blue"><i class="fa fa-refresh"></i> <?php echo yii::t('app','刷新');?></a>
								<div class="btn-group pull-right">
									<a href="<?php echo $this->createUrl('order/historyList',array('companyId'=>$this->companyId));?>" class="btn blue"><?php echo yii::t('app','历史订单');?></a>
								</div>
							</form>
						</div>
					</div>
				<?php if($models):?>
					<div class="tabbable tabbable-custom">
						<ul class="nav nav-tabs">
						<?php $key =0 ;foreach ($models as $model):?>
						<?php $key = $key+1 ;?>
							<li class="<?php if($key == 1) echo 'active' ; ?>"><a href="#tab_1_<?php echo $key;?>" data-toggle="tab"><?php echo $model->name ;?></a></li>
						<?php endforeach;?>	
						</ul>
						<div class="tab-content">
						<?php $key =0 ;foreach ($models as $model):?>
						<?php $key = $key+1 ;?>
							<div class="tab-pane glyphicons-demo <?php if($key == 1) echo 'active' ; ?>" id="tab_1_<?php echo $key;?>">
								<ul class="list-unstyled1">
								<?php if($model->site):?>
								<?php foreach ($model->site as $s):?>
									<li id="siteId_<?php echo $s->site_id;?>" class="siteItem <?php if($s->isfree) echo 'btn red';?>" siteId=<?php echo $s->site_id;?>><?php echo $s->serial ;?></li>
								<?php endforeach;?>
								<?php endif;?>
								</ul>
							</div>
						<?php endforeach;?>	
						</div>
					</div>
				<?php endif;?>
			</div>
		</div>
</div>
<script>
jQuery(document).ready(function(){
	$('.siteItem').on('click',function(){
		var siteId = $(this).attr('siteId');
		$.get('<?php echo $this->createUrl('order/getOrderId' , array('companyId' => $this->companyId));?>&id='+siteId , function(data){
			if(data.status){
				$('#site_no').val(data.serial);
				$('#payForm').attr('action','<?php echo $this->createUrl('order/pay' , array('companyId' => $this->companyId));?>&id='+data.order_id);
				$('#viewButton').attr('href' , '<?php echo $this->createUrl('order/update' , array('companyId' => $this->companyId));?>&id='+data.order_id);
				$('#total').html('￥'+data.total).show();
			} else {
				$('#site_no').val('');
				$('#payForm').attr('action','');
				$('#viewButton').attr('href' , '#');
				$('#total').hide();
			}
		},'json');
	});
	$('#payButton').click(function(){
		var url = $('#payForm').attr('action');
		if(url) {
			$.get(url , function(data){
				if(data.status) {
					$('#site_no').val('');
					$('#payForm').attr('action','');
					$('#siteId_'+data.siteId).removeClass('btn red');
					alert("<?php echo yii::t('app','结单成功');?>");
				}
			},'json');
		}
	});
});
</script>