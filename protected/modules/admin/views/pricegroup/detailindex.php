
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺设置'),'url'=>$this->createUrl('company/list' , array('companyId' => $this->companyId,'type'=>0,))),array('word'=>yii::t('app','价格分组设置'),'url'=>$this->createUrl('pricegroup/index' , array('companyId' => $this->companyId,))),array('word'=>yii::t('app','价格信息编辑'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('pricegroup/index' , array('companyId' => $this->companyId,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'price-group-detail-form',
				'action' => $this->createUrl('pricegroup/detailindex' , array('companyId' => $this->companyId,'pricegroupid'=>$pricegroupid,'is_post'=>1)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','价格分组详细设置');?></div>
					<div class="actions">

						<div class="btn-group" style="left:3%;">
							<input type="submit"  class="btn yellow" value=<?php echo yii::t('app','批量保存');?> >
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
								<th style="width:10%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','原价');?></th>
								<th><?php echo yii::t('app','组内价格');?></th>
								<th><?php echo yii::t('app','原会员价格');?></th>
								<th><?php echo yii::t('app','组内会员价格');?></th>
								<th><?php echo yii::t('app','是否是套餐');?></th>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) : $i=0; ?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="ids[]" /></td>
								<td style="width:10%"><?php echo $model['name'];?></td>
								<td ><img width="100" src="<?php echo $model['main_picture'];?>" /></td>
								<td ><?php echo $model['yuanjia'];?></td>
								<td>
								<input type="text" style="width:60px;"
									value="<?php if(!empty($model['price'])) echo $model['price']; else echo $model['yuanjia']; ?>"
									name="<?php if(!empty($model['lid'])) echo 'priced['.$model['lid'].']'; else echo 'price[]';?>"
									onfocus=" if (value ==<?php echo $model['yuanjia'];?>){value = ''}"
									onblur="if (value ==''){value=<?php echo $model['yuanjia'];?>}"
									id="price<?php echo $i;?>"
									 >
								<input type="hidden"
									value="<?php echo $model['is_set']; ?>"
									name="<?php if(!empty($model['lid'])) echo 'is_seted['.$model['lid'].']'; else echo 'is_set[]';?>"
									id="ist<?php echo $i;?>"
									 >
								<input type="hidden"
									value="<?php echo $model['plid']; ?>"
									name="<?php if(!empty($model['lid'])) echo 'plided['.$model['lid'].']'; else echo 'plid[]';?>"
									id="pid<?php echo $i;?>"
									 >
								</td>
								<td ><?php echo $model['member_price'];?></td>
								<td>
								<input type="text" style="width:60px;"
									value="<?php if(!empty($model['mb_price'])) echo $model['mb_price']; else echo $model['member_price']; ?>"
									name="<?php if(!empty($model['lid'])) echo 'mb_priced['.$model['lid'].']'; else echo 'mb_price[]';?>"
									onfocus=" if (value ==<?php echo $model['member_price'];?>){value = ''}"
									onblur="if (value ==''){value=<?php echo $model['member_price'];?>}"
									id="mb_price<?php echo $i;?>"
									 >
								</td>
                                <td>
									<div class="form-group">
										<div class="row" style="padding-left:10px;">
											<?php if($model['is_set']==1) :?>
											<span style="color:red;font-size:1.2em;margin-left:40%; " >
											<?php echo yii::t('app','套餐');?>
											</span>
											<?php elseif($model['is_set']==0):?>
											<span style="color:yellowgreen;font-size:1.2em;margin-left:40%;text-align: middle; " >
											<?php echo yii::t('app','单品');?>
											</span>
											<?php endif; ?>
										</div>
									</div>
								</td>
								<td>
									<div class="row" style="padding-left:10px;">
	                                    <input type="button" class="btn green saved" num="<?php echo $i;?>" lid="<?php echo $model['lid'];?>"  value="<?php echo yii::t('app','保存');?>">
	                                    <!-- <a href="<?php //echo $this->createUrl('priceGroup/detailindex' , array('companyId' => $this->companyId ,'lid'=>$model['lid'],'pricegroupid'=>$pricegroupid,'is_set'=>$model['is_set'],'is_post'=>0));?>" class="btn green" ><?php //echo yii::t('app','保存');?></a>  -->
									</div>
								</td>
							</tr>
						<?php $i++;?>
						<?php endforeach;?>
						<?php endif;?>
							<script type="text/javascript">
							$('.saved').click(function(){
								var lid = $(this).attr('lid');
								var num = $(this).attr('num');
								var price =$('#price'+num).val();
								var mb_price =$('#mb_price'+num).val();
								var ist =$('#ist'+num).val();
								var pid =$('#pid'+num).val();
								location.href="<?php echo $this->createUrl('pricegroup/saved',array('companyId'=>$this->companyId,'pricegroupid'=>$pricegroupid));?>/lid/"+lid+"/price/"+price+"/mb_price/"+mb_price+"/ist/"+ist+"/pid/"+pid;
							});
							</script>
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
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
