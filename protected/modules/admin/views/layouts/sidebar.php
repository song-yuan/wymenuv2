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
					<a href="<?php echo $this->createUrl('default/index');?>">
					<i class="fa fa-home"></i> 
					<span class="title">首页</span>					
					</a>
				</li>
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
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->