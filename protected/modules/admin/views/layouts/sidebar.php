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
				<li class="<?php if(in_array(Yii::app()->controller->id , array('product' , 'productCategory'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">产品管理</span>					
					</a>
					<ul class="sub-menu">
						<li class="<?php if(Yii::app()->controller->id == 'productCategory') echo 'active';?>"><a href="<?php echo $this->createUrl('productCategory/index',array('companyId' => $this->companyId));?>">产品分类</a></li>
						<li class="<?php if(Yii::app()->controller->id == 'product') echo 'active';?>"><a href="<?php echo $this->createUrl('product/index',array('companyId' => $this->companyId));?>">单品管理</a></li>
						<li class=""><a href="">套餐管理</a></li>
						<li class=""><a href="">时价菜管理</a></li>
						<li class=""><a href="">特价菜管理</a></li>
						<li class=""><a href="">优惠活动管理</a></li>
						<li class=""><a href="">沽清列表</a></li>
						<li class=""><a href="">产品图片管理</a></li>
						<li class=""><a href="">单品打印方式管理</a></li>
						<li class=""><a href="">退菜理由选项设定</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('site' , 'siteType'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">口味设定</span>					
					</a>
					<ul class="sub-menu">
						<li class=""><a href="">整单口味选项</a></li>
						<li class=""><a href="">单品口味选项</a></li>
						<li class=""><a href="">套餐管理</a></li>
						<li class=""><a href="">单品口味对应</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('site' , 'siteType'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">反馈信息</span>					
					</a>
					<ul class="sub-menu">
						<li class=""><a href="">整单反馈选项</a></li>
						<li class=""><a href="">单品反馈选项</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('site' , 'siteType'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">订单管理</span>					
					</a>
					<ul class="sub-menu">
						<li class=""><a href="">订单列表</a></li>
						<li class=""><a href="">订单明细列表</a></li>
						<li class=""><a href="">订单口味列表</a></li>
						<li class=""><a href="">订单反馈列表</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('site' , 'siteType'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">餐桌包厢管理</span>					
					</a>
					<ul class="sub-menu">
						<li class=""><a href="">楼层管理</a></li>
						<li class=""><a href="">餐桌种类</a></li>
						<li class=""><a href="">餐桌包厢明细</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('site' , 'siteType'))) echo 'active';?>">
					<a href="">
					<i class="fa fa-map-marker"></i> 
					<span class="title">打印</span>					
					</a>
					<ul class="sub-menu">
						<li class=""><a href="">打印机管理</a></li>
						<li class=""><a href="">打印方案</a></li>
						<li class=""><a href="">清单打印机</a></li>
					</ul>
				</li>
				<li class="<?php if(in_array(Yii::app()->controller->id , array('site' , 'siteType'))) echo 'active';?>">
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