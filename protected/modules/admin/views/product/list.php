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
		
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.ku-purple{
			
                /*   background-color:#EE9572;
                   background-color:#DB7093;
                 background-color:#8FBC8F;
                 background-color:#7D9EC0;*/
		}
		.ku-grey{
			background-color:rgb(68,111,120);
		}
		.ku-item.kusz{
			background-image:url(../../../../../../img/waiter/icon-cpsz.png);
			background-position: center center;
    		background-size: 80%;
    		background-repeat: no-repeat;
		}
		.ku-item.cpfl{
			background-image:url(../../../../../../img/waiter/icon-cpsz.png);
			background-position: 15px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.cplr{
			background-image:url(../../../../../../img/waiter/icon-cpsz.png);
			background-position: -130px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.tcsz{
			background-image:url(../../../../../../img/waiter/icon-cpsz.png);
			background-position: -280px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.kwsz{
			background-image:url(../../../../../../img/waiter/icon-cpsz.png);
			background-position: -430px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.cpjx{
			background-image:url(../../../../../../img/waiter/icon-cpsz.png);
			background-position: -580px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.cpxf{
			background-image:url(../../../../../../img/waiter/icon-cpxf.png);
			background-position: center center;
			background-size: 70%;
    		background-repeat: no-repeat;
		}
		.ku-item.gqlb{
			background-image:url(../../../../../../img/waiter/icon-cpsz.png);
			background-position: -725px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.cptp{
			background-image:url(../../../../../../img/waiter/icon-cpsz.png);
			background-position: 15px -160px;
    		background-repeat: no-repeat;
		}
		.ku-item.lcqy{
			background-image:url(../../../../../../img/waiter/icon-czsz.png);
			background-position: 20px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.czzl{
			background-image:url(../../../../../../img/waiter/icon-czsz.png);
			background-position: -135px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.czrs{
			background-image:url(../../../../../../img/waiter/icon-czsz.png);
			background-position: -285px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.wmcd{
			background-image:url(../../../../../../img/waiter/icon-czsz.png);
			background-position: -435px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.czmx{
			background-image:url(../../../../../../img/waiter/icon-czsz.png);
			background-position: -585px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.scy{
			background-image:url(../../../../../../img/waiter/icon-scy.png);
			background-position: 13px 20px;
			background-size: 80%;
    		background-repeat: no-repeat;
		}
		.ku-item.dyjsz{
			background-image:url(../../../../../../img/waiter/icon-dysz.png);
			background-position: 20px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.cdsz{
			background-image:url(../../../../../../img/waiter/icon-dysz.png);
			background-position: -135px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.padsz{
			background-image:url(../../../../../../img/waiter/icon-dysz.png);
			background-position: -290px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.dpdy{
			background-image:url(../../../../../../img/waiter/icon-dysz.png);
			background-position: -440px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.zfsz{
			background-image:url(../../../../../../img/waiter/icon-sysz.png);
			background-position: 15px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.fysz{
			background-image:url(../../../../../../img/waiter/icon-sysz.png);
			background-position: -125px 20px;
    		background-repeat: no-repeat;
		}
		.ku-item.tucsz{
			background-image:url(../../../../../../img/waiter/icon-sysz.png);
			background-position: -270px 20px;
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
		.ku-item.kwxf{
			background-image:url(../../../../../../img/waiter/icon-kwxf.png);
			background-position: 22px 20px;
			background-size: 60% ;
    		background-repeat: no-repeat;
		}
		.ku-item.plxg{
			background-image:url(../../../../../../img/waiter/icon-cpplxg.png);
			background-position: 22px 20px;
			background-size: 60% ;
    		background-repeat: no-repeat;
		}
		.ku-item.cdplsz{
			background-image:url(../../../../../../img/waiter/icon-cdplsz.png);
			background-position: 22px 20px;
			background-size: 60% ;
    		background-repeat: no-repeat;
		}
		.ku-item.tcxf{
			background-image:url(../../../../../../img/waiter/icon-tcxf.png);
			background-position: 22px 20px;
			background-size: 60% ;
    		background-repeat: no-repeat;
		}
		.ku-item.pfxf{
			background-image:url(../../../../../../img/waiter/icon-pfxf.png);
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
					<?php elseif ($type==4):?>
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
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>''))));?>
	<?php elseif($type==1):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','餐桌设置'),'url'=>''))));?>
	<?php elseif($type==2):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','打印设置'),'url'=>''))));?>
	<?php elseif($type==3):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','收银设置'),'url'=>''))));?>
	<?php elseif($type==4):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','配方设置'),'url'=>''))));?>
	<?php endif;?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class=" fa <?php if($type==0){echo '';}else{echo 'cf-black';}?> fa-edit"></i><a href="<?php echo $this->createUrl('product/list',array('companyId'=>$this->companyId,'type'=>0));?>"><span class="tab <?php if($type==0){ echo 'tab-active';}?>"><?php echo yii::t('app','菜品设置');?></span></a><em class=" fa <?php if($type==1){echo '';}else{echo 'cf-black';}?> fa-wheelchair">&nbsp</em><a href="<?php echo $this->createUrl('product/list',array('companyId'=>$this->companyId,'type'=>1));?>"><span class="tab <?php if($type==1){ echo 'tab-active';}?>" ><?php echo yii::t('app',' 餐桌设置');?></span></a><em class=" fa <?php if($type==2){echo '';}else{echo 'cf-black';}?> fa-print">&nbsp</em><a href="<?php echo $this->createUrl('product/list',array('companyId'=>$this->companyId,'type'=>2));?>"><span class="tab <?php if($type==2){ echo 'tab-active';}?>" ><?php echo yii::t('app',' 打印设置');?></span></a><em class=" fa <?php if($type==3){echo '';}else{echo 'cf-black';}?> fa-cny">&nbsp</em><a href="<?php echo $this->createUrl('product/list',array('companyId'=>$this->companyId,'type'=>3));?>"><span class="tab <?php if($type==3){ echo 'tab-active';}?>"><?php echo yii::t('app',' 收银设置');?></span></a><em class=" fa <?php if($type==4){echo '';}else{echo 'cf-black';}?> fa-calendar">&nbsp</em><a href="<?php echo $this->createUrl('product/list',array('companyId'=>$this->companyId,'type'=>4));?>"><span class="tab <?php if($type==4){ echo 'tab-active';}?>" ><?php echo yii::t('app','配方设置');?></span></a></div>
					<div class="actions">
						<?php if($type == 4):?><a class="btn blue relation" href="javascript:;"> <?php echo yii::t('app','查看关系图');?></a>
						
						<?php endif;?>
					</div>
				</div>
				<div class="portlet-body" style="min-height: 750px">
					<?php if($type==0):?>
					<a href="<?php echo $this->createUrl('productCategory/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple cpfl"></div>
							<div class="ku-item-info">菜品分类</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('product/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple cplr"></div>
							<div class="ku-item-info">菜品录入</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('productSet/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple tcsz"></div>
							<div class="ku-item-info">套餐设置</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('taste/index',array('companyId'=>$this->companyId,'type'=>'0'));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple kwsz"></div>
							<div class="ku-item-info">口味设置</div>
						</div>
					</a>
					<?php if(Yii::app()->user->role < User::SHOPKEEPER):?>
					<a href="<?php echo $this->createUrl('copyproduct/index',array('companyId'=>$this->companyId));?>">
						<div id="cpxf" class="pull-left margin-left-right">
							<div class="ku-item ku-purple cpxf"></div>
							<div class="ku-item-info">菜品下发</div>
						</div>
					</a>
					
					<a href="<?php echo $this->createUrl('copyproductSet/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple tcxf"></div>
							<div class="ku-item-info">套餐下发</div>
						</div>
					</a>
					
					<a href="<?php echo $this->createUrl('copytaste/index',array('companyId'=>$this->companyId,'type'=>'0'));?>">
						<div id="kwxf" class="pull-left margin-left-right">
							<div class="ku-item ku-purple kwxf"></div>
							<div class="ku-item-info">口味下发</div>
						</div>
					</a>
					<?php endif;?>
					<a href="<?php echo $this->createUrl('muchupdateProd/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple plxg"></div>
							<div class="ku-item-info">批量修改</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('productClean/index',array('companyId'=>$this->companyId,'typeId'=>'product'));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple gqlb"></div>
							<div class="ku-item-info">估清列表</div>
						</div>
					</a>
					
					<a href="<?php echo $this->createUrl('productSim/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple cpjx"></div>
							<div class="ku-item-info">菜品简写</div>
						</div>
					</a>
					
					<a href="<?php echo $this->createUrl('productImg/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple cptp"></div>
							<div class="ku-item-info">产品图片</div>
						</div>
					</a>
					<?php elseif($type==1):?>
					<a href="<?php echo $this->createUrl('floor/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple lcqy"></div>
							<div class="ku-item-info">楼层区域</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('siteType/index',array('companyId'=>$this->companyId,'type'=>0));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple czzl"></div>
							<div class="ku-item-info">餐桌种类</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('sitePersons/index',array('companyId'=>$this->companyId,'type'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple czrs"></div>
							<div class="ku-item-info">餐桌人数</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('siteChannel/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple wmcd"></div>
							<div class="ku-item-info">外卖渠道</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('site/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple czmx"></div>
							<div class="ku-item-info">餐桌明细</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('takeawayMember/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple scy"></div>
							<div class="ku-item-info">外卖送餐员</div>
						</div>
					</a>
					<?php elseif($type==2):?>
					<?php if(Yii::app()->user->role <= User::SHOPKEEPER_VICE):?>
					<a href="<?php echo $this->createUrl('printer/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple dyjsz"></div>
							<div class="ku-item-info">打印机设置</div>
						</div>
					</a>
                                        
					<a href="<?php echo $this->createUrl('printerWay/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple cdsz"></div>
							<div class="ku-item-info">厨打设置</div>
						</div>
					</a>
                                        <?php if(Yii::app()->user->role <= 7):?>
                                        <a href="<?php echo $this->createUrl('CopyPrinter/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple dyjsz"></div>
							<div class="ku-item-info">一键下发</div>
						</div>
					</a>
                                        <?php endif;?>
					<a href="<?php echo $this->createUrl('pad/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple padsz"></div>
							<div class="ku-item-info">PAD设置</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('productPrinter/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple dpdy"></div>
							<div class="ku-item-info">单品厨打对应</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('muchprinterProd/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple cdplsz"></div>
							<div class="ku-item-info">厨打批量设置</div>
						</div>
					</a>
					<?php endif;?>
					<?php elseif($type==3):?>
					<a href="<?php echo $this->createUrl('payMethod/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple zfsz"></div>
							<div class="ku-item-info">支付设置</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('basicFee/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple fysz"></div>
							<div class="ku-item-info">费用设置</div>
						</div>
					</a>
					 
					<a href="<?php echo $this->createUrl('retreat/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple tucsz"></div>
							<div class="ku-item-info">退菜设置</div>
						</div>
					</a>
					<!--
					<a href="<?php echo $this->createUrl('bom/bom',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple ps"></div>
							<div class="ku-item-info">盘损</div>
						</div>
					</a>
					 -->
					 <?php elseif($type==4):?>
					<a href="<?php echo $this->createUrl('materialCategory/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple pxfl"></div>
							<div class="ku-item-info">原料分类</div>
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
							<div class="ku-item-info">原料信息</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('materialUnitRatio/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple dwxs"></div>
							<div class="ku-item-info">单位系数</div>
						</div>
					</a>
					<?php if(Yii::app()->user->role <= User::SHOPKEEPER_VICE):?>
					<a href="<?php echo $this->createUrl('productBom/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple bomxx"></div>
							<div class="ku-item-info">产品配方</div>
						</div>
					</a>
					<?php endif;?>
					<?php if(Yii::app()->user->role < User::SHOPKEEPER):?>
					<a href="<?php echo $this->createUrl('copymaterial/index',array('companyId'=>$this->companyId));?>">
						<div id="ylxfs" class="pull-left margin-left-right ylxfs">
							<div class="ku-item ku-purple pxxf"></div>
							<div class="ku-item-info">原料下发</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('copyproductbom/index',array('companyId'=>$this->companyId));?>"> 
						<div id="pfxfs" class="pull-left margin-left-right pfxfs">
							<div class="ku-item ku-purple pfxf"></div>
							<div class="ku-item-info">配方下发</div>
						</div>
					</a>
					<?php endif;?>
					<!-- 
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
					 -->
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	<script>
	$(document).ready(function() {
		window.$ = function(id) {
			  return (typeof id == 'string') ? document.getElementById(id) : id;
			}
		  	var k = $('cpxf');
		  	var k2 = $('kwxf');
		  	var k3 = $('ylxfs');
		  	var k4 = $('pfxfs');
		  	//if(!k) return;
		  	if(k){
			  	onhover(function() {
			      	msgfunction('cpxf');
			  	}, k, 1500);
				onhover(function() {
					msgfunction('kwxf');
			    }, k2, 1500);
		  	}
		  	if(k3){
				onhover(function() {
					msgfunction('ylxfs');
			    }, k3, 1500);
				onhover(function() {
					msgfunction('pfxfs');
			    }, k4, 1500);
		  	}
		    //alert(1);
		});
		 
		function onhover(fun, obj, time) {
		  var s;
		  obj.onmouseover = function() {
		      s = setTimeout(fun, time);
		    };
		  obj.onmouseout = function() {
		      if(!s) return;
		      clearTimeout(s);
		      layer.closeAll('tips');
		    };
		};
		function msgfunction(type){
			var divid = "#"+type;
			if(type == 'cpxf'){
				var msg = '菜品下发之前，请先进行如下操作：<br/>1、添加菜品分类，并设置二级分类；<br/>2、添加菜品；';
				}
			if(type == 'kwxf'){
				var msg = '口味下发之前，请先进行如下操作：<br/>1、添加口味；<br/>2、进行菜品的口味对应；<br/>3、进行菜品下发；';
				}
			if(type == 'ylxfs'){
				var msg = '原料下发之前，请先进行如下操作：<br/>1、添加原料分类；<br/>2、添加入库单位和零售单位；<br/>3、设置单位系数；<br/>4、添加原料信息；';
				}
			if(type == 'pfxfs'){
				var msg = '配方下发之前，请先进行如下操作：<br/>1、添加产品配方详情；<br/>2、进行原料下发；<br/>';
				}
			//alert(1);
			layer.tips(msg,divid, {
				  tips: [4, '#78BA32'],
				  time: 0,
				  shift: 5,
				  closeBtn: 0,
				});
			
			}
//         $(document).ready(function() {
//             $('.relation').click(function(){
//                 $('.modal').modal();
//            });
//         });
	</script>