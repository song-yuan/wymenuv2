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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','产品沽清'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type' => '0',)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('productClean/index' , array('companyId' => $this->companyId,'typeId'=>"product")),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
    	<div class="tabbable tabbable-custom">
            <ul class="nav nav-tabs">
	              <li class="<?php if($typeId == 'product') echo 'active' ; ?>"><a href="#tab_1_<?php echo $typeId;?>" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('productClean/index' , array('typeId'=>'product' , 'companyId'=>$this->companyId));?>'"><?php echo yii::t('app','单品');?></a></li>
                  <li class="<?php if($typeId == 'set') echo 'active' ; ?>"><a href="#tab_1_<?php echo $typeId;?>" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('productClean/index' , array('typeId'=>'set' , 'companyId'=>$this->companyId));?>'"><?php echo yii::t('app','套餐');?></a></li>
            </ul>
            <div class="tab-content">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
                    <?php if($typeId=='product') :?>
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','产品沽清列表');?></div>
					<div class="actions">						
                        <div style="margin-top:-5px !important;" class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
						<!--<a href="<?php echo $this->createUrl('product/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i><?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','历史记录');?></button>
						</div>-->
					</div>
                    <?php else :?>
                    <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','套餐沽清列表');?></div>
                    <?php endif;?>
                    <div class="col-md-3 pull-right">
						<div class="input-group">
                             <input type="text" name="csinquery" class="form-control" placeholder="<?php echo yii::t('app','输入助记符查询');?>">
                             <span class="input-group-btn">
	                             <button class="btn blue" type="submit"><?php echo yii::t('app','查询!');?></button>
	                             <button style="left:10px;" class="btn blue" type="button" id="cancelallclean"><?php echo yii::t('app','解除全部沽清');?></button>                                                        
                             </span>
                        </div>
                    </div>
                                        
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:20%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','状态');?></th>
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
									<div class="form-group">
										<div class="col-md-12">
											<div class="radio-list">
												<label class="radio-inline">
												<input type="radio" name="optionsRadios<?php echo $model->lid;?>" id="optionsRadios<?php echo $model->lid;?>1" value="-1" <?php if($model->store_number==-1) echo "checked";?>> <?php echo yii::t('app','数量不受限');?>
												</label>
												<label class="radio-inline">
												<input type="radio" name="optionsRadios<?php echo $model->lid;?>" id="optionsRadios<?php echo $model->lid;?>2" value="0" <?php if($model->store_number==0) echo "checked";?>> <?php echo yii::t('app','已售完');?>
												</label>
                                                                                                <label class="radio-inline">
                                                                                                <input type="radio" name="optionsRadios<?php echo $model->lid;?>" id="optionsRadios<?php echo $model->lid;?>3" value="1" <?php if($model->store_number>0) echo "checked";?>> <?php echo yii::t('app','仅剩');?>
                                                                                                <input type="text" style="width:60px;" name="leftnum<?php echo $model->lid;?>" id="idleftnum<?php echo $model->lid;?>" value="<?php if($model->store_number>0) echo $model->store_number; else echo "0"; ?>" >
                                                                                                <input type="button" name="leftbutton<?php echo $model->lid;?>" id="idleftbutton<?php echo $model->lid;?>" class="clear_btn" value=<?php echo yii::t('app','保存');?> >
                                                                                                </label>
											</div>
										</div>
									</div>
								</td>
                                                                <!--
                                                                <label class="radio-inline">
												<input type="radio" name="optionsRadios" id="optionsRadios27" value="option3" disabled> Disabled
												</label> 
                                                                <td>
									<div class="s-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="<?php echo yii::t('app','在售');?>" data-off-label="<?php echo yii::t('app','售罄');?>">
										<input typeId="<?php echo $typeId;?>" pid="<?php echo $model->lid;?>" <?php if(!$model->status) echo 'checked="checked"';?> type="checkbox"  class="toggle"/>
									</div>
								</td>
                                                                -->
							</tr>
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
            <?php if(Yii::app()->user->role > User::SHOPKEEPER):?>
            	alert("您没有权限！");
            	return false;
            <?php endif;?>
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
            <?php if(Yii::app()->user->role > User::SHOPKEEPER):?>
            alert("您没有权限！");
            location.reload();
            return false;
            <?php endif;?>
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