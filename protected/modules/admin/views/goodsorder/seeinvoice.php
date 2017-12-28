<style>
.none{display: none;}
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','进销存'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId'=>$this->companyId,'type'=>0))),array('word'=>yii::t('app','采购单列表'),'url'=>$this->createUrl('goodsorder/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','查看发货单'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('goodsorder/index' , array('companyId' => $this->companyId)))));?>

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
					<div class="caption"><i class="fa fa-globe"></i><?php echo $model['company_name'];?> => <?php echo yii::t('app','发货单明细列表');?></div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr style="background: lightblue;">
								<th><?php echo yii::t('app','发货单号');?></th>

								<th><?php echo yii::t('app','是否已出货');?></th>
                                <th><?php echo yii::t('app','发货仓库');?></th>
                                <th><?php echo yii::t('app','详情');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models):?>
							<?php foreach($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $model['delivery_accountno'];?></td>
								<td><?php if(empty($account)){ echo "配货中";}else{ echo "已发货";}?></td>
								<td><?php echo $model['company_name'];?></td>
								<td><?php if(!empty($account)):?><a class="btn green" href="<?php echo $this->createUrl('goodsorder/seeodo',array('companyId'=>$this->companyId,'delivery_accountno'=>$model['delivery_accountno'],'lid'=>$lid,'account_no'=>$account_no));?>">查看出库单</a><?else:?><input id="goods_deliveried" type="button" class="btn" disabled value="未生成出库单" />　<a class="btn green" href="<?php echo $this->createUrl('goodsorder/seedetails',array('companyId'=>$this->companyId,'delivery_accountno'=>$model['delivery_accountno'],'lid'=>$lid,'account_no'=>$account_no));?>">查看详情</a><?php endif;?></td>
							</tr>
							<?php endforeach;?>
						<?php else:?>
							<tr><?php echo yii::t('app','暂无数据');?></tr>
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
		$('.stockselect').on('change',function(){
			var cs = $('#changestock').val();
			cs = parseInt(cs);
			var lid = $(this).find("option:selected").attr('lid');
			var olddpid = $(this).find("option:selected").attr('olddpid');
			var stockid = $(this).find("option:selected").val();
			if(olddpid != stockid){
				cs = cs + 1;
				}else{
					cs = cs - 1;
				}
			$('#changestock').val(cs);
			//layer.msg(stockid);
		});

		$('#change_stock').click(function(){
			var str = '';
			$('.stockselect').each(function(){
				var lid = $(this).find("option:selected").attr('lid');
				var olddpid = $(this).find("option:selected").attr('olddpid');
				var stockid = $(this).find("option:selected").val();
				if(olddpid != stockid){
					str = str + lid +','+ stockid +';';
					}

			});
			if(str.length >0){
				str = str.substr(0,str.length-1);
			}else{
				layer.msg('未选择需要重新调整的仓库');
				return false;
			}
			if(confirm('确认调整仓库')){
				$.ajax({
					url:'<?php echo $this->createUrl('goodsorder/store',array('companyId'=>$this->companyId));?>',
					data:{pid:str},
					success:function(data){
						var msg = eval("("+data+")");
						if(msg.status=='success'){
							layer.msg(msg.msg);
						}else{
							alert('失败');
						}
						history.go(0);
					}
				});
			}
			//layer.msg(str);
		})
		$('#new_goods_delivery').click(function(){
			var pid = $('#stocks').val();
			var change = $('#changestock').val();
			//layer.msg(pid);return false;
			if(change >0){
				if(confirm('有商品调整仓库，尚未保存！若不保存，请继续操作！')){

					}else{
						return false;
					}
				}
			if(confirm('确认生成入库订单')){
				$.ajax({
					url:'<?php echo $this->createUrl('goodsorder/stockstore',array('companyId'=>$this->companyId));?>',
					data:{pid:pid},
					success:function(data){
						var msg = eval("("+data+")");
						if(msg.status=='success'){
							layer.msg(msg.msg);
						}else{
							layer.msg('生成失败');
						}
						history.go(0);
						//location.href="<?php echo $this->createUrl('purchaseOrder/index' , array('companyId'=>$this->companyId,));?>";
					}
				});
			}
		});


		$('#goods_back').on('click',function(){
			var attr = $('#back_reason').attr('class');
			if(attr == 'none'){
				$('#back_reason').removeAttr('class');
			}else {
				var back_reason = $('#goods_back_reason').val();
				var account_no = $(this).attr('gac');
				if(confirm('确认向店铺驳回该订单?')){
					$.ajax({
						url:'<?php echo $this->createUrl('goodsorder/orderCheck',array('companyId'=>$this->companyId));?>',
						data:{
							order_status:8,
							account_no:account_no,
							back_reason:back_reason,
						},
						success:function(data){
							var msg = eval("("+data+")");
							if(msg.status=='success'){
								layer.msg(msg.msg);
							}else{
								layer.msg('设置驳回失败');
							}
							history.go(0);
						}
					});
				}
			}
			
		});

		$('#goods_pass').click(function(){
			var account_no = $(this).attr('gac');
			if(confirm('确认通过店铺订单的审核?')){
				$.ajax({
					url:'<?php echo $this->createUrl('goodsorder/orderCheck',array('companyId'=>$this->companyId));?>',
					data:{
						order_status:7,
						account_no:account_no
					},
					success:function(data){
						var msg = eval("("+data+")");
						if(msg.status=='success'){
							layer.msg(msg.msg);
						}else{
							layer.msg('设置通过失败');
						}
						history.go(0);
					}
				});
			}
		});


		$('#excel').click(function excel(){
            var goid = $(this).attr('lid');
            // alert(goid);
            if(confirm('确认导出并且下载Excel文件吗？')){
                location.href="<?php echo $this->createUrl('goodsorder/detailindexExport' , array('companyId'=>$this->companyId));?>/goid/"+goid;
                // location.href="www.baidu.com";
            }
	    });
	});
	</script>