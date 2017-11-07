
<style>
		span.tab{
			color: black;
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
			border:2px solid black;
			box-shadow: 5px 5px 5px #888888;
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.ku-purple{
			background-color:#852b99;
		}
		.ku-grey{
			background-color:rgb(68,111,120);
		}
	</style>
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

		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','进销存'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','运输损耗列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId' => $this->companyId)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">

		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption">
						<span class="tab tab-active"><?php echo yii::t('app','运输损耗列表');?></span>
					</div>
					<div class="actions">
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<?php if($models):?>
						<thead>
							<tr>
								<!-- <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th> -->
								<th><?php echo yii::t('app','仓库名称');?></th>
								<th><?php echo yii::t('app','店铺名称');?></th>
								<th><?php echo yii::t('app','商品名称');?></th>
								<th><?php echo yii::t('app','产品图片');?></th>
								<th><?php echo yii::t('app','退货人');?></th>
								<th><?php echo yii::t('app','订单号 & 配送号');?></th>
                                <th><?php echo yii::t('app','商品单价');?></th>
                                <th><?php echo yii::t('app','退货数量');?></th>
                                <th><?php echo yii::t('app','退款金额');?></th>
                                <th><?php echo yii::t('app','是否退钱');?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<!-- <td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="lid[]" /></td> -->
								<td><?php echo $model['depot_name'];?></td>
								<td><?php echo $model['store_name'];?></td>
								<td><?php echo $model['goods_name'];?></td>
								<td><img width="100" src="<?php if($model['main_picture']){echo $model['main_picture'];}else{echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>" alt=""></td>
								<td><?php echo $model['username'];?></td>
								<td><?php echo $model['goods_order_accountno'].'<br>&<br>'.$model['invoice_accountno'];?></td>
								<td><?php echo $model['price'];?></td>
								<td><?php echo $model['num'];?></td>
								<td><?php echo $model['price']*$model['num'];?></td>
                                <td class="center">
								<?php if($model['status']==0): ?>
									<button class="status btn red" lid="<?php echo $model['lid'];?>" status="<?php echo $model['status']; ?>"> 未退款</button>
								<?php else: ?>
									<button class="status btn green" lid="<?php echo $model['lid'];?>" status="<?php echo $model['status']; ?>"> 已退款</button>
								<?php endif; ?>

								</td>
							</tr>
						<?php endforeach;?>
						<?php else: ?>
							<tr class="odd gradeX"><td>暂时没有损耗信息</td></tr>
						<?php endif;?>
						</tbody>
					</table>

				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
	<script>
	$('.status').click(function() {
		if (confirm('是否修改损耗产品的退款状态?')) {
			var lid = $(this).attr('lid');
			var status = $(this).attr('status');
			$(this).attr('id','aa');
			$.ajax({
				url: '<?php echo $this->createUrl('goodsmaterialback/changestatus',array('companyId'=>$this->companyId))?>',
				type: 'POST',
				dataType: 'json',
				data: {lid: lid,status:status},
				success:function(data){
					if(data){
						var clas = $('#aa').attr('class');
						if( clas == 'status btn red'){
							$('#aa').removeClass('status btn red');
							$('#aa').addClass('status btn green');
							$('#aa').text('');
							$('#aa').text('已退款');
							$('#aa').attr('status',1);
							$('#aa').removeAttr("id");
						}else{
							$('#aa').removeClass('status btn green');
							$('#aa').addClass('status btn red');
							$('#aa').text('');
							$('#aa').text('未退款');
							$('#aa').attr('status',0);
							$('#aa').removeAttr("id");
						}
					}else{
						layer.msg("因网络问题修改失败,请重新修改");
						$('#aa').removeAttr("id");
					}
				},
			});

		}
	});
	</script>
	<!-- END PAGE CONTENT-->