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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','进销存'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId'=>$this->companyId,'type'=>0))),array('word'=>yii::t('app','采购单列表'),'url'=>$this->createUrl('goodsorder/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','采购单明细'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('goodsorder/index' , array('companyId' => $this->companyId,'page'=>$papage)))));?>
	
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo $name ;?> => <?php echo yii::t('app','采购单明细列表');?></div>
					<div class="actions">
					
					</div>
				</div>
				<?php if($model):?>
				<?php $plid = $model['lid'];$status = $model['order_status'];?>
				<div style="vertical-align:middle;text-align: center;" <?php echo $plid;?>>
					<div class="actions">
					<span style="font-weight: 600;font-size: 24px;"><?php echo $model['company_name'];?></span>
					</div>
					<div class="actions">
					<span>订单号：<?php echo $model['account_no'];?> </span>
					<span>&nbsp;</span>
					<span>总金额：<?php echo $model['reality_total'];?> </span>
					<span>&nbsp;</span>
					<span style="color: red;"><?php switch ($model['pay_status']){case 0: echo '未支付';break;case 1: echo '已支付';break;default:echo '';break;}?> </span>
					</div>
				</div>
				<?php endif;?>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
					
						<thead>
							<tr style="background: lightblue;">
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','名称');?></th>
                                <th><?php echo yii::t('app','价格');?></th>
                                <th><?php echo yii::t('app','数量');?></th>
                                <th><?php echo yii::t('app','发货仓库');?></th>
                                <?php if($status<=3):?>
                                <th><?php echo yii::t('app','调整仓库');?>
                                <input id="change_stock" type="button" value="一键保存" />&nbsp;
                                </th>
                                <?php endif;?>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models):?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td ></td>
								<td ><?php echo $model['goods_name'] ;?></td>
								<td ><?php echo $model['price'] ;?></td>
								<td ><?php echo $model['num'] ;?></td>
								<td ><?php echo $model['stock_name'] ;?></td>
								<?php if($status<=3):?>
								<td >
									<select id="paymentid" class="col-md-9 form-control stockselect" >
			                            <?php if($stocks):?>
			                            <?php foreach ($stocks as $stock):?>
			                            <option lid="<?php echo $model['lid'];?>" olddpid="<?php echo $model['stock_dpid'];?>" value="<?php echo $stock['dpid'];?>" <?php if ($model['stock_dpid'] == $stock['dpid']){?> selected="selected" <?php }?> ><?php echo $stock['company_name'];?></option>
			                            <?php endforeach;?>
			                            <?php endif;?>
	                    			</select>
                    			</td>
                    			<?php endif;?>
								<td></td>
							</tr>
							
						<?php endforeach;?>
							<tr>
								<input id="changestock" type="hidden" value='0'/>
								<input id="stocks" type="hidden" value ="<?php echo $plid;?>"/>
								<?php if($status <= 3):?>
								<td colspan="20" style="text-align: right;">
								<input id="new_goods_delivery" type="button" class="btn blue" value="生成发货单" />&nbsp;
								</td>
								<?php elseif($status == 4):?>
								<td colspan="20" style="text-align: right;">
								<input id="goods_deliveried" type="button" class="btn" disabled value="已生成发货单" />&nbsp;
								<input id="check_goods_delivery" type="button" class="btn green" value="查看发货单" />&nbsp;
								</td>
								<?php elseif($status == 5):?>
								<td colspan="20" style="text-align: right;">
								<input id="goods_invoice" type="button" class="btn" disabled value="已发货" />&nbsp;
								<input id="goods_invoiceed" type="button" class="btn green" value="查看出库单" />&nbsp;
								</td>
								<?php endif;?>
								
							</tr>
						</tbody>
						<?php else:?>
						<tr><td><?php echo yii::t('app','还没有添加详细产品');?></td></tr>
						<?php endif;?>
						
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第 ');?><?php echo $pages->getCurrentPage()+1;?><?php echo yii::t('app','页');?> 
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
	});
	</script>        