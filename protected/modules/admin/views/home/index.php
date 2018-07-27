
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'breadcrumbs'=>array(array('word'=>yii::t('app','首页'),'url'=>''))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
	<div class="portlet purple box">
		<div class="portlet-title">
			<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','首页');?></div>
			<?php if($companys):?>
			<div class="actions">
				<div class="btn-group">
					<select class="form-control input-medium select2me sedpid" data-placeholder="请选择店铺...">
						<option value=""></option>
						<?php foreach ($companys as $company):?>
						<option value="<?php echo $company['dpid']?>" <?php if($dpid==$company['dpid']){ echo 'selected="selected"';}?>><?php echo $company['company_name']?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			<?php endif;?>
		</div>
		<div class="portlet-body clearfix">
			<h2>营业数据</h2>
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat blue">
						<div class="visual">
							<i class="fa fa-ticket"></i>
						</div>
						<div class="details">
							<div class="number"><?php echo $order['all_order_num'];?></div>
							<div class="desc">订单数</div>
						</div>
						<a class="more" href="#">
						更多 <i class="m-icon-swapright m-icon-white"></i>
						</a>                 
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat green">
						<div class="visual">
							<i class="fa fa-yen"></i>
						</div>
						<div class="details">
							<div class="number"><?php echo $order['all_order_total']?$order['all_order_total']:0;?></div>
							<div class="desc">营业额</div>
						</div>
						<a class="more" href="#">
						更多 <i class="m-icon-swapright m-icon-white"></i>
						</a>                 
					</div>
				</div>
			</div>	
			<?php if($orderpay):?>
			<h2>支付方式</h2>
			<div class="row">
				<?php 
					foreach ($orderpay as $pay):
						if($pay['paytype']==11){
							continue;
						}
				?>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat blue">
						<div class="visual">
							<i class="fa"></i>
						</div>
						<div class="details">
							<div class="number"><?php echo $pay['pay_amount'];?></div>
							<?php if($pay['paytype']==0):?>
							<div class="desc"><?php echo '现金支付';?></div>
							<?php elseif($pay['paytype']==1):?>
							<div class="desc"><?php echo '微信支付';?></div>
							<?php elseif($pay['paytype']==2):?>
							<div class="desc"><?php echo '支付宝支付';?></div>
							<?php elseif($pay['paytype']==3):?>
							<div class="desc"><?php echo $pay['payment_method_name'];?></div>
							<?php elseif($pay['paytype']==4):?>
							<div class="desc"><?php echo '会员卡支付';?></div>
							<?php elseif($pay['paytype']==5):?>
							<div class="desc"><?php echo '银联支付';?></div>
							<?php elseif($pay['paytype']==8):?>
							<div class="desc"><?php echo '积分支付';?></div>
							<?php elseif($pay['paytype']==9):?>
							<div class="desc"><?php echo '微信代金券';?></div>
							<?php elseif($pay['paytype']==10):?>
							<div class="desc"><?php echo '储值支付';?></div>
							<?php elseif($pay['paytype']==12):?>
							<div class="desc"><?php echo '微信点单';?></div>
							<?php elseif($pay['paytype']==13):?>
							<div class="desc"><?php echo '微信外卖';?></div>
							<?php elseif($pay['paytype']==14):?>
							<div class="desc"><?php echo '美团外卖';?></div>
							<?php elseif($pay['paytype']==15):?>
							<div class="desc"><?php echo '饿了么外卖';?></div>
							<?php endif;?>
						</div>
						<a class="more" href="#">
						更多 <i class="m-icon-swapright m-icon-white"></i>
						</a>                 
					</div>
				</div>
				<?php endforeach;?>
			</div>	
			<?php endif;?>
		</div>
	</div>		
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.sedpid').change(function(){
		var dpid = $(this).val();
		if(dpid==''){
			dpid = 0;
		}
		location.href = '<?php echo $this->createUrl('home/index',array('companyId'=>$this->companyId));?>'+'/dpid/'+dpid;
	});
	
});
</script>				
