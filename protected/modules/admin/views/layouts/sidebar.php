		<!-- BEGIN SIDEBAR -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->        	
			<ul class="page-sidebar-menu">
				<li>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li class="<?php if(Yii::app()->controller->id == 'default') echo 'active';?>">
					<a href="<?php echo $this->createUrl('default/index',array('companyId' => $this->companyId));?>">
					<i class="fa fa-home"></i> 
					<span class="title"><?php echo yii::t('app','首页');?></span>					
					</a>
				</li>
                                <?php if(Yii::app()->user->role < '3') : ?>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('company' ,'payMethod', 'companyWifi', 'user' ,'synchronous' ))) echo 'active';?>">
					<a href="">
                                            <i class="fa fa-cog"></i> 
					<span class="title"><?php echo yii::t('app','基础信息');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'company') echo 'active';?>"><a href="<?php echo $this->createUrl('company/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','店铺管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'companyWifi') echo 'active';?>"><a href="<?php echo $this->createUrl('companyWifi/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','店铺WIFI设定');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'payMethod') echo 'active';?>""><a href="<?php echo $this->createUrl('payMethod/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','支付方式设定');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'user') echo 'active';?>"><a href="<?php echo $this->createUrl('user/index' , array('companyId' =>$this->companyId));?>"><?php echo yii::t('app','操作员管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'synchronous') echo 'active';?>"><a href="<?php echo $this->createUrl('synchronous/index' , array('companyId' =>$this->companyId , 'type' => "manul"));?>"><?php echo yii::t('app','基础数据同步设定');?></a></li>
					</ul>
				</li>
                                <li class="<?php if(in_array(Yii::app()->controller->id , array('product','productAddition','productSet','productSim','productImg','productCategory','retreat','productPrinter','productClean','productWeight','productSales','productSpecial', 'productTempprice'))) echo 'active';?>">
                                        <a href="">
					<i class="fa fa-coffee"></i> 
					<span class="title"><?php echo yii::t('app','产品管理');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'productCategory') echo 'active';?>"><a href="<?php echo $this->createUrl('productCategory/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','产品分类');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'product') echo 'active';?>"><a href="<?php echo $this->createUrl('product/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','单品管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productSet') echo 'active';?>"><a href="<?php echo $this->createUrl('productSet/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','套餐管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productSim') echo 'active';?>"><a href="<?php echo $this->createUrl('productSim/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','菜品简写管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productAddition') echo 'active';?>"><a href="<?php echo $this->createUrl('productAddition/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','加菜管理');?></a></li>
						<!--<li class="<?php if(Yii::app()->controller->id == 'productSet') echo 'active';?>"><a href="<?php echo $this->createUrl('productSet/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','套餐管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productTempprice') echo 'active';?>"><a href="<?php echo $this->createUrl('productTempprice/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','时价菜管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productSpecial') echo 'active';?>"><a href="<?php echo $this->createUrl('productSpecial/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','特价菜管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productSales') echo 'active';?>"><a href="<?php echo $this->createUrl('productSales/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','优惠活动管理');?></a></li>-->
						<li class="<?php if(Yii::app()->controller->id == 'productClean') echo 'active';?>"><a href="<?php echo $this->createUrl('productClean/index',array('companyId' => $this->companyId,'typeId'=>'product'));?>"><?php echo yii::t('app','沽清列表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productImg') echo 'active';?>"><a href="<?php echo $this->createUrl('productImg/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','产品图片管理');?></a></li>
                                                <li class="<?php if(Yii::app()->controller->id == 'productPrinter') echo 'active';?>"><a href="<?php echo $this->createUrl('productPrinter/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','单品厨打');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'retreat') echo 'active';?>"><a href="<?php echo $this->createUrl('retreat/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','退菜理由选项设定');?></a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('taste'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-cutlery"></i> 
					<span class="title"><?php echo yii::t('app','口味设定');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'taste' && in_array($this->getAction()->getId(),array('index','create','update')) &&(isset($_GET['type'])&&$_GET['type']==1)) echo 'active';?>"><a href="<?php echo $this->createUrl('taste/index',array('companyId' => $this->companyId,'type'=>1));?>"><?php echo yii::t('app','整单口味选项');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'taste' && in_array($this->getAction()->getId(),array('index','create','update')) &&(!isset($_GET['type'])||(isset($_GET['type'])&&$_GET['type']==0))) echo 'active';?>"><a href="<?php echo $this->createUrl('taste/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','单品口味选项');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'taste' && in_array($this->getAction()->getId(),array('productTaste','updateProductTaste'))) echo 'active';?>"><a href="<?php echo $this->createUrl('taste/productTaste',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','单品口味对应');?></a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('feedback'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-bullhorn"></i> 
					<span class="title"><?php echo yii::t('app','反馈选项');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'feedback' &&(isset($_GET['allflag'])&&$_GET['allflag']==1)) echo 'active';?>"><a href="<?php echo $this->createUrl('feedback/index',array('companyId' => $this->companyId,'allflag'=>1));?>"><?php echo yii::t('app','整单反馈选项');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'feedback' &&(!isset($_GET['allflag'])||(isset($_GET['allflag'])&&$_GET['allflag']==0))) echo 'active';?>"><a href="<?php echo $this->createUrl('feedback/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','单品反馈选项');?></a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('orderManagement'))) echo 'active';?>">
					<a href="">
					<i class="fa  fa-files-o"></i> 
					<span class="title"><?php echo yii::t('app','订单管理');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'orderManagement' && in_array($this->getAction()->getId(),array('accountStatement',''))) echo 'active';?>"><a href="<?php echo $this->createUrl('orderManagement/accountStatement',array('companyId' => $this->companyId,'begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','日结对账单');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'orderManagement' && in_array($this->getAction()->getId(),array('paymentRecord',''))) echo 'active';?>"><a href="<?php echo $this->createUrl('orderManagement/paymentRecord',array('companyId' => $this->companyId,'begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','付款退款记录');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'orderManagement' && in_array($this->getAction()->getId(),array('index',''))) echo 'active';?>"><a href="<?php echo $this->createUrl('orderManagement/index',array('companyId' => $this->companyId,'begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','历史订单');?></a></li>
					</ul>
				</li>
                                <li class="<?php if(in_array(Yii::app()->controller->id , array('weixin','member','wxlevel','wxcashback','wxpoint','wxpointvalid','wxrecharge'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-comments"></i> 
					<span class="title"><?php echo yii::t('app','微信及会员管理');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'weixin' && in_array($this->getAction()->getId(),array('index'))) echo 'active';?>"><a href="<?php echo $this->createUrl('weixin/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','公众号设置');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'weixin' && in_array($this->getAction()->getId(),array('menu'))) echo 'active';?>"><a href="<?php echo $this->createUrl('weixin/menu',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','发布菜单');?></a></li>
                                                <li class="<?php if(in_array(Yii::app()->controller->id,array('wxlevel','wxcashback','wxpoint','wxpointvalid','wxrecharge')) && in_array($this->getAction()->getId(),array('index','create','update'))) echo 'active';?>"><a href="<?php echo $this->createUrl('wxlevel/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','微信会员设置');?></a></li><!--等级、积分有效期、积分比例、返现的比率、充值阶梯-->
                                                <li class="<?php if(Yii::app()->controller->id == 'weixin' && in_array($this->getAction()->getId(),array('wxmember'))) echo 'active';?>"><a href="<?php echo $this->createUrl('weixin/wxmember',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','微信会员列表');?></a></li><!--会员信息、历史积分、余额、相关营销品（优惠、代金券）、积分记录、充值记录、返现记录、订单记录等-->
                                                <li class="<?php if(Yii::app()->controller->id == 'member' && in_array($this->getAction()->getId(),array('index','create','update','charge'))) echo 'active';?>"><a href="<?php echo $this->createUrl('member/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','传统卡会员列表');?></a></li>
					</ul>
				</li>

								<li class="<?php if(in_array(Yii::app()->controller->id , array('cashcard','normalpromotion','wxRedpacket','privatepromotion','cupon','gift','wxcard','promotionActivity','discount','screen'))) echo 'active';?>">

					<a href="">
					<i class="fa fa-comments"></i> 
					<span class="title"><?php echo yii::t('app','营销管理');?></span>
					</a>
					<ul class="sub-menu">

						<li class="<?php if((Yii::app()->controller->id == 'cashcard' || Yii::app()->controller->id == 'normalpromotion' || Yii::app()->controller->id == 'privatepromotion'  || Yii::app()->controller->id == 'cupon' || Yii::app()->controller->id == 'gift' || Yii::app()->controller->id == 'wxcard' ) && in_array($this->getAction()->getId(),array('index','create','update','detailindex','promotiondetail','code','exchange'))) echo 'active';?>"><a href="<?php echo $this->createUrl('cashcard/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','营销品设置');?></a></li><!--整体设置，普通优惠、专享优惠、代金券-->
						<li class="<?php if(Yii::app()->controller->id == 'wxRedpacket' && in_array($this->getAction()->getId(),array('index','create','update','detailindex','detailrules'))) echo 'active';?>"><a href="<?php echo $this->createUrl('wxRedpacket/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','微信红包');?></a></li><!--管理（概要和明细）、发送规则-->
                        <li class="<?php if(Yii::app()->controller->id == 'promotionActivity' && in_array($this->getAction()->getId(),array('index','create','update','detailindex'))) echo 'active';?>"><a href="<?php echo $this->createUrl('promotionActivity/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','营销活动');?></a></li><!--营销活动管理，添加营销品-->
                        <li class="<?php if(Yii::app()->controller->id == 'screen' && in_array($this->getAction()->getId(),array('index','create','update','discuss'))) echo 'active';?>"><a href="<?php echo $this->createUrl('screen/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','微信电视');?></a></li><!--弹幕电视-->
                        <li class="<?php if(Yii::app()->controller->id == 'discount' && in_array($this->getAction()->getId(),array('index','create','update','detailindex'))) echo 'active';?>"><a href="<?php echo $this->createUrl('discount/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','折扣模板');?></a></li><!--营销活动管理，添加营销品-->
                        <!--  <li class="<?php if(Yii::app()->controller->id == 'cashcard' && in_array($this->getAction()->getId(),array('xxx'))) echo 'active';?>"><a href="<?php echo $this->createUrl('member/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','营销工具');?></a></li><!--大转盘等，先放哪里，直接把云卡拿来-->
                        <!--  <li class="<?php if(Yii::app()->controller->id == 'cashcard' && in_array($this->getAction()->getId(),array('xxx'))) echo 'active';?>"><a href="<?php echo $this->createUrl('member/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','效果分析');?></a></li><!--每个活动/红包，营销品，发送多少，看的人多少，领用多少，使用多少-->
					 </ul>
				</li>
				<!-- <li class="<?php if(in_array(Yii::app()->controller->id , array('customerFlow','salesanalysis','actanalysis','redpacketanalysis'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-comments"></i> 
					<span class="title"><?php echo yii::t('app','营销分析');?></span>
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'customerFlow' && in_array($this->getAction()->getId(),array('index'))) echo 'active';?>"><a href="<?php echo $this->createUrl('customerFlow/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','客流分析');?></a></li><!--整体设置，普通优惠、专享优惠、代金券--
                        <!-- <li class="<?php if(Yii::app()->controller->id == 'salesanalysis' && in_array($this->getAction()->getId(),array('index'))) echo 'active';?>"><a href="<?php echo $this->createUrl('customerFlow/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','销售额分析');?></a></li><!--管理（概要和明细）、发送规则--
                        <li class="<?php if(Yii::app()->controller->id == 'actanalysis' && in_array($this->getAction()->getId(),array('index'))) echo 'active';?>"><a href="<?php echo $this->createUrl('customerFlow/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','活动分析');?></a></li><!--营销活动管理，添加营销品--
                        <li class="<?php if(Yii::app()->controller->id == 'redpacketanalysis' && in_array($this->getAction()->getId(),array('index'))) echo 'active';?>"><a href="<?php echo $this->createUrl('customerFlow/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','折扣模板');?></a></li><!--营销活动管理，添加营销品--
                    </ul>
				</li>-->
				<li class="<?php if(in_array(Yii::app()->controller->id , array('site' , 'siteType', 'floor','sitePersons','siteChannel'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-building"></i> 
					<span class="title"><?php echo yii::t('app','餐桌包厢管理');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'floor') echo 'active';?>"><a href="<?php echo $this->createUrl('floor/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','楼层区域管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'siteType') echo 'active';?>"><a href="<?php echo $this->createUrl('siteType/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','餐桌种类设置');?></a></li>
						
						<li class="<?php if(Yii::app()->controller->id == 'sitePersons') echo 'active';?>"><a href="<?php echo $this->createUrl('sitePersons/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','餐桌人数设置');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'siteChannel') echo 'active';?>"><a href="<?php echo $this->createUrl('siteChannel/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','外卖渠道设置');?></a></li>
						
						<li class="<?php if(Yii::app()->controller->id == 'site') echo 'active';?>"><a href="<?php echo $this->createUrl('site/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','餐桌包厢明细');?></a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('printer' , 'printerWay', 'pad'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-print"></i> 
					<span class="title"><?php echo yii::t('app','硬件管理');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'printer' && $this->getAction()->getId() == 'index') echo 'active';?>"><a href="<?php echo $this->createUrl('printer/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','打印机管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'printerWay') echo 'active';?>"><a href="<?php echo $this->createUrl('printerWay/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','厨打方案');?></a></li>
						<!--<li class="<?php if(Yii::app()->controller->id == 'printer' && $this->getAction()->getId() == 'list') echo 'active';?>"><a href="<?php echo $this->createUrl('printer/list',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','清单打印机');?></a></li>-->
                                                <li class="<?php if(Yii::app()->controller->id == 'pad' && $this->getAction()->getId() == 'index') echo 'active';?>"><a href="<?php echo $this->createUrl('pad/index',array('companyId' => $this->companyId));?>"><?php echo yii::t('app','pad管理');?></a></li>
					</ul>
				</li>
                                <!--
				<li class="<?php if(in_array(Yii::app()->controller->id , array('' , ''))) echo 'active';?>">
					<a href="">
					<i class="fa fa-files-o"></i> 
					<span class="title"><?php echo yii::t('app','订单管理');?></span>					
					</a>
					<ul class="sub-menu">
						<li class=""><a href=""><?php echo yii::t('app','未日结订单');?></a></li>
						<li class=""><a href=""><?php echo yii::t('app','退款记录');?></a></li>
						<li class=""><a href=""><?php echo yii::t('app','日结订单');?></a></li>
					</ul>
				</li>
                                -->
                                <li class="<?php if(in_array(Yii::app()->controller->id , array('statements'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-bar-chart-o"></i> 
					<span class="title"><?php echo yii::t('app','统计报表');?></span>					
					</a>
					<ul class="sub-menu">
						<!-- <li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'salesReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/salesReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','支付营业额报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'cgReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/cgReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','分类营业额报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'productsalesReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/productsalesReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','产品销售报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'orderReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/orderReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','订单统计报表');?></a></li>
						 -->
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'payallReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/payallReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','收款统计(支付方式)');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'incomeReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/incomeReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','营业收入(产品分类)');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'recharge') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/recharge',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','充值记录报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'ceshiproductReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/ceshiproductReport',array('companyId' => $this->companyId,'text'=>'3','ordertype'=>'0','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','产品销售报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'orderdetail') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/orderdetail',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','账单详情报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'businessdataReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/businessdataReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','营业数据报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'channelsproportion') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/channelsproportion',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','渠道占比报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'tableareaReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/tableareaReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','台桌区域报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'retreatdetailReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/retreatdetailReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','退菜明细报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'retreatreasonReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/retreatreasonReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','退菜原因统计报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'cuponReport') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/cuponReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','代金券使用情况报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'diningNum') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/diningNum',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','就餐人数统计报表');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'statements' && $this->getAction()->getId() == 'turnOver') echo 'active';?>"><a href="<?php echo $this->createUrl('statements/turnOver',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>"><?php echo yii::t('app','员工营业额报表');?></a></li>
						
					</ul>
				</li>                                
				
				<!--
				<li class="<?php if(Yii::app()->controller->id == 'order') echo 'active';?>">
					<a href="<?php echo $this->createUrl('order/index' , array('companyId' =>$this->companyId));?>">
					<i class="fa fa-list-alt"></i> 
					<span class="title"><?php echo yii::t('app','订单管理');?></span>					
					</a>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('product' , 'productCategory'))) echo 'active';?>">
					<a href="javascript:;">
					<i class="fa fa-gift"></i> 
					<span class="title"><?php echo yii::t('app','产品管理');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'productCategory') echo 'active';?>"><a href="<?php echo $this->createUrl('productCategory/index' , array('companyId' =>$this->companyId));?>"><?php echo yii::t('app','分类管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'product') echo 'active';?>"><a href="<?php echo $this->createUrl('product/index' , array('companyId' =>$this->companyId));?>"><?php echo yii::t('app','产品管理');?></a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('site' , 'siteType'))) echo 'active';?>">
					<a href="<?php echo $this->createUrl('site/index');?>">
					<i class="fa fa-map-marker"></i> 
					<span class="title"><?php echo yii::t('app','位置管理');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'siteType') echo 'active';?>"><a href="<?php echo $this->createUrl('siteType/index' , array('companyId' =>$this->companyId));?>"><?php echo yii::t('app','座位类型管理');?></a></li>
						<li class="<?php if(Yii::app()->controller->id == 'site') echo 'active';?>"><a href="<?php echo $this->createUrl('site/index' , array('companyId' =>$this->companyId));?>"><?php echo yii::t('app','座位管理');?></a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('printer' , 'department'))) echo 'active';?>">
					<a href="<?php echo $this->createUrl('site/index');?>">
					<i class="fa fa-home"></i> 
					<span class="title"><?php echo yii::t('app','操作间管理');?></span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'department') echo 'active';?>"><a href="<?php echo $this->createUrl('department/index' , array('companyId' =>$this->companyId));?>"><?php echo yii::t('app','操作间管理');?></a></li>
			    		<li class="<?php if(Yii::app()->controller->id == 'printer') echo 'active';?>"><a href="<?php echo $this->createUrl('printer/index' , array('companyId' =>$this->companyId));?>"><?php echo yii::t('app','打印机管理');?></a></li>
					</ul>
				</li>
				
				<li class="<?php if(in_array(Yii::app()->controller->id ,array( 'company','companyWifi'))) echo 'active';?>">
					<a href="<?php echo $this->createUrl('company/index');?>">
					<i class="fa fa-briefcase"></i> 
					<span class="title"><?php echo yii::t('app','企业管理');?></span>					
					</a>
				</li>
				<li class="<?php if(Yii::app()->controller->id == 'user') echo 'active';?>">
					<a href="<?php echo $this->createUrl('user/index' , array('companyId' =>$this->companyId));?>">
					<i class="fa fa-user"></i> 
					<span class="title"><?php echo yii::t('app','操作员管理');?></span>					
					</a>
				</li>
				-->
                                <?php endif; ?>
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->