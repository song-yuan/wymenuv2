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
					<span class="title">首页</span>					
					</a>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('company' , 'companyWifi', 'user'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">基础信息</span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'company') echo 'active';?>"><a href="<?php echo $this->createUrl('company/index',array('companyId' => $this->companyId));?>">店铺管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'companyWifi') echo 'active';?>"><a href="<?php echo $this->createUrl('companyWifi/index',array('companyId' => $this->companyId));?>">店铺WIFI设定</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'payMethod') echo 'active';?>""><a href="<?php echo $this->createUrl('payMethod/index',array('companyId' => $this->companyId));?>">支付方式设定</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'user') echo 'active';?>"><a href="<?php echo $this->createUrl('user/index' , array('companyId' =>$this->companyId));?>">操作员管理</a></li>
						<li class=""><a href="">基础数据同步设定</a></li>
					</ul>
				</li>
                                <li class="<?php if(in_array(Yii::app()->controller->id , array('product','productSet','productImg','productCategory','retreat','productPrinter','productClean','productWeight','productSales','productSpecial', 'productTempprice'))) echo 'active';?>">
                                        <a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">产品管理</span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'productCategory') echo 'active';?>"><a href="<?php echo $this->createUrl('productCategory/index',array('companyId' => $this->companyId));?>">产品分类</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'product') echo 'active';?>"><a href="<?php echo $this->createUrl('product/index',array('companyId' => $this->companyId));?>">单品管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productSet') echo 'active';?>"><a href="<?php echo $this->createUrl('productSet/index',array('companyId' => $this->companyId));?>">套餐管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productTempprice') echo 'active';?>"><a href="<?php echo $this->createUrl('productTempprice/index',array('companyId' => $this->companyId));?>">时价菜管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productSpecial') echo 'active';?>"><a href="<?php echo $this->createUrl('productSpecial/index',array('companyId' => $this->companyId));?>">特价菜管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productSales') echo 'active';?>"><a href="<?php echo $this->createUrl('productSales/index',array('companyId' => $this->companyId));?>">优惠活动管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productClean') echo 'active';?>"><a href="<?php echo $this->createUrl('productClean/index',array('companyId' => $this->companyId,'typeId'=>'product'));?>">沽清列表</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'productImg') echo 'active';?>"><a href="<?php echo $this->createUrl('productImg/index',array('companyId' => $this->companyId));?>">产品图片管理</a></li>
                                                <li class="<?php if(Yii::app()->controller->id == 'productPrinter') echo 'active';?>"><a href="<?php echo $this->createUrl('productPrinter/index',array('companyId' => $this->companyId));?>">单品打印方式管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'retreat') echo 'active';?>"><a href="<?php echo $this->createUrl('retreat/index',array('companyId' => $this->companyId));?>">退菜理由选项设定</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('taste'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">口味设定</span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'taste' && in_array($this->getAction()->getId(),array('index','create','update')) &&(isset($_GET['type'])&&$_GET['type']==1)) echo 'active';?>"><a href="<?php echo $this->createUrl('taste/index',array('companyId' => $this->companyId,'type'=>1));?>">整单口味选项</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'taste' && in_array($this->getAction()->getId(),array('index','create','update')) &&(!isset($_GET['type'])||(isset($_GET['type'])&&$_GET['type']==0))) echo 'active';?>"><a href="<?php echo $this->createUrl('taste/index',array('companyId' => $this->companyId));?>">单品口味选项</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'taste' && in_array($this->getAction()->getId(),array('productTaste','updateProductTaste'))) echo 'active';?>"><a href="<?php echo $this->createUrl('taste/productTaste',array('companyId' => $this->companyId));?>">单品口味对应</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('feedback'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">反馈选项</span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'feedback' &&(isset($_GET['allflag'])&&$_GET['allflag']==1)) echo 'active';?>"><a href="<?php echo $this->createUrl('feedback/index',array('companyId' => $this->companyId,'allflag'=>1));?>">整单反馈选项</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'feedback' &&(!isset($_GET['allflag'])||(isset($_GET['allflag'])&&$_GET['allflag']==0))) echo 'active';?>"><a href="<?php echo $this->createUrl('feedback/index',array('companyId' => $this->companyId));?>">单品反馈选项</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('' , ''))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">订单管理</span>					
					</a>
					<ul class="sub-menu">
						<li class=""><a href="">历史订单查询</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('site' , 'siteType', 'floor'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">餐桌包厢管理</span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'floor') echo 'active';?>"><a href="<?php echo $this->createUrl('floor/index',array('companyId' => $this->companyId));?>">楼层管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'siteType') echo 'active';?>"><a href="<?php echo $this->createUrl('siteType/index',array('companyId' => $this->companyId));?>">餐桌种类</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'site') echo 'active';?>"><a href="<?php echo $this->createUrl('site/index',array('companyId' => $this->companyId));?>">餐桌包厢明细</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('printer' , 'printerWay'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">打印机管理</span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'printer' && $this->getAction()->getId() == 'index') echo 'active';?>"><a href="<?php echo $this->createUrl('printer/index',array('companyId' => $this->companyId));?>">打印机管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'printerWay') echo 'active';?>"><a href="<?php echo $this->createUrl('printerWay/index',array('companyId' => $this->companyId));?>">打印方案</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'printer' && $this->getAction()->getId() == 'list') echo 'active';?>"><a href="<?php echo $this->createUrl('printer/list',array('companyId' => $this->companyId));?>">清单打印机</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('' , ''))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">日常统计</span>					
					</a>
					<ul class="sub-menu">
						<li class=""><a href="">账单日结</a></li>
						<li class=""><a href="">账单查询</a></li>
					</ul>
				</li>
				<!--
				<li class="<?php if(Yii::app()->controller->id == 'order') echo 'active';?>">
					<a href="<?php echo $this->createUrl('order/index' , array('companyId' =>$this->companyId));?>">
					<i class="fa fa-list-alt"></i> 
					<span class="title">订单管理</span>					
					</a>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('product' , 'productCategory'))) echo 'active';?>">
					<a href="javascript:;">
					<i class="fa fa-gift"></i> 
					<span class="title">产品管理</span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'productCategory') echo 'active';?>"><a href="<?php echo $this->createUrl('productCategory/index' , array('companyId' =>$this->companyId));?>">分类管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'product') echo 'active';?>"><a href="<?php echo $this->createUrl('product/index' , array('companyId' =>$this->companyId));?>">产品管理</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('site' , 'siteType'))) echo 'active';?>">
					<a href="<?php echo $this->createUrl('site/index');?>">
					<i class="fa fa-map-marker"></i> 
					<span class="title">位置管理</span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'siteType') echo 'active';?>"><a href="<?php echo $this->createUrl('siteType/index' , array('companyId' =>$this->companyId));?>">座位类型管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'site') echo 'active';?>"><a href="<?php echo $this->createUrl('site/index' , array('companyId' =>$this->companyId));?>">座位管理</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('printer' , 'department'))) echo 'active';?>">
					<a href="<?php echo $this->createUrl('site/index');?>">
					<i class="fa fa-home"></i> 
					<span class="title">操作间管理</span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'department') echo 'active';?>"><a href="<?php echo $this->createUrl('department/index' , array('companyId' =>$this->companyId));?>">操作间管理</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'printer') echo 'active';?>"><a href="<?php echo $this->createUrl('printer/index' , array('companyId' =>$this->companyId));?>">打印机管理</a></li>
					</ul>
				</li>
				
				<li class="<?php if(in_array(Yii::app()->controller->id ,array( 'company','companyWifi'))) echo 'active';?>">
					<a href="<?php echo $this->createUrl('company/index');?>">
					<i class="fa fa-briefcase"></i> 
					<span class="title">企业管理</span>					
					</a>
				</li>
				<li class="<?php if(Yii::app()->controller->id == 'user') echo 'active';?>">
					<a href="<?php echo $this->createUrl('user/index' , array('companyId' =>$this->companyId));?>">
					<i class="fa fa-user"></i> 
					<span class="title">操作员管理</span>					
					</a>
				</li>
				-->
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->