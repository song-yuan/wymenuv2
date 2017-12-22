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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','进销存管理'),'subhead'=>yii::t('app','盘损单详情'),'breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','盘损单管理'),'url'=>$this->createUrl('inventory/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','盘损单详情'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('inventory/index' , array('companyId' => $this->companyId,)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('inventory/detailDelete' , array('companyId' => $this->companyId,'slid'=>$slid,'status'=>$status,)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','品项盘损列表');?></div>
					<div class="actions">
						<?php if($status == 0):?>
							<a href="<?php echo $this->createUrl('inventory/detailcreate' , array('companyId' => $this->companyId, 'lid'=>$slid));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
						<?php endif;?>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','品项名称');?></th>
								<th><?php echo yii::t('app','盘损库存');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<div style="display: none;" id="storagedetail" val="1"></div>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td style="width:16%"><?php if($model->material)echo $model->material->material_name;?></td>
								<td ><input style="display: none;" type="text" class="checkboxes" id="originalnum<?php echo $model['lid'];?>" value="<?php  echo $model['inventory_stock'];?>" name="idss[]" />
								<input class="kucundiv" type="text" <?php if($status != 0)echo 'disabled';?>  style="width:100px;" name="leftnum<?php echo $model['lid'];?>" id="idleftnum0<?php echo $model['lid'];?>" value="<?php echo $model['inventory_stock'];?>" stockid="0" onfocus=" if (value =='0.00'){value = '0.00'}" onblur="if (value ==''){value=''}"  onkeyup="if(isNaN(value))execCommand('undo')" onafterpaste="if(isNaN(value))execCommand('undo')" >
								</td>
								<td class="center">
								<?php if($status == 0):?>
									<a href="<?php echo $this->createUrl('inventory/detailupdate',array('lid' => $model->lid , 'slid'=>$model->inventory_id,  'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								<?php endif;?>
								</td>
							</tr>
						<?php endforeach;?>
						<?php else:?>
						<div style="display: none;" id="storagedetail" val="0"></div>
						<?php endif;?>
							<tr>
								<td colspan="6" style="text-align: right;">
								<?php if($storage->status==1):?><span style="color:red">已确认盘损</span>
								<?php elseif($storage->status==0):?><?php if(Yii::app()->user->role<13):?>
								<span style="color: red;">若修改了盘损量，请先保存再进行盘损！</span>
								<button type="button" id="save"  class="btn yellow" ><i class="fa fa-pencial"></i><?php echo yii::t('app','暂时保存');?></button>				
								<input id="status-0" type="button" class="btn blue" value="确认盘损" storage-id="<?php echo $storage->lid;?>" />
								<?php else:?><span style="color:red">正在编辑</span>
								<?php endif;?>
								<?php elseif($storage->status ==2):?><span style="color:green">盘损单已失效</span>
								<?php endif;?>
								</td>
							</tr>
						</tbody>
					</table>
					</div>
					<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?> , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('#status-0').click(function(){
			var pid = '<?php echo $slid;?>';
			var storagedetail = $('#storagedetail').attr('val');
			//alert(pid);
			if(storagedetail == 1){
			if(confirm('确认盘损')){
				$.ajax({
					url:'<?php echo $this->createUrl('inventory/allStore',array('companyId'=>$this->companyId));?>',
					data:{pid:pid},
					success:function(msg){
						if(msg){
							layer.msg('盘损成功');
						}else{
							layer.msg('盘损失败');
						}
						//history.go(0);
						location.href="<?php echo $this->createUrl('inventory/index' , array('companyId'=>$this->companyId,));?>";
					}
				});
			}
			}else{
				alert('请添加需盘损的详细品项');
				}
		});

		$("#save").on("click",function(){
			var loading = layer.load();
			//alert("123");
			var pid = '<?php echo $slid;?>';
	        var arr=document.getElementsByName("idss[]");
	        var optid;
	        var optval = '';
	        for(var i=0;i<arr.length;i++)
	        {
	            var vid = $(arr[i]).attr("id").substr(11,10);  
	            var nownum = $("#idleftnum0"+vid).val(); 
	            if(nownum != ''){
	                optval = vid +','+ nownum +';'+ optval;
	                } 
	        }
	        if(optval.length >0){
	        	optval = optval.substr(0,optval.length-1);//除去最后一个“，”
	        	//alert(optval);
	        }else{
	            alert('请先添加盘损项');
	            layer.closeAll('loading');
	            return false;
	            }
            //layer.msg(optval);return false;
	        $.ajax({
	            type:'GET',
				url:"<?php echo $this->createUrl('inventory/savestore',array('companyId'=>$this->companyId,));?>/optval/"+optval+"/pid/"+pid,
				async: false,
				//data:"companyId="+company_id+'&padId='+pad_id,
	            cache:false,
	            dataType:'json',
				success:function(msg){
		            if(msg.status=="success")
		            {            
				        layer.msg("保存成功！");
			            location.reload();
			            layer.closeAll('loading');
		            }else{
			            alert("<?php echo yii::t('app','失败'); ?>"+"1");
			            layer.closeAll('loading');
		            }
				},
	            error:function(){
					alert("<?php echo yii::t('app','失败'); ?>"+"2");  
					layer.closeAll('loading');                              
				},
			});
	        
			});
	});
	</script>	