<div class="page-content">
 <div id="responsive" class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','美团外卖'),'url'=>$this->createUrl('meituan/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','菜品对应'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('meituan/index' , array('companyId' => $this->companyId,'type' => '0')))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<div class="portlet purple box">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','菜品对应');?></div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<table class="tree table table-striped table-hover table-bordered dataTable">
						<tr>
							<th>美团菜品分类</th>
					 		<th>美团菜品</th>
					 		<th colspan="3">收银机关联菜品</th>
					 	</tr>
					 	<?php foreach ($models as $cateName=>$model):?>
					 	<?php 
					 		foreach ($model['data'] as $key=>$m):
					 			$i = $key;
					 			$skus = json_decode($m['skus'],true);
					 			foreach ($skus as $sku):
					 			$i++;
					 	?>
						<tr>
							<?php if($i==1):?>
							<td rowspan="<?php echo $model['length'];?>"><?php echo $cateName;?></td>
							<?php endif;?>
							<td>
								<?php echo $m['name'].'('.$sku['spec'].')';?>
							</td>
							<?php if(isset($products[$sku['sku_id']])):?>
							<td>
								<?php echo $products[$sku['sku_id']]['product_name'];?>
							</td>
							<td>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<select class="form-control" name="category_name">
												<option>---请选择---</option>
												<?php foreach ($categorys['0000000000'] as $cate):?>
												<optgroup label="<?php echo $cate['category_name'];?>">
													<?php if(isset($categorys[$cate['lid']])):?>
													<?php foreach ($categorys[$cate['lid']] as $c):?>
													<option value="<?php echo $c['lid'];?>"><?php echo $c['category_name'];?></option>
													<?php endforeach;?>
													<?php endif;?>
												</optgroup>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-md-6">
											<select class="form-control" name="product_name">
												<option value="" category_id="0">---请选择---</option>
												<?php foreach ($products as $p):?>
												<option value="<?php echo $p['phs_code'];?>" category_id="<?php echo $p['category_id'];?>" mt_name="<?php echo $m['name'];?>" mt_category_name="<?php echo $cateName;?>" mt_skuid="<?php echo $sku['sku_id'];?>" mt_skuspec="<?php echo $sku['spec'];?>"><?php echo $p['product_name'];?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</div>
							</td>
							<?php else:?>
							<td>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<select class="form-control" name="category_name">
												<option>---请选择---</option>
												<?php foreach ($categorys['0000000000'] as $cate):?>
												<optgroup label="<?php echo $cate['category_name'];?>">
													<?php if(isset($categorys[$cate['lid']])):?>
													<?php foreach ($categorys[$cate['lid']] as $c):?>
													<option value="<?php echo $c['lid'];?>"><?php echo $c['category_name'];?></option>
													<?php endforeach;?>
													<?php endif;?>
												</optgroup>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-md-6">
											<select class="form-control" name="product_name">
												<option value="" category_id="0">---请选择---</option>
												<?php foreach ($products as $p):?>
												<option value="<?php echo $p['phs_code'];?>" category_id="<?php echo $p['category_id'];?>" mt_name="<?php echo $m['name'];?>" mt_category_name="<?php echo $cateName;?>" mt_skuid="<?php echo $sku['sku_id'];?>" mt_skuspec="<?php echo $sku['spec'];?>"><?php echo $p['product_name'];?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</div>
							</td>
							<?php endif;?>
				 		</tr>
				 		<?php endforeach;?>
					 	<?php endforeach;?>
					 	<?php endforeach;?>
					</table>
                </div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$('select[name="category_name"]').change(function(){
	var cid = $(this).val();
	var obj = $(this).parents('td').find('select[name="product_name"] option');
	obj.each(function(){
		var cateId = $(this).attr('category_id');
		if(parseInt(cid)==parseInt(cateId)){
			$(this).show();
		}else{
			$(this).hide();
		}
	});
});
$('select[name="product_name"]').change(function(){
	var pcode = $(this).val();
	if(pcode!=''){
		var obj = $(this).find('option:selected');
		var mtName = obj.attr('mt_name');
		var mtCateName = obj.attr('mt_category_name');
		var mtSkuid = obj.attr('mt_skuid');
		var mtSkuspec = obj.attr('mt_skuspec');
		$.ajax({
			url:'<?php echo $this->createUrl('meituan/ajaxProductDy',array('companyId'=>$this->companyId));?>',
			data:{appcode:pcode,mt_name:mtName,mt_category_name:mtCateName,mt_skuid:mtSkuid,mt_skuspec:mtSkuspec},
			type:'POST',
			success:function(msg){
				if(msg!='ok'){
					alert('关联失败,请重新关联');
				}else{
					alert('关联成功');
				}
			}
		});
	}
});
</script>