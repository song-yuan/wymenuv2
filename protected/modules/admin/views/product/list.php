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
	
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class=" fa <?php if($type==0){echo '';}else{echo 'cf-black';}?> fa-edit"></i><a href="<?php echo $this->createUrl('product/list',array('companyId'=>$this->companyId,'type'=>0));?>"><span class="tab <?php if($type==0){ echo 'tab-active';}?>"><?php echo yii::t('app','菜品设置');?></span></a><em class=" fa <?php if($type==1){echo '';}else{echo 'cf-black';}?> fa-wheelchair">&nbsp</em><a href="<?php echo $this->createUrl('product/list',array('companyId'=>$this->companyId,'type'=>1));?>"><span class="tab <?php if($type==1){ echo 'tab-active';}?>" ><?php echo yii::t('app',' 餐桌设置');?></span></a><em class=" fa <?php if($type==2){echo '';}else{echo 'cf-black';}?> fa-print">&nbsp</em><a href="<?php echo $this->createUrl('product/list',array('companyId'=>$this->companyId,'type'=>2));?>"><span class="tab <?php if($type==2){ echo 'tab-active';}?>" ><?php echo yii::t('app',' 打印设置');?></span></a><em class=" fa <?php if($type==3){echo '';}else{echo 'cf-black';}?> fa-cny">&nbsp</em><a href="<?php echo $this->createUrl('product/list',array('companyId'=>$this->companyId,'type'=>3));?>"><span class="tab <?php if($type==3){ echo 'tab-active';}?>"><?php echo yii::t('app',' 收银设置');?></span></a><em class=" fa <?php if($type==4){echo '';}else{echo 'cf-black';}?> fa-calendar">&nbsp</em><a href="<?php echo $this->createUrl('product/list',array('companyId'=>$this->companyId,'type'=>4));?>"><span class="tab <?php if($type==4){ echo 'tab-active';}?>" ><?php echo yii::t('app','配方设置');?></span></a></div>
					<div class="actions">
						<?php if($type == 4):?><a class="btn blue relation" href="javascript:;"> <?php echo yii::t('app','查看关系图');?></a>
						
						<?php endif;?>
					</div>
				</div>
				<div class="portlet-body clearfix" >
					<?php if($type==0):?>
					<div class="panel_body row">
						<p>菜品添加</p>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('productCategory/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>菜品分类</div>
			                	<div class="list_small">添加菜品的一级分类和二级分类信息</div>
			                	</div>
			                </a> 
	                	</div>
	                	<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('product/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>菜品录入</div>
			                	<div class="list_small">添加新的菜品信息以及选择上下架</div>
			                	</div>
			                </a> 
	                	</div>
	                	<div style="display: none;" class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('productGroup/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>套餐分组</div>
			                	<div class="list_small">添加套餐一级分类和二级分类信息</div>
			                	</div>
			                </a> 
	                	</div>
	                	<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('productSet/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>套餐设置</div>
			                	<div class="list_small">添加新的套餐名称以及套餐明细</div>
			                	</div>
			                </a> 
	                	</div>
	                	<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('taste/index',array('companyId'=>$this->companyId,'type'=>'0'));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>口味设置</div>
			                	<div class="list_small">为不同菜品设置相应口味</div>
			                	</div>
			                </a> 
	                	</div>
	                </div>
	                <div class="panel_body row">
	                	<p>菜品修改</p>
	                	<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('muchupdateProd/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>批量修改</div>
			                	<div class="list_small">将多个菜品的信息进行勾选修改</div>
			                	</div>
			                </a> 
	                	</div>
            			<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('productSim/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>菜品简写</div>
			                	<div class="list_small">将菜品名称设置成菜品首字母或者代码等</div>
			                	</div>
			                </a> 
	                	</div>
	                	<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('productSim/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>产品图片</div>
			                	<div class="list_small">上传菜品的对应图片</div>
			                	</div>
			                </a> 
	                	</div>
	                </div>
					<?php if(Yii::app()->user->role < User::SHOPKEEPER):?>
					<div class="panel_body row">
						<p>菜品下发</p>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('copyproduct/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>菜品下发</div>
			                	<div class="list_small">将录入好的菜品下发到所需店铺</div>
			                	</div>
			                </a> 
	                	</div>
	                	<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('copyproductSet/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>套餐下发</div>
			                	<div class="list_small">将录入好的套餐下发到所需店铺</div>
			                	</div>
			                </a> 
	                	</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('copytaste/index',array('companyId'=>$this->companyId,'type'=>'0'));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>口味下发</div>
			                	<div class="list_small">将录入好的口味下发到所需店铺</div>
			                	</div>
			                </a> 
	                	</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('copycatep/index',array('companyId'=>$this->companyId,'type'=>'0'));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>分类下发</div>
			                	<div class="list_small"></div>
			                	</div>
			                </a> 
						</div>
						<?php endif;?>
					</div>
					<div class="panel_body row">
						<p>估清</p>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('productClean/index',array('companyId'=>$this->companyId,'typeId'=>'product'));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>估清列表</div>
			                	<div class="list_small">查询菜品的销量以及剩余库存等</div>
			                	</div>
			                </a> 
						</div>
						</div>
					<?php elseif($type==1):?>
					<div class="panel_body row">
						<p>餐桌设置</p>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('floor/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>楼层区域</div>
			                	<div class="list_small">为桌台设置楼层</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('sitetype/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>餐桌种类</div>
			                	<div class="list_small">设置餐桌的座位类型以及编号</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('sitePersons/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>餐桌人数</div>
			                	<div class="list_small">为餐桌设置一个人数限定</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('site/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>餐桌明细</div>
			                	<div class="list_small">将设置好的楼层区域、餐桌种类、餐桌人数进行整合添加</div>
			                	</div>
			                </a> 
						</div>
					</div>
					<div class="panel_body row">
					<p>外卖设置</p>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('siteChannel/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>外卖渠道</div>
			                	<div class="list_small">设置外卖渠道的名称信息</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('siteChannel/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>外卖送餐员</div>
			                	<div class="list_small">设置外卖送餐员的基础信息</div>
			                	</div>
			                </a> 
						</div>
					</div>
					<?php elseif($type==2):?>
					<?php if(Yii::app()->user->role <= User::SHOPKEEPER_VICE):?>
					<div class="panel_body row">
						<p>设置</p>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('printer/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>打印机设置</div>
			                	<div class="list_small">添加打印机的名称以及IP地址等信息</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('printerWay/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>厨打设置</div>
			                	<div class="list_small">添加厨打的名称、打印方式以及打印分数等</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('productLabel/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>标签打印</div>
			                	<div class="list_small"></div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('pad/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>PAD设置</div>
			                	<div class="list_small"></div>
			                	</div>
			                </a> 
						</div>
					</div>
                    
					<div class="panel_body row">
						<p>操作</p>
						<?php if(Yii::app()->user->role <= 7):?>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('CopyPrinter/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>一键下发</div>
			                	<div class="list_small"></div>
			                	</div>
			                </a> 
						</div>
                    	<?php endif;?>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('productPrinter/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>单品厨打对应</div>
			                	<div class="list_small">为单个菜品对应设置好的厨打方案</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('muchprinterProd/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>厨打批量设置</div>
			                	<div class="list_small">为多个菜品批量设置厨打方案</div>
			                	</div>
			                </a> 
						</div>
					</div>
					<?php endif;?>
					<?php elseif($type==3):?>
					<div class="panel_body row">
						<p></p>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('payMethod/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>支付设置</div>
			                	<div class="list_small">为点餐结账时设置不同的支付方式</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('basicFee/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>费用设置</div>
			                	<div class="list_small">餐位费、打包费、送餐费设置基础费用等</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('retreat/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>退菜设置</div>
			                	<div class="list_small">设置退菜原因以及提示信息</div>
			                	</div>
			                </a> 
						</div>
					</div>
					<!--
					<a href="<?php echo $this->createUrl('bom/bom',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple ps"></div>
							<div class="ku-item-info">盘损</div>
						</div>
					</a>
					 -->
					 <?php elseif($type==4):?>
					 <div class="panel_body row">
					 	<p>原料及配方</p>
					 	<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('materialCategory/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>原料分类</div>
			                	<div class="list_small">添加原料的类别和基础信息</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('materialUnit/index',array('companyId'=>$this->companyId,'type'=>0));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>入库单位</div>
			                	<div class="list_small">为商品设置整箱的单位名称、类型、序号以及规格</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('materialUnit/index',array('companyId'=>$this->companyId,'type'=>1));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>零售单位</div>
			                	<div class="list_small">为商品设置单个的单位名称、类型、序号以及规格</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('materialUnitRatio/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>单位系数</div>
			                	<div class="list_small">根据入库单位和零售单位设置相应的实数，例如（一箱里有100个馒头对应系数就是100）</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('productMaterial/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>原料信息</div>
			                	<div class="list_small">为原料整合设置好的原料分类、入库单位、零售单位并设置原料的名称、类型以及编号等</div>
			                	</div>
			                </a> 
						</div>
						<?php if(Yii::app()->user->role <= User::SHOPKEEPER_VICE):?>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('productBom/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>产品配方</div>
			                	<div class="list_small">为不同的菜品添加设置好的配方</div>
			                	</div>
			                </a> 
						</div>
						<?php endif;?>
					 </div>
					<?php if(Yii::app()->user->role < User::SHOPKEEPER):?>
					<div class="panel_body row">
					<p>原料及配方下发</p>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('copymaterial/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>原料下发</div>
			                	<div class="list_small">将设置好的原料信息下发到所需店铺</div>
			                	</div>
			                </a> 
						</div>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('copyproductbom/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>配方下发</div>
			                	<div class="list_small">将设置好的配方信息下发到所需店铺</div>
			                	</div>
			                </a> 
						</div>
					</div>
					<?php endif;?>
					<?php endif;?>
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