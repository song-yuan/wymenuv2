
	<div class="page-content">

		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺设置'),'url'=>''))));?>

			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','店铺设置');?></div>
				</div>
				<div class="portlet-body clearfix">
				<div class="panel_body row">
                	<p>店铺管理</p>
	                <div style="display: none;" class="list col-sm-3 col-xs-12">
		                <a href="<?php echo $this->createUrl('company/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-home"></i> 店铺管理</div>
		                <div class="list_small">添加及编辑店铺信息</div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
		                <a href="<?php echo $this->createUrl('user/index',array('companyId'=>$this->companyId,'type'=>0));?>">
		                <div class="list_big "><i class="fa fa-user"></i> 操作员管理</div>
		                <div class="list_small">添加及编辑店铺操作员</div>
		                </a> 
	                </div>
	                <?php if($role <=5):?>
	                <div class="list col-sm-3 col-xs-12">
	                	<a href="<?php echo $this->createUrl('areaGroup/index',array('companyId'=>$this->companyId,'type'=>2));?>" title="添加店铺的类型,用来区分不同的类型的店铺">
							<div class="list_big "><i class="fa fa-th-large"></i> 店铺标签</div>
							<div class="list_small">添加店铺的类型,用来区分不同的类型的店铺</div>
						</a>
					</div>
					<div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('areaGroup/index',array('companyId'=>$this->companyId,'type'=>3));?>" title="管理员在手机中管理的店进行分组显示">
							<div class="list_big "><i class="fa fa-list-ol"></i> 店铺分组</div>
							<div class="list_small">管理员在手机中管理的店进行分组显示</div>
						</a>
					</div>
					<div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('pricegroup/index',array('companyId'=>$this->companyId));?>" title="价格体系 可以添加多套价格体系 根据不到区域添加不同的价格体系  不同的店铺设置不同的价格体系">
							<div class="list_big "><i class="fa fa-th-list"></i> 价格体系</div>
							<div class="list_small">价格体系 可以添加多套价格体系 根据不到区域添加不同的价格体系  不同的店铺设置不同的价格体系</div>
						</a>
					</div>
					<div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('areaGroup/index',array('companyId'=>$this->companyId));?>">
							<div class="list_big "><i class="fa fa-th-list"></i> 地区分组</div>
							<div class="list_small">将不同的店铺分入不同的区域，不同的区域设置不同的配送仓库</div>
						</a>
					</div>
					<?php endif;?>
					
                </div>
				<div class="panel_body row">
                	<p>店铺设置</p>
                	
                	<div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('doubleScreen/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-desktop"></i> 双屏设置</div>
		                <div class="list_small">设置双屏收银机的客显界面、上传双屏机器的客显界面广告宣传图</div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('companyWx/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-comments-o"></i> 微店营业</div>
		                <div class="list_small">微店开关店控制、营业时间设置、总部价格控制。</div>
		                </a> 
	                </div>
	                <?php if($role <=9):?>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('poscode/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-cny"></i> 收银机设置</div>
		                <div class="list_small">添加及查询收银机序列号</div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('copyScreen/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-cloud-download"></i> 双屏下发</div>
		                <div class="list_small">总部用来下发双屏机器客显界面的宣传广告图</div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('announcement/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-bullhorn"></i> 公告设置</div>
		                <div class="list_small">设置微信店铺中的公告信息</div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('companySetting/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-star-half-o"></i> 登陆界面</div>
		                <div class="list_small">设置登录界面底部显示的名称，图标以及标语口号</div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('message/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-star-half-o"></i> 短信套餐</div>
		                <div class="list_small">购买短信条数，用途：实体卡消费时，发送短信提醒。</div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('mtpayConfig/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-star-half-o"></i> 美团支付配置</div>
		                <div class="list_small">当商户支付通道改成美团支付时，需要填写美团支付相关参数配置。</div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('posfee/setindex',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-star-half-o"></i> 软件期限设置</div>
		                <div class="list_small">软件使用期限设置。</div>
		                </a> 
	                </div>
	               <?php endif;?>
	               
                </div>	
                <?php if($role <=1):?>
                <div class="panel_body row">
                	<p>更新</p>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('uploadApk/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-android"></i> app更新</div>
		                <div class="list_small"></div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('postable/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-refresh"></i> 表更新</div>
		                <div class="list_small"></div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('syncFailure/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-bug"></i>同步失败</div>
		                <div class="list_small"></div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('connectUs/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-phone"></i>联系我们</div>
		                <div class="list_small"></div>
		                </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
						<a href="<?php echo $this->createUrl('posfee/index',array('companyId'=>$this->companyId));?>">
		                <div class="list_big"><i class="fa fa-star-half-o"></i> 软件收费配置</div>
		                <div class="list_small">软件使用期限设置。</div>
		                </a> 
	                </div>
                </div>
                <?php endif;?>
					<?php if(Yii::app()->user->role <= 1):?>
<!--					<a href="<?php echo $this->createUrl('weixin/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple wxdp"></div>
							<div class="ku-item-info">微信设置</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('alipay/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple zfbsz"></div>
							<div class="ku-item-info">支付宝设置</div>
						</div>
					</a>
					-->
					<a style="display: none;" href="<?php echo $this->createUrl('payneedinfo/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple rwzl"></div>
							<div class="ku-item-info">入网资料</div>
						</div>
					</a>
					<?php endif;?>
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