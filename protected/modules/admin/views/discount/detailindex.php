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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','微信红包'),'subhead'=>yii::t('app','红包明细列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','微信红包管理'),'url'=>''),array('word'=>yii::t('app','红包明细管理'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wxRedpacket/index' , array('companyId' => $this->companyId,)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'wxRedpacketdetail-form',
				'action' => $this->createUrl('wxRedpacket/detailindex' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','红包明细列表');?></div>
					
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','序号');?></th>
								<th style="width:20%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','摘要');?></th>
								<th><?php echo yii::t('app','针对消费群体');?></th>
								<th><?php echo yii::t('app','添加代金券营销品');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php $i=1;?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $i;?></td>
								<td style="width:20%"><?php echo $model['cupon_title'];?></td>
								<td ><img width="100" src="<?php echo $model['main_picture'];?>" /></td>
								<td ><?php echo $model['cupon_abstract'];?></td>
								<td >
                                
                               		<div class="form-group">
										<div class="col-md-12">
											<div class="radio-list">
                                                <label class="radio-inline">
                                                 <?php switch ($model['to_group']){case 0: echo yii::t('app','所有人');break;case 1: echo yii::t('app','关注微信的人群');break;case 2: echo yii::t('app','会员等级');break;case 3: echo yii::t('app','会员个人');break;default: echo "";break;}?>
                          						 </label>	
                                                 <?php if($model['to_group']=="2") :?>
                                                 <?php $LvNames = $this->getLvName($model['lid'],$model['dpid']);?>
                                                 
                                                 <?php if(!empty($LvNames)) :?>
                                                 <?php foreach ($LvNames as $LvName) :?>
													<div class="col-md-12">
										
													<li><?php echo $LvName['level_name']?></li>
										
													</div>
													<?php endforeach;?>
													<?php endif;?>
													<?php endif;?>
											</div>
										</div>
									</div>
								</td>
                                <td>
									<div class="form-group">
										<div class="col-md-12">
											<div class="radio-list">
                                                <label class="radio-inline">
                                                <div class="r-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="<?php echo yii::t('app','是');?>" data-off-label="<?php echo yii::t('app','否');?>" >
                                                <input type="checkbox" class="toggle" name="optionsCheck<?php echo $model['lid'];?>" id="optionsCheck<?php echo $model['lid'];?>" value="1" <?php if(!empty($model['promotion_lid'])){ echo "checked";}else{echo "";}?>>
                                                </div>
                                                <input type="button" name="leftbutton<?php echo $model['lid'];?>" id="idleftbutton<?php echo $model['lid'];?>" class="clear_btn" value=<?php echo yii::t('app','确定添加');?> >
                                                </label>
											</div>
										</div>
									</div>
								</td>
                                                                
							</tr>
							<?php $i=$i+1;?>
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
            <?php $this->endWidget(); ?>
	</div>
	
	<SCRIPT type="text/javascript">

	 $(".clear_btn").on("click",function(){
         var vid=$(this).attr("id").substr(12,10);
         //alert(vid);
         //var arr=document.getElementsByName("optionsRadios"+vid);
         var chx=document.getElementById("optionsCheck"+vid);
        // var optid;
         //var optvalue;
         var redpkID = "<?php echo $redpkID;?>";
         //alert(redpkID);
         var checkvalue = '0';
         //var cid = $(this).val();
         //alert(chx);
//          for(var i=0;i<arr.length;i++)
//          {
//              if(arr[i].checked)
//              {    
//                 optid=arr[i].value;
//              }
//          }
//          if(optid=="0")
//          	{
//              optvalue= $("#idleftnum0"+vid).val();
//          }else if(optid=="1")
//              {
//          	optvalue= $("#idleftnum1"+vid).val();
//              }
			if(chx.checked)
				{
				checkvalue= '1';
				}
			//alert(optid);
			//alert(optvalue);
         //alert(checkvalue);
        // alert(promotionID);
         $.ajax({
                     type:'GET',
			url:"<?php echo $this->createUrl('WxRedpacket/store',array('companyId'=>$this->companyId));?>/id/"+vid+"/redpkID/"+redpkID+"/chk/"+checkvalue+"/page/",
			async: false,
			//data:"companyId="+company_id+'&padId='+pad_id,
                     cache:false,
                     dataType:'json',
			success:function(msg){
                         //alert(msg.status);
                         if(msg.status=="success")
                         {
                             alert("<?php echo yii::t('app','修改成功'); ?>");
                             
                             location.reload();
                         }else{
                             alert("<?php echo yii::t('app','修改失败'); ?>"+"1")
                             location.reload();
                         }
			},
                     error:function(){
				alert("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
     });


	</SCRIPT>
	<!-- END PAGE CONTENT-->
	
	