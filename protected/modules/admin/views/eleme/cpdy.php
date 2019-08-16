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
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','饿了么外卖'),'url'=>$this->createUrl('eleme/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','菜品对应'),'url'=>$this->createUrl('eleme/cpdy' , array('companyId'=>$this->companyId,'type'=>0)))),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('eleme/index' , array('companyId' => $this->companyId,'type' => '0')))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'siteType-form',
				'action' =>'',
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
		<div class="portlet purple box">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','菜品对应');?></div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<table class="tree table table-striped table-hover table-bordered dataTable">
						<tr>
							<th>饿了么菜品分类</th>
					 		<th>饿了么菜品</th>
					 		<th colspan="3">收银机关联菜品</th>
					 	</tr>
					 	<?php
					 		foreach ($ecategorys as $ecate):
					 			$ecid = $ecate['id'];
					 			$ecateId = 'lid-'.$ecid;
					 			$model = isset($eproducts[$ecateId])?$eproducts[$ecateId]:array();
					 			if(empty($model)){
					 				continue;
					 			}

					 		foreach ($model['data'] as $key=>$m):
					 			$i = $key;
					 			$specs = $m['specs'];
					 			$materials = $m['materials'];
					 			$dspecs = json_encode($specs);
					 			$dmaterials = json_encode($materials);
					 			foreach ($specs as $spec):
					 			$i++;
					 	?>
						<tr>
							<?php if($i==1):?>
							<td rowspan="<?php echo $model['length'];?>"><?php echo $ecate['name'];?></td>
							<?php endif;?>
							<td>
								<?php
									if($spec['name']!=''){
										echo $m['name'].'('.$spec['name'].')';
									}else{
										echo $m['name'];
									}
								?>
							</td>
							<?php if(isset($products[$spec['extendCode']])):?>
							<td>
								<?php echo $products[$spec['extendCode']]['product_name'];?>
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
											<select class="form-control" name="product_name" e_cid="<?php echo $ecid;?>" e_id="<?php echo $m['id'];?>" e_name="<?php echo $m['name'];?>" e_spec_id="<?php echo $spec['specId'];?>" e_spec="<?php echo urlencode($dspecs);?>" e_materials="<?php echo urlencode($dmaterials);?>">
												<option value="" category_id="0">---请选择---</option>
												<?php foreach ($products as $ph):?>
												<option value="<?php echo $ph['phs_code'];?>" category_id="<?php echo $ph['category_id'];?>"><?php echo $ph['product_name'];?></option>
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
											<select class="form-control" name="product_name" e_cid="<?php echo $ecid;?>" e_id="<?php echo $m['id'];?>" e_name="<?php echo $m['name'];?>" e_spec_id="<?php echo $spec['specId'];?>" e_spec="<?php echo urlencode($dspecs);?>" e_materials="<?php echo urlencode($dmaterials);?>">
												<option value="" category_id="0">---请选择---</option>
												<?php foreach ($products as $p):?>
												<option value="<?php echo $p['phs_code'];?>" category_id="<?php echo $p['category_id'];?>"><?php echo $p['product_name'];?></option>
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
<?php $this->endWidget(); ?>
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
		var eId =  $(this).attr('e_id');
		var eCateId =  $(this).attr('e_cid');
		var eName =  $(this).attr('e_name');
		var eSpecid =  $(this).attr('e_spec_id');
		var eSpec =  $(this).attr('e_spec');
		var eMaterials = $(this).attr('e_materials');
		$.ajax({
			url:'<?php echo $this->createUrl('eleme/ajaxProductDy',array('companyId'=>$this->companyId));?>',
			data:{extendcode:pcode,e_name:eName,e_id:eId,e_cateid:eCateId,e_specid:eSpecid,e_spec:eSpec,e_materials:eMaterials},
			type:'POST',
			success:function(msg){
				if(msg.status){
					var data = msg.data;
					$('select[name="product_name"]').find('option[e_id="'+eId+'"]').attr('e_spec',data);
					alert('关联成功');
				}else{
					alert('关联失败('+msg.msg+')');
				}
			},
			dataType:'json'
		});
	}
});
</script>