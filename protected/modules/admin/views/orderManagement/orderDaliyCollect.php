
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','订单管理'),'subhead'=>yii::t('app','日结列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','日结汇总'),'url'=>''))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
				
				 <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','汇总列表'); ?></div>
					 <div class="actions">
                        <div class="btn-group">
            
						   <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
								<input type="text" class="form-control" name="begtime" id="begin_time" placeholder=<?php echo yii::t('app','起始时间'); ?> value="<?php echo $begin_time; ?>" onfocus=this.blur()>  
								<span class="input-group-addon">~</span>
							    <input type="text" class="form-control" name="endtime" id="end_time" placeholder=<?php echo yii::t('app','终止时间'); ?>  value="<?php echo $end_time;?>" onfocus=this.blur()>           
						   </div>  
					    </div>
					   
					      <div class="btn-group">
							    <button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','日 结'); ?></button>
				  	      </div>
				  	  </div>
				</div>
			</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								
								<th width=100px;><?php echo yii::t('app','序号'); ?></th>
						        <th><?php echo yii::t('app','店铺'); ?></th>
                                <th><?php echo yii::t('app','支付方式'); ?></th>
                                <th><?php echo yii::t('app','金额'); ?></th>                                                                
                                <th><?php echo yii::t('app','备注'); ?></th>
								
							</tr>
						</thead>
						<tbody>
						
						<!--foreach-->
					<?php $a=1;?>
						<!--  <?php foreach ($models as $model):?>   -->
						
						
						
								<tr class="odd gradeX">
								<td><?php echo $a;?></td>
								<td><?php echo $model['company_name']; ?></td>
								<td><?php if($model['payment_method_id'] == '0000000000') echo yii::t('app','现金支付'); else echo $model['name'];?></td>
								<td><?php echo $model['should_all'];?></td>
								<td></td>
								</tr>
						<?php $a=$a+1;?>
						<!-- <?php endforeach;?>	  -->
						<!-- end foreach-->
						
						</tbody>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共'); ?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页'); ?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据'); ?> , <?php echo yii::t('app','当前是第'); ?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页'); ?>
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
	
	</div>
	<!-- END PAGE CONTENT-->

</div>