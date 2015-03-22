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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'产品沽清','subhead'=>'产品沽清列表','breadcrumbs'=>array(array('word'=>'产品沽清','url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('productClean/index' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
              
                    <div class="tabbable tabbable-custom">
                            <ul class="nav nav-tabs">
                                    <li class="<?php if($typeId == 'product') echo 'active' ; ?>"><a href="#tab_1_<?php echo $typeId;?>" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('productClean/index' , array('typeId'=>'product' , 'companyId'=>$this->companyId));?>'">单品</a></li>
                                    <li class="<?php if($typeId == 'set') echo 'active' ; ?>"><a href="#tab_1_<?php echo $typeId;?>" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('productClean/index' , array('typeId'=>'set' , 'companyId'=>$this->companyId));?>'">套餐</a></li>
                            
                            </ul>
                            <div class="tab-content">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
                                    <?php if($typeId=='product') :?>
					<div class="caption"><i class="fa fa-globe"></i>产品沽清列表</div>
					<div class="actions">						
                                                <div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
						<!--<a href="<?php echo $this->createUrl('product/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> 添加</a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> 历史记录</button>
						</div>-->
					</div>
                                        <?php else :?>
                                        <div class="caption"><i class="fa fa-globe"></i>套餐沽清列表</div>
                                        <?php endif;?>
                                        <!--    <div class="col-md-3 pull-right">
						<div class="input-group">
                                                    <input type="text" name="csinquery" class="form-control" placeholder="输入助记符查询">
                                                    <span class="input-group-btn">
                                                        <button class="btn blue" type="submit">查询!</button>
                                                    </span>
                                                </div>
                                            </div>
                                        -->
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:20%">名称</th>
								<th >图片</th>
								<th>状态</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td style="width:20%"><?php if($typeId=='product') echo $model->product_name; else echo $model->set_name;?></td>
								<td ><img width="100" src="<?php echo $model['main_picture'];?>" /></td>
                                                                <td>
									<div class="s-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="在售" data-off-label="售罄">
										<input typeId="<?php echo $typeId;?>" pid="<?php echo $model->lid;?>" <?php if(!$model->status) echo 'checked="checked"';?> type="checkbox"  class="toggle"/>
									</div>
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									共 <?php echo $pages->getPageCount();?> 页  , <?php echo $pages->getItemCount();?> 条数据 , 当前是第 <?php echo $pages->getCurrentPage()+1;?> 页
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
                
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		
		$('.s-btn').on('switch-change', function () {
                    var inp = $(this).find('input');
                        var id=inp.attr('pid');
                        var typeid=inp.attr('typeid');
                        var url='<?php echo $this->createUrl('productClean/status',array('companyId'=>$this->companyId));?>/id/'+id+'/typeId/'+typeid;
                        //alert(url);
                        $.get(url);
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('productClean/index' , array('companyId'=>$this->companyId,'typeId'=>'product'));?>/cid/"+cid;
		});
	});
	</script>	