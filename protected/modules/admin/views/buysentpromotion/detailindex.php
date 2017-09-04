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
	<div id="main2" name="main2" style="min-width: 300px;min-height:200px;display:none;" onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" onmouseout="this.style.backgroundColor=''">
		<div id="title" style="width: 100%;hieght: 50px;font-size: 22px;line-height: 50px;text-align: center;">配置买送规则</div>
		<div id="contant" style="width: 96%;height: 180px;">
		</div>
		<div style="width: 90%;margin-left: 5%;height: 50px;"><button id="add_save" style="float: right;">确认保存</button></div>
	</div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营销活动'),'url'=>$this->createUrl('entityMarket/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','买送'),'url'=>$this->createUrl('buysentpromotion/index' , array('companyId' => $this->companyId,))),array('word'=>yii::t('app','添加买送产品'),'url'=>'')),'back'=>array('word'=>'返回','url'=>$this->createUrl('buysentpromotion/index' , array('companyId' => $this->companyId,)))));?>
			
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'privatepromotiondetail-form',
				'action' => $this->createUrl('buysentpromotion/detailindex' , array('companyId' => $this->companyId,'typeId'=>"product",'promotionID'=>$promotionID)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
              
                    <div class="tabbable tabbable-custom">
                            <!-- <ul class="nav nav-tabs">
                                    <li class="<?php if($typeId == 'product') echo 'active' ; ?>"><a href="#tab_1_<?php echo $typeId;?>" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('buysentpromotion/detailindex' , array('typeId'=>'product' , 'companyId'=>$this->companyId,'promotionID'=>$promotionID));?>'"><?php echo yii::t('app','单品');?></a></li>
                            </ul>
                             -->
                            <div class="tab-content">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
                                    <?php if($typeId=='product') :?>
					<div class="caption"><i class="fa fa-globe"></i><?php echo $prodname.yii::t('app','活动产品设置');?></div>
					<div class="actions">						
                        <div style="margin-top:-5px !important;" class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
						<div class="btn-group">
							<button type="button" id="yichu"  class="btn red" style="padding:6px 10px;margin-top:2px;" ><i class="fa fa-ban"></i> <?php echo yii::t('app','勾选批量移除');?></button>
						</div>
					</div>
                                        <?php else :?>
                                        <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','套餐特价活动设置');?></div>
                                        <?php endif;?>
                                            <div class="col-md-3 pull-right">
												<div class="input-group">
                                                    <input type="text" name="csinquery" class="form-control" placeholder="<?php echo yii::t('app','输入助记符查询');?>">
                                                    <span class="input-group-btn">
                                                        <button class="btn blue" type="submit"><?php echo yii::t('app','查询!');?></button>                                                  
                                                    </span>
                                                </div>
                                            </div>
                                            
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:10%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','购买数量');?></th>
								<th style="width:10%"><?php echo yii::t('app','赠送产品');?></th>
								<th ><?php echo yii::t('app','赠送数量');?></th>
								<th><?php echo yii::t('app','状态');?></th>
								<th><?php echo yii::t('app','编辑');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="ids[]" /></td>
								<td style="width:10%"><?php if($typeId=='product') echo $model['product_name']; else echo $model['set_name'];?></td>
								<td ><?php echo $model['buy_num'];?></td>
								<td style="width:10%"><?php if($typeId=='product') echo $model['sent_name']; else echo $model['set_name'];?></td>
								<td ><?php echo $model['sent_num'];?></td>
                                <td ><?php if($model['is_available'])echo '生效';else echo '失效';?></td>
                                <td class="xiugai" lid="<?php echo $model['lid'];?>" name = "<?php echo $model['product_name'];?>"><a><?php echo yii::t('app','编辑');?></a></td>
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
                        </div>
                
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			//alert(cid);
			var promotionID='<?php echo $promotionID;?>';
			location.href="<?php echo $this->createUrl('buysentpromotion/detailindex' , array('companyId'=>$this->companyId,'typeId'=>"product"));?>/cid/"+cid+"/promotionID/"+promotionID;
		});
	});
      var layer_zhexiantu = 0;  

        $("#yichu").on("click",function(){
        	<?php if(Yii::app()->user->role > User::SHOPKEEPER):?>
            alert("您没有权限！");return false;
            <?php endif;?>
            var aa = document.getElementsByName("ids[]");
            var str=new Array();
            for (var i = 0; i < aa.length; i++) {
	            if (aa[i].checked){
	                str += aa[i].value +',';
	
	            }
            }
            if(str!=''){
                str = str.substr(0,str.length-1);//除去最后一个“，”
            }else{
               	alert("<?php echo yii::t('app','请勾选相应的菜品再进行一键移除！！！');?>");
               	return false;
            }
 
            $.ajax({
	            type:'GET',
	 			url:"<?php echo $this->createUrl('buysentpromotion/detaildelete',array('companyId'=>$this->companyId));?>/id/"+str+"/page/",
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
		                	alert("<?php echo yii::t('app','失败'); ?>"+"1");
		                	location.reload();
	                	}
		 			},
	                error:function(){
	 				alert("<?php echo yii::t('app','失败'); ?>"+"2");                                
	 			},
 		});
    });
    $('.xiugai').on('click', function(){
        var pdid = $(this).attr('lid');
        var name = $(this).attr('name');
        //layer.msg(pdid); 

		var proDetail = '<div id="name" plid = '+pdid+' style="width: 100%;height: 30px;font-size: 18px;line-height: 30px;">'+name+'</div>'
						+'<div style="width: 100%;height: 50px;font-size: 18px;margin-top: 10px;"><span style="width: 10%;padding: 10px 10px;">买</span><input style="width: 30%;" type="text" onkeypress="return event.keyCode>=48&&event.keyCode<=57" id="buynum" placeholder="多少"/><span style="width: 10%;padding: 10px 10px;">送</span><input style="width: 30%;" type="text" onkeypress="return event.keyCode>=48&&event.keyCode<=57" id="sentnum" placeholder="多少"/><span></span></div>'; 
        $("#contant").html(proDetail);
        layer_zhexiantu=layer.open({
		     type: 1,
		     //shift:5,
		     shade: [0.5,'#fff'],
		     move:'#title',
		     moveOut:true,
		     offset:['300px','700px'],
		     shade: false,
		     title: false, //不显示标题
		     area: ['auto', 'auto'],
		     content: $('#main2'),//$('#productInfo'), //捕获的元素
		     cancel: function(index){
		         layer.close(index);
		         layer_zhexiantu=0;
		     }
		 });
		 
    });
    $('#add_save').on('click', function(){
		 var buynum = $('#buynum').val();
		 var sentnum = $('#sentnum').val();
		 var plid = $('#name').attr('plid');
		 if(buynum == '' || sentnum == ''){
			 layer.msg('请填写数量！');
			 return false;
			 }
		 //alert(buynum+'@@'+sentnum+'##'+plid);
		 var url = "<?php echo $this->createUrl('buysentpromotion/stordetail',array('companyId'=>$this->companyId));?>/matids/"+plid+"/buynum/"+buynum+"/sentnum/"+sentnum;
         $.ajax({
             url:url,
             type:'POST',
             //data:matids,//CF
             //async:false,
             dataType: "json",
             success:function(msg){
                 var data=msg;
                 if(data.status){
                     alert("保存成功");
                     location.reload(); 
                     layer.close(layer_zhexiantu);
    		         layer_zhexiantu=0; 
                 }else{
                     alert("保存失败");
                 }
             },
             error: function(msg){
                 var data=msg;
                 alert(data.msg);
             }
         });
		
	});
	</script>	