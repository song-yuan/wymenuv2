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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','组织库存详情'),'subhead'=>yii::t('app','库存详情列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','组织库存'),'url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('orgInformation/stockdetail' , array('companyId' => $this->companyId,'typeId'=>"product")),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','组织库存列表');?></div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('orgInformation/index' , array('companyId' => $this->companyId));?>" class="btn blue"> <?php echo yii::t('app','返回');?></a>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th width="12%"><?php echo yii::t('app','品项名称');?></th>
								<th width="12%"><?php echo yii::t('app','品项单位');?></th>
								<th width="12%"><?php echo yii::t('app','实时库存');?></th>
								<th width="12%"><?php echo yii::t('app','盘损数量');?></th>
								<th width="12%"><?php echo yii::t('app','盘存数量');?></th>
								<th width="37%"><?php echo yii::t('app','备注');?></th>
                                <th width="3%">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $model->material_name ;?></td>
								<td><?php echo Common::getStockName($model->sales_unit_id) ;?></td>
								<td><?php //echo $model->product_name ;?></td>
                                <td>
                                    <div class="col-md-12">
                                        <input type="text" style="width:100px;" name="leftnum<?php echo $model->lid;?>" id="idleftnum<?php echo $model->lid;?>" value="<?php //if($model->store_number>0) echo $model->store_number; else echo "0"; ?>" >
                                    </div>
								</td>
                                <td>
                                    <div class="col-md-12">
                                        <input type="text" style="width:100px;" name="leftnum<?php echo $model->lid;?>" id="idleftnum<?php echo $model->lid;?>" value="<?php //if($model->store_number>0) echo $model->store_number; else echo "0"; ?>" >
                                    </div>
                                </td>
                                <td>
									<div class="col-md-12">
										<input type="text" style="width:200px;" name="leftnum<?php echo $model->lid;?>" id="idleftnum<?php echo $model->lid;?>" value="<?php //if($model->store_number>0) echo $model->store_number; else echo "0"; ?>" >
									</div>
								</td>
                                <td>
                                    <div class="col-md-12">
                                        <input type="button" name="leftbutton<?php echo $model->lid;?>" id="idleftbutton<?php echo $model->lid;?>" class="clear_btn" value=<?php echo yii::t('app','保存');?> >
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
			<!-- END EXAMPLE TABLE PORTLET-->
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
        
        //cancelallclean
        
        $("#cancelallclean").on("click",function(){
            var url="<?php echo $this->createUrl('productClean/resetall',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>";
            //alert(url);
            $.ajax({
 			url:url,
 			async: false,
 			//data:"companyId="+company_id+'&padId='+pad_id,
                        dataType:'json',
 			success:function(msg){
                            //alert(msg.status);
                            if(msg.status=="success")
                            {
                                alert("已经解除全部沽清！");
                                location.reload();
                            }else{
                                alert("已经解除全部沽清"+"111")
                                location.reload();
                            }
 			},
                        error:function(){
 				alert("请重试"+"2");                                
 			},
 		});
        });
        
        $(".clear_btn").on("click",function(){
            var vid=$(this).attr("id").substr(12,10);
            var arr=document.getElementsByName("optionsRadios"+vid);
            var optvalue;
            for(var i=0;i<arr.length;i++)
            {
                if(arr[i].checked)
                {    
                   optvalue=arr[i].value;
                }
            }
            if(optvalue=="1")
            {
               optvalue= $("#idleftnum"+vid).val();
            }
            //alert(optvalue);
            $.ajax({
                        type:'GET',
 			url:"<?php echo $this->createUrl('productClean/store',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>/id/"+vid+"/storeNumber/"+optvalue,
 			async: false,
 			//data:"companyId="+company_id+'&padId='+pad_id,
                        cache:false,
                        dataType:'json',
 			success:function(msg){
                            //alert(msg.status);
                            if(msg.status=="success")
                            {
                                alert("<?php echo yii::t('app','成功'); ?>");
                                location.reload();
                            }else{
                                alert("<?php echo yii::t('app','失败'); ?>"+"1")
                                location.reload();
                            }
 			},
                        error:function(){
 				alert("<?php echo yii::t('app','失败'); ?>"+"2");                                
 			},
 		});
        });
        
	</script>	