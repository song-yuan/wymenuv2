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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'head'=>yii::t('app','活动中心'),'subhead'=>yii::t('app','添加活动明细'),'breadcrumbs'=>array(array('word'=>yii::t('app','线上活动'),'url'=>$this->createUrl('discount/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','营销活动设置'),'url'=>$this->createUrl('promotionActivity/index' , array('companyId' => $this->companyId,))),array('word'=>yii::t('app','添加营销活动明细'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('promotionActivity/index' , array('companyId' => $this->companyId,)))));?>
		
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('promotionActivity/detailindex' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
		<div class="col-md-12">
		<div class="tabbable tabbable-custom">
                            <ul class="nav nav-tabs">
                                    <!-- <li class="<?php if($typeID=='normal'){echo 'active';}?>"><a href="" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('promotionActivity/detailindex' , array('typeID'=>'normal' , 'companyId'=>$this->companyId , 'activityID'=>$activityID));?>'"><?php echo yii::t('app','添加普通优惠营销品');?></a></li> -->
                                    <li class="<?php if($typeID=='private'){echo 'active';}?>"><a href="" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('promotionActivity/detailindex' , array('typeID'=>'private' , 'companyId'=>$this->companyId , 'activityID'=>$activityID));?>'"><?php echo yii::t('app','添加特价优惠营销品');?></a></li>
                                    <li class="<?php if($typeID=='cupon'){echo 'active';}?>"><a href="" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('promotionActivity/detailindex' , array('typeID'=>'cupon' , 'companyId'=>$this->companyId , 'activityID'=>$activityID));?>'"><?php echo yii::t('app','添加代金券营销品');?></a></li>
                            		<li class="<?php if($typeID=='gift'){echo 'active';}?>"><a href="" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('promotionActivity/detailindex' , array('typeID'=>'gift' , 'companyId'=>$this->companyId , 'activityID'=>$activityID));?>'"><?php echo yii::t('app','添加礼品券营销品');?></a></li>
                            </ul>
                            <div class="tab-content">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<?php if($typeID=="gift"){ ?>
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','营销活动明细列表');?>-->>><?php echo yii::t('app','礼品券');?><p><?php echo yii::t('app','(*注：只显示生效和未过期的里礼品券)');?></p></div>
					
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','序号');?></th>
								<th style="width:10%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','摘要');?></th>
								<th><?php echo yii::t('app','礼品券金额');?></th>
								<th><?php echo yii::t('app','库存');?></th>
								<th><?php echo yii::t('app','会员领取次数');?></th>
								<th style="width:15%"><?php echo yii::t('app','有效期');?></th>
								<th><?php echo yii::t('app','是否添加营销品');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php $i=1;?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $i;?></td>
								<td style="width:10%"><?php echo $model['title'];?></td>
								<td ><img width="100" src="<?php echo $model['gift_pic'];?>" /></td>
								<td ><?php echo $model['intro'];?></td>
								<td><?php echo $model['price'];?></td>
								<td><?php echo $model['stock'];?></td>
								<td><?php echo $model['count'];?></td>
								<td style="width:15%"><?php echo $model['begin_time'].'至'.$model['end_time'];?></td>
                                <td>
									<div class="form-group">
										<div class="col-md-12">
											<div class="radio-list">
                                                <label class="radio-inline">
                                                <div class="r-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="<?php echo yii::t('app','是');?>" data-off-label="<?php echo yii::t('app','否');?>" >
                                                
                                                <input type="checkbox" class="toggle" name="optionsCheck<?php echo $model['lid'];?>" id="optionsCheck<?php echo $model['lid'];?>" value="1" <?php if(!empty($model['promotion_lid'])){ echo "checked";}else{echo '';}?>>
                                                </div>
                                                <input type="button" name="leftbutton<?php echo $model['lid'];?>" id="idleftbutton<?php echo $model['lid'];?>" class="clear_btn" value=<?php echo yii::t('app','确定修改');?> >
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
	</div>
	</div>
	<!-- END PAGE CONTENT-->
		<SCRIPT type="text/javascript">

	 $(".clear_btn").on("click",function(){
         var vid=$(this).attr("id").substr(12,10);
         //alert(vid);
         //var arr=document.getElementsByName("optionsRadios"+vid);
         var chx=document.getElementById("optionsCheck"+vid);
        // var optid;
         //var optvalue;
         var activityID = "<?php echo $activityID;?>";
         var typeID = "<?php echo $typeID;?>";
         //alert(activityID);
         var checkvalue = '0';

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
			url:"<?php echo $this->createUrl('promotionActivity/store',array('companyId'=>$this->companyId));?>/id/"+vid+"/activityID/"+activityID+"/typeID/"+typeID+"/chk/"+checkvalue+"/page/",
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
	<?php }elseif ($typeID=="private"){ ?>
					<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','营销活动明细列表');?>-->>><?php echo yii::t('app','特价优惠');?><p><?php echo yii::t('app','(*注：只显示生效和未过期的优惠)');?></p></div>
					
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','序号');?></th>
								<th style="width:20%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','摘要');?></th>
								<th><?php echo yii::t('app','针对消费群体');?></th>
								<th><?php echo yii::t('app','是否添加营销品');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php $i=1;?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $i;?></td>
								<td style="width:20%"><?php echo $model['promotion_title'];?></td>
								<td ><img width="100" src="<?php echo $model['main_picture'];?>" /></td>
								<td ><?php echo $model['promotion_abstract'];?></td>
                                <td >
                                
                               		<div class="form-group">
										<div class="col-md-12">
											<div class="radio-list">
                                                <label class="radio-inline">
                                                 <?php switch ($model['to_group']){case 0: echo yii::t('app','所有人');break;case 1: echo yii::t('app','关注微信的人群');break;case 2: echo yii::t('app','会员等级');break;case 3: echo yii::t('app','会员个人');break;default: echo "";break;}?>
                          						 </label>	
                                                 <?php if($model['to_group']=="2") :?>
                                                 <?php $LvNames = $this->getLvName($model['lid'],$model['dpid'],"private");?>
                                                 
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
                                                
                                                <input type="checkbox" class="toggle" name="optionsCheck<?php echo $model['lid'];?>" id="optionsCheck<?php echo $model['lid'];?>" value="1" <?php if(!empty($model['promotion_lid'])){ echo "checked";}else{echo '';}?>>
                                                </div>
                                                <input type="button" name="leftbutton<?php echo $model['lid'];?>" id="idleftbutton<?php echo $model['lid'];?>" class="clear_btn" value=<?php echo yii::t('app','确定修改');?> >
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
	</div>
	</div>
	<!-- END PAGE CONTENT-->
		<SCRIPT type="text/javascript">

	 $(".clear_btn").on("click",function(){
         var vid=$(this).attr("id").substr(12,10);
         //alert(vid);
         //var arr=document.getElementsByName("optionsRadios"+vid);
         var chx=document.getElementById("optionsCheck"+vid);
        // var optid;
         //var optvalue;
         var activityID = "<?php echo $activityID;?>";
         var typeID = "<?php echo $typeID;?>";
         //alert(activityID);
         var checkvalue = '';

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
			url:"<?php echo $this->createUrl('promotionActivity/storeprivate',array('companyId'=>$this->companyId));?>/id/"+vid+"/activityID/"+activityID+"/typeID/"+typeID+"/chk/"+checkvalue+"/page/",
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
	<?php }elseif ($typeID == "cupon"){?>
				<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','营销活动明细列表');?>-->>><?php echo yii::t('app','代金券优惠');?><p><?php echo yii::t('app','(*注：只显示生效和未过期的代金券)');?></p></div>
					
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','序号');?></th>
								<th style="width:20%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','摘要');?></th>
								<th><?php echo yii::t('app','针对消费群体');?></th>
								<th><?php echo yii::t('app','是否添加营销品');?></th>
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
                                                 <?php $LvNames = $this->getLvName($model['lid'],$model['dpid'],"cupon");?>
                                                 
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
                                                
                                                <input type="checkbox" class="toggle" name="optionsCheck<?php echo $model['lid'];?>" id="optionsCheck<?php echo $model['lid'];?>" value="1" <?php if(!empty($model['promotion_lid'])){ echo "checked";}else{echo '';}?>>
                                                </div>
                                                <input type="button" name="leftbutton<?php echo $model['lid'];?>" id="idleftbutton<?php echo $model['lid'];?>" class="clear_btn" value=<?php echo yii::t('app','确定修改');?> >
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
	</div>
	</div>
	<!-- END PAGE CONTENT-->
		<SCRIPT type="text/javascript">

	 $(".clear_btn").on("click",function(){
         var vid=$(this).attr("id").substr(12,10);
         //alert(vid);
         //var arr=document.getElementsByName("optionsRadios"+vid);
         var chx=document.getElementById("optionsCheck"+vid);
        // var optid;
         //var optvalue;
         var activityID = "<?php echo $activityID;?>";
         var typeID = "<?php echo $typeID;?>";
         //alert(activityID);
         var checkvalue = '0';

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
			url:"<?php echo $this->createUrl('promotionActivity/store',array('companyId'=>$this->companyId));?>/id/"+vid+"/activityID/"+activityID+"/typeID/"+typeID+"/chk/"+checkvalue+"/page/",
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
	<?php }else{?>
	<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','营销活动明细列表');?></div>
					
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','序号');?></th>
								<th style="width:20%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','摘要');?></th>
								<th><?php echo yii::t('app','添加代金券');?></th>
							</tr>
						</thead>
						<tbody>
						<td><?php echo yii::t('app','没有发现数据！！！');?></td>
						</tbody>
	<?php }?>