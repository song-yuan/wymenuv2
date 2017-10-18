
<style>
.radio-inline div{padding-top:0!important;}
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('company/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','店铺分组设置'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('company/list' , array('companyId' => $this->companyId,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'price-group-id-form',
				'action' => $this->createUrl('areaGroupDetail/index' , array('companyId' => $this->companyId,'type'=>$type)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
	)); ?>
	<div class="col-md-12">
    <div class="tabbable tabbable-custom">

			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','店铺分组设置');?></div>
					<div class="actions">

						<div class="btn-group" style="left:3%;">
							<input type="submit"  class="btn yellow" value=<?php echo yii::t('app','批量保存');?> >
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<?php if($models) : ?>
						<thead>
							<tr>
								<th class="table-checkbox" ><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<?php if(Yii::app()->user->role < '5'): ?><th>ID</th><?php endif; ?>
								<th><?php echo yii::t('app','店铺名称');?></th>
								<th><?php echo yii::t('app','联系人');?></th>
								<th><?php echo yii::t('app','地址');?></th>
								<th><?php echo yii::t('app','分组设置');?></th>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="ids[]" /></td>
								<?php if(Yii::app()->user->role < '5'): ?><td><?php echo $model['dpid'];?></td><?php endif; ?>
								<td style="width:10%"><?php echo $model['company_name'];?></td>
								<td ><?php echo $model['contact_name'];?></td>
								<td ><?php echo $model['address'];?></td>
								<td>
									<select name="<?php echo $model['dpid']; ?>" id="aa<?php echo $model['dpid']; ?>" class="btn" style="border:1px solid gray;padding:2px 3px;">
										<?php if (!$groups):?>
											<option value="">亲 , 您还没有添加分组</option>
										<?php else:?>
											<option value="0" <?php if ($model['area_group_id']==0) {echo 'selected';} ?>>-请选择-</option>
											<?php foreach($groups as $group ): ?>
												<option value="<?php echo $group['lid']; ?>" <?php if ($group['lid']==$model['area_group_id']) {echo 'selected';} ?>>-<?php echo $group['group_name']; ?>-</option>
											<?php endforeach; ?>
										<?php endif;?>
									</select>
								</td>
								<td>
									<div class="row" style="padding-left:10px;">
	                                    <input type="button" class="btn green saved " valued="<?php echo $model['dpid']; ?>" value="<?php echo yii::t('app','保存');?>">
									</div>
								</td>
							</tr>
						<?php endforeach;?>
						<?php else: ?>
							<tr><td>亲,请先添加下级店铺....</td></tr>
						<?php endif;?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
        	</div>


		</div>
		<?php $this->endWidget(); ?>
	</div>


		<script type="text/javascript">
		$('.saved').on('click',function(){

			var dpid =$(this).attr('valued');
			// alert('aa'+dpid);
			var ss = $('#aa'+dpid ).find('option:selected').attr('value');
			// alert(ss);
			var arr = new Array;
			arr[dpid] = ss;
			var arrs = dpid+':'+ss;
										        // console.log(arr);
			$.ajax({
	            type:'get',
				url:"<?php echo $this->createUrl('areaGroupDetail/store',array('companyId'=>$this->companyId,));?>/arr/"+arrs,
				async: false,
	            cache:false,
	            dataType:'json',
				success:function(msg){
					if(msg){
						location.reload();
					}else{
						alert('保存失败');
					}
					

				}
			});

		})
	</script>
	<!-- END PAGE CONTENT-->
