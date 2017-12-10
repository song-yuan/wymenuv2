
<style>
		span.tab{
			color: black;
			border-right:1px dashed white;
			margin-right:10px;
			padding-right:10px;
			display:inline-block;
		}
		span.tab-active{
			color:white;
		}
		.ku-item{
			width:100px;
			height:100px;
			margin-right:20px;
			margin-top:20px;
			margin-left:20px;
			border-radius:5px !important;
		/*	border:2px solid black;
			box-shadow: 5px 5px 5px #888888;*/
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.ku-purple{
		/*	background-color:#DB7093;*/
		}
		.ku-grey{
			/*background-color:#DB7093;*/
		}
		.ku-item.kusz{
			background-image:url(../../../../../../img/waiter/icon-kcsz.png);
			background-position: center center;
    		background-size: 80%;
    		background-repeat: no-repeat;
		}
		.ku-item.pxfl{
			background-image:url(../../../../../../img/waiter/icon-pxxx.png);
			background-position: 20px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.rkdw{
			background-image:url(../../../../../../img/waiter/icon-pxxx.png);
			background-position: -130px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.lsdw{
			background-image:url(../../../../../../img/waiter/icon-pxxx.png);
			background-position: -280px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.pxxx{
			background-image:url(../../../../../../img/waiter/icon-pxxx.png);
			background-position: -430px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.dwxs{
			background-image:url(../../../../../../img/waiter/icon-pxxx.png);
			background-position: -575px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.bomxx{
			background-image:url(../../../../../../img/waiter/icon-pxxx.png);
			background-position: -725px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.pxxf{
			background-image:url(../../../../../../img/waiter/icon-pxxf.png);
			background-position: 25px 20px;
			background-size: 50% ;
    		background-repeat: no-repeat;
		}
		.ku-item.cpfl{
			background-image:url(../../../../../../img/waiter/icon-pxxx.png);
			background-position: 20px -140px;
    		background-repeat: no-repeat;
		}
		.ku-item.cpxx{
			background-image:url(../../../../../../img/waiter/icon-pxxx.png);
			background-position: -130px -140px;
    		background-repeat: no-repeat;
		}
		.ku-item.pdrz{
			background-image:url(../../../../../../img/waiter/icon-pxxx.png);
			background-position: -285px -140px;
    		background-repeat: no-repeat;
		}
		.ku-item.csfl{
			background-image:url(../../../../../../img/waiter/icon-kcgl.png);
			background-position: 15px 10px;
    		background-repeat: no-repeat;
		}
		.ku-item.csxx{
			background-image:url(../../../../../../img/waiter/icon-kcgl.png);
			background-position: -130px 10px;
    		background-repeat: no-repeat;
		}
		.ku-item.cgdd{
			background-image:url(../../../../../../img/waiter/icon-kcgl.png);
			background-position: -275px 10px;
    		background-repeat: no-repeat;
		}
		.ku-item.rkdd{
			background-image:url(../../../../../../img/waiter/icon-kcgl.png);
			background-position: -420px 10px;
    		background-repeat: no-repeat;
		}
		.ku-item.thdd{
			background-image:url(../../../../../../img/waiter/icon-kcgl.png);
			background-position: -565px 10px;
    		background-repeat: no-repeat;
		}
		.ku-item.db{
			background-image:url(../../../../../../img/waiter/icon-kcgl.png);
			background-position: -710px 10px;
    		background-repeat: no-repeat;
		}
		.ku-item.pc{
			background-image:url(../../../../../../img/waiter/icon-kcgl.png);
			background-position: 10px -150px;
    		background-repeat: no-repeat;
		}
		.ku-item.ps{
			background-image:url(../../../../../../img/waiter/icon-kcgl.png);
			background-position: -135px -150px;
    		background-repeat: no-repeat;
		}
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
		.ku-item.sskc{
			background-image:url(../../../../../../img/waiter/icon-sskc.png);
			background-position: 22px 20px;
			background-size: 60% ;
    		background-repeat: no-repeat;
		}
		.ku-item.kcrz{
			background-image:url(../../../../../../img/waiter/icon-kcrz.png);
			background-position: 22px 20px;
			background-size: 60% ;
    		background-repeat: no-repeat;
		}
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
                .cf-black{
			color: #000 !important;
			
		}
	</style>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width: 80%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">关系图</h4>
				</div>
				<div class="modal-body">
					<?php if($type == 2):?>
					库存管理流程图:
					<img alt="" src="../../../../../../img/waiter/lcrelation.jpg" width="100%">
					<?php elseif ($type==1):?>
					品项信息图:
					<img alt="" src="../../../../../../img/waiter/pxrelation.jpg" width="100%">
					<?php endif;?>
				</div>
				<div class="modal-footer">
					<!--  
					<button type="button" class="btn blue">Save changes</button>
					-->
					<button type="button" class="btn default" data-dismiss="modal">确定</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	
	<!-- BEGIN PAGE CONTENT-->
	<?php if($type==0):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','库存设置'),'url'=>''))));?>
	<?php elseif($type==1):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','品项信息'),'url'=>''))));?>
	<?php else:?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>''))));?>
	<?php endif;?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
				<!-- <em class=" fa <?php if($type==1){echo '';}else{echo 'cf-black';}?> fa-calendar">&nbsp</em><a href="<?php echo $this->createUrl('bom/bom',array('companyId'=>$this->companyId,'type'=>1));?>"><span class="tab <?php if($type==1){ echo 'tab-active';}?>" ><?php echo yii::t('app','品项信息');?></span></a> -->
					<div class="caption">
                                            <i class=" fa <?php if($type==0){echo '';}else{echo 'cf-black';}?> fa-archive"></i>
                                            <a href="<?php echo $this->createUrl('bom/bom',array('companyId'=>$this->companyId,'type'=>0));?>">
                                                <span class="tab <?php if($type==0){ echo 'tab-active';}?>"><?php echo yii::t('app','库存设置');?></span>
                                            </a>
                                            <em class=" fa <?php if($type==2){echo '';}else{echo 'cf-black';}?> fa-puzzle-piece">&nbsp</em>
                                            <a href="<?php echo $this->createUrl('bom/bom',array('companyId'=>$this->companyId,'type'=>2));?>">
                                                <span class="tab <?php if($type==2){ echo 'tab-active';}?>" ><?php echo yii::t('app','库存管理');?></span>
                                            </a>
                                        </div>
					<div class="actions">
						<?php if($type == 1||$type==2):?><a class="btn blue relation" href="javascript:;"> <?php echo yii::t('app','查看关系图');?></a>
						
						<?php endif;?>
					</div>
				</div>
				<div class="portlet-body clearfix" style="min-height: 450px">
					<?php if($type==0):?>
					<a href="<?php echo $this->createUrl('stockSetting/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple kusz"></div>
							<div class="ku-item-info">库存设置</div>
						</div>
					</a>
					<?php elseif($type==1):?>
					<a href="<?php echo $this->createUrl('materialCategory/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple pxfl"></div>
							<div class="ku-item-info">品项分类</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('materialUnit/index',array('companyId'=>$this->companyId,'type'=>0));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple rkdw"></div>
							<div class="ku-item-info">入库单位</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('materialUnit/index',array('companyId'=>$this->companyId,'type'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple lsdw"></div>
							<div class="ku-item-info">零售单位</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('productMaterial/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple pxxx"></div>
							<div class="ku-item-info">品项信息</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('materialUnitRatio/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple dwxs"></div>
							<div class="ku-item-info">单位系数</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('productBom/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple bomxx"></div>
							<div class="ku-item-info">产品配方</div>
						</div>
					</a>
<!-- 					<a href="<?php echo $this->createUrl('copymaterial/index',array('companyId'=>$this->companyId));?>"> -->
<!-- 						<div class="pull-left margin-left-right"> -->
<!-- 							<div class="ku-item ku-purple bomxx"></div> -->
<!-- 							<div class="ku-item-info">品项下发</div> -->
<!-- 						</div> -->
<!-- 					</a> -->
					<a href="<?php echo $this->createUrl('copymaterial/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple pxxf"></div>
							<div class="ku-item-info">品项下发</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('bomproductCategory/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey cpfl"></div>
							<div class="ku-item-info">产品分类</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('bomProduct/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey cpxx"></div>
							<div class="ku-item-info">产品信息</div>
						</div>
					</a>
					
					<?php elseif($type==2):?>
					<a href="<?php echo $this->createUrl('mfrClassification/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple csfl"></div>
							<div class="ku-item-info">厂商分类</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('mfrInformation/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple csxx"></div>
							<div class="ku-item-info">厂商信息</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('purchaseOrder/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple cgdd"></div>
							<div class="ku-item-info">采购订单</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('storageOrder/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple rkdd"></div>
							<div class="ku-item-info">入库订单</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('refundOrder/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple thdd"></div>
							<div class="ku-item-info">退货订单</div>
						</div>
					</a>
					<?php $companyType = Helper::getcompanyType($this->companyId);
						if(in_array($companyType,array(0,2))): 
					?>
					<a href="<?php echo $this->createUrl('commit/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple db"></div>
							<div class="ku-item-info">调拨</div>
						</div>
					</a>
					 <?php endif;?>
					 <a href="<?php echo $this->createUrl('stockTaking/damagereason',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple ps"></div>
							<div class="ku-item-info">盘损原因</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('stockTaking/damageindex',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple ps"></div>
							<div class="ku-item-info">盘损</div>
						</div>
					</a>
					 <a href="<?php echo $this->createUrl('stockTaking/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple pc"></div>
							<div class="ku-item-info">盘点</div>
						</div>
					</a>
					<!-- <a href="<?php echo $this->createUrl('stockInventory/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple pc"></div>
							<div class="ku-item-info">盘存</div>
						</div>
					</a> -->
					<a href="<?php echo $this->createUrl('inventory/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple ps"></div>
							<div class="ku-item-info">盘损</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('nowmaterialstock/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey sskc"></div>
							<div class="ku-item-info">实时库存</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('stocktakinglog/index',array('companyId'=>$this->companyId,'begin_time'=>date('Y-m-d 00:00:00',time()),'end_time'=>date('Y-m-d 23:59:59',time()),'page'=>1,'status'=>0));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey pdrz"></div>
							<div class="ku-item-info">盘点日志</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('stocktakinglog/index',array('companyId'=>$this->companyId,'begin_time'=>date('Y-m-d 00:00:00',time()),'end_time'=>date('Y-m-d 23:59:59',time()),'page'=>1,'status'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey pdrz"></div>
							<div class="ku-item-info">盘损日志</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('materialStockLog/index',array('companyId'=>$this->companyId,'begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey kcrz"></div>
							<div class="ku-item-info">库存日志</div>
						</div>
					</a>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	<script>
        $(document).ready(function() {
            $('.relation').click(function(){
                $('.modal').modal();
           });
        });
	</script>