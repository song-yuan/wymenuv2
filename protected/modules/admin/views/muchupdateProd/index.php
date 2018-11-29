<script type="text/javascript">
function fun()
		{
			
			if($(this).checked){
				document.$("FirstItem").checked;
			}

		};
		
		
		
</script>
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','产品批量修改'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'muchupdateProd-form',
				'action' => $this->createUrl('muchupdateProd/storProduct' , array('companyId' => $this->companyId)),
				'method' => 'POST',
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','产品列表');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
<!-- 						<a href="<?php echo $this->createUrl('product/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>-->
							<div class="btn-group"> 
							<button type="submit" id="su"  class="btn red form-control" ><i class="fa fa-share-square-o "></i> <?php echo yii::t('app','菜单批量修改');?></button>
 						</div> 
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /><?php echo yii::t('app','全选');?></th>
								<th style="width: 15%;"><?php echo yii::t('app','名称');?></th>
								<th><?php echo yii::t('app','类别');?></th>
								<th style="width: 80px;"><?php echo yii::t('app','现价');?></th>
								<th style="width: 80px;"><?php echo yii::t('app','会员价');?></th>
								<th style="width: 80px;"><?php echo yii::t('app','排序号');?></th>
								<th style="width: 80px;"><?php echo yii::t('app','打包费');?></th>
								<th style="width: 130px;"><?php echo yii::t('app','是否参与会员折扣');?></th>
								<th style="width: 110px;"><?php echo yii::t('app','是否允许折扣');?></th>
								<th style="width: 110px;"><?php echo yii::t('app','是否允许销售');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input id="<?php echo $model->lid;?>" type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" phs_code="<?php echo $model->phs_code;?>" chs_code="<?php echo $model->chs_code;?>" name="ids[]" />
								</td>
								<td><?php echo $model->product_name;?></td>
								<td><?php if(!empty($model->category->category_name)) echo $model->category->category_name;?></td>
								<td >
									<input class="form-control" type="text" name="Product[<?php echo $model->lid;?>][original_price]" value="<?php echo $model->original_price;?>">
								</td>
								<td >
									<input class="form-control" type="text" name="Product[<?php echo $model->lid;?>][member_price]" value="<?php echo $model->member_price;?>">
								</td>
								<td >
									<input class="form-control" type="text" name="Product[<?php echo $model->lid;?>][sort]" value="<?php echo $model->sort;?>">
								</td>
								<td >
									<input class="form-control" type="text" name="Product[<?php echo $model->lid;?>][dabao_fee]" value="<?php echo $model->dabao_fee;?>">
								</td>
								<td >
									<select class="form-control" name="Product[<?php echo $model->lid;?>][is_member_discount]">
										<option value="0" <?php if($model->is_member_discount==0){ echo 'selected="selected';}?>">否</option>
										<option value="1" <?php if($model->is_member_discount==1){ echo 'selected="selected';}?>">是</option>
									</select>
								</td>
								<td >
									<select class="form-control" name="Product[<?php echo $model->lid;?>][is_discount]">
										<option value="0" <?php if($model->is_discount==0){ echo 'selected="selected';}?>">否</option>
										<option value="1" <?php if($model->is_discount==1){ echo 'selected="selected';}?>">是</option>
									</select>
								</td>
                                <td >
									<select class="form-control" name="Product[<?php echo $model->lid;?>][is_show]">
										<option value="0" <?php if($model->is_show==0){ echo 'selected="selected';}?>">否</option>
										<option value="1" <?php if($model->is_show==1){ echo 'selected="selected';}?>">是</option>
									</select>
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
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
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('muchupdateProd/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});

		$("#su").on('click',function() {
			var aa = document.getElementsByName("ids[]");
	        var pids = new Array();
	        for (var i = 0; i < aa.length; i++) {
	            if (aa[i].checked) {
	                pids += aa[i].getAttribute("value") +',';
	            }
	        }
	        if(pids!=''){
	        	pids = pids.substr(0,pids.length-1);//除去最后一个“，”
	        }else{
	       	 	alert("<?php echo yii::t('app','请选择要修改的菜品！！！');?>");
	       		return false;
	       	}
		});
	});
	</script>	