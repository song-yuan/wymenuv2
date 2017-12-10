
<style>
.radio-inline div{padding-top:0!important;}
.form-horizontal .form-group {
    margin-right: -15px;
    margin-left: 0px;
}
.width2{
    width: 200px;
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺设置'),'url'=>$this->createUrl('company/list' , array('companyId' => $this->companyId,'type'=>0,))),array('word'=>yii::t('app','配送分组设置'),'url'=>$this->createUrl('peisonggroup/index' , array('companyId' => $this->companyId,))),array('word'=>yii::t('app','配送信息编辑'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('peisonggroup/index' , array('companyId' => $this->companyId,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">

	<div class="col-md-12">
    <div class="tabbable tabbable-custom">

			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','配送分组详细设置');?></div>
					<div class="actions">
						<div class="btn-group">
							<input type="search" name="pname" id="pname" value="" placeholder="输入品项名关键字" class="btn width2">
						</div>
						<div class="btn-group">
						    <span id="search" class="btn blue" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></span>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<caption> <h3 style="margin-top:0px;"><b> <?php echo $groupname; ?></b></h3></caption>
						<thead>
							<tr>
								<th class="table-checkbox" ><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:10%"><?php echo yii::t('app','品项名称');?></th>
								<th><?php echo yii::t('app','指定仓库');?></th>
								<th><?php echo yii::t('app','是否配置');?></th>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) : $i=0; ?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="ids[]" /></td>
								<td style="width:10%"><?php echo $model['material_name'];?></td>
                                <td>
									<div class="form-group">
											<select class="form-control" name="stock" id="stock_dpid<?php echo $i; ?>">
												<option value=""> -请选择仓库- </option>
												<?php if ($stock_dpids): ?>
												<?php foreach ($stock_dpids as $key => $value): ?>
												<option value="<?php echo $value['dpid'] ?>" <?php if ($model['stock_dpid']==$value['dpid']) { ?> selected="selected" <?php } ?> > -<?php echo $value['company_name'] ?>- </option>
												<?php endforeach; ?>
												<?php else: ?>
												<option value=""> -请添加仓库- </option>
												<?php endif; ?>
											</select>
									</div>
								</td>
								<td>
									<div class="form-group">
										<?php if(!empty($model['material_id'])): ?>
											<span style="color:green;">已配置</span>
										<?php else: ?>
											<span style="color:red;">未配置</span>
										<?php endif; ?>
									</div>
								</td>
								<td>
									<div class="row" style="padding-left:10px;">
	                                    <input type="button" class="btn green saved" num="<?php echo $i;?>" lid="<?php echo $model['lid'];?>" material_id="<?php echo $model['pm_material_id'];?>" mphs_code="<?php echo $model['mphs_code'];?>" value="<?php echo yii::t('app','保存');?>">
									</div>
								</td>
							</tr>
						<?php $i++;?>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
					</div>
					<?php if($pages->getItemCount()):?>
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<div class="dataTables_info">
								<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
        	</div>


		</div>
	</div>
	<!-- END PAGE CONTENT-->
<script>
	$('.saved').click(function(){
		var lid = $(this).attr('lid');
		var material_id = $(this).attr('material_id');
		var mphs_code = $(this).attr('mphs_code');
		var num = $(this).attr('num');
		var stock_dpid =$('#stock_dpid'+num+' option:selected').val();
		$(this).attr('id', 'aa');
		if (confirm('确认保存该条数据吗 ? ')) {
			if (stock_dpid=='') {
				layer.msg('请选择对应的仓库!');
			}else{
				$.ajax({
					url: '<?php echo $this->createUrl('peisonggroup/saved',array('companyId'=>$this->companyId,'peisonggroupid'=>$peisonggroupid)) ?>',
					dataType: 'json',
					data: {
						lid: lid,
						stock_dpid:stock_dpid,
						mphs_code:mphs_code,
						material_id:material_id,
					},
					success: function(data){
						if (data[0]==1) {
							layer.msg('保存成功!');
							$('#stock_dpid'+num).parent().parent().next().children('div').children('span').css('color', 'green');
							$('#stock_dpid'+num).parent().parent().next().children('div').children('span').html('已配置');
							$('#aa').attr('lid', data[1]);
							$('#aa').removeAttr('id');
						} else{
							layer.msg('保存失败!');
						}
					}
				});
			}
		}
		
	});

	$('#search').click(function(event) {
		/* Act on the event */
		var peisonggroupid ="<?php echo $peisonggroupid; ?>";
		var pname = $('#pname').val();
		// alert(pname);
		location.href="<?php echo $this->createUrl('peisonggroup/detailIndex' , array('companyId'=>$this->companyId));?>/peisonggroupid/"+peisonggroupid+"/pname/"+pname;
	});

	document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if(e && e.keyCode==13){ // enter 键
        //要做的事情
		var peisonggroupid ="<?php echo $peisonggroupid; ?>";
		var pname = $('#pname').val();
		location.href="<?php echo $this->createUrl('peisonggroup/detailIndex' , array('companyId'=>$this->companyId));?>/peisonggroupid/"+peisonggroupid+"/pname/"+pname;
        }
    };
</script>