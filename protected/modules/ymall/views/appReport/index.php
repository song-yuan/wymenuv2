<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.min.css">
<!--导航栏-->
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	<a id="menu" class="mui-action-menu mui-icon mui-icon-bars mui-pull-right" href="#topPopover"></a>
	<h1 class="mui-title"><?php echo Helper::getCompanyName($this->companyId);?></h1>
</header>
<div id="topPopover" class="mui-modal">
	<header class="mui-bar mui-bar-nav">
		<a class="mui-icon mui-icon-close mui-pull-right" href="#topPopover"></a>
		<h1 class="mui-title">选择店铺</h1>
	</header>
	<div class="mui-content" style="height: 100%;margin-top: 10px;margin-left: 10px;">
		<form action="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>" method="post">
		<ul id="ul">
		<?php foreach($dps as $dp):?>
			<li><input style="width: 20px;height: 20px;vertical-align: middle;" type="checkbox" name="checkbox[]" value="<?php echo $dp['dpid'];?>"><?php echo $dp['company_name'];?></li>
			<?php endforeach;?>
		</ul>
		<div class="input">
			<input type="submit" value="确定">
		</div>
		</form>
	</div>
</div>
<div class="dp">
	<!--今日营业收益-->
	<div class="shou">
		<div class="shou1">
			<h4>今日应收</h4>
			<h3><?php foreach($todayProfit as $today){
				if(!empty($today['pay_amount'])){
					echo $today['pay_amount'];
				}else{
					echo '0';
				}
				}?></h3>
		</div>
		<div class="shou2">
			<h4>今日实收</h4>
			<h3><?php foreach($todayProfit as $today){
				if(!empty($today['pay_amount'])){
					$pay_amount = $today['pay_amount'];
					echo $pay_amount;
				}else{
					echo '0';
				}
				}?></h3>
		</div>
		<div class="shou3">
			<h4>总订单数</h4>
			<h3><?php foreach($orders as $order){
				$reality_total = $order['reality_total'];
				if(!empty($order['counts'])){
					$counts = $order['counts'];
					echo $counts;
				}else{
					echo '0';
				}
				}?></h3>
		</div>
		<div class="shou4">
			<h4>订单平均价</h4>
			<h3><?php 
				if(!empty($counts)){
					echo round($pay_amount/$counts,2);
				}else{
					echo '0';
				}
				?></h3>
		</div>
		<div class="shou5"><hr></div>
		<div class="shou3">
			<h4 >总消费人数</h4>
			<h3><?php foreach($orders as $order){
				if(!empty($order['number'])){
					$number = $order['number'];
					echo $number;
				}else{
					echo '0';
				}
				}?></h3>
		</div>
		<div class="shou4">
			<h4>人均消费</h4>
			<h3><?php 
				if(!empty($number)){
					$round = round($pay_amount/$number,2);
					echo $round;
				}else{
					echo '0';
				}
				?></h3>
		</div>
	</div>

	<ul class="mui-table-view"> 
	        <li class="mui-table-view-cell mui-collapse">
	            <a class="mui-navigate-right" href="#">今日收益</a>
	            <div class="mui-collapse-content">
	            	<div class="time">
	            		当前时间：<span><?php echo date('Y-m-d');?></span>
	            	</div> 
	                <div class="ys">
						<div class="ys1">
							<h6>应收</h6>
							<span><?php if(!empty($pay_amount)){
								echo $pay_amount;
							}else{
								echo '0';
							}?></span>
						</div>
						<div class="ys2">
							<h6>折扣</h6>
							<span><?php 
					if(!empty($pay_amount)){
						echo $reality_total-$pay_amount;
					}else{
						echo '0';
					}
					?></span>
						</div>
						<div class="ys4">
							<h6>人均</h6>
							<span><?php if(!empty($number)){
						$round = round($pay_amount/$number,2);
						echo $round;
					}else{
						echo '0';
					}?></span>
						</div>
						<div class="ys5">
							<h6>订单数</h6>
							<span><?php foreach($orders as $order){
					$reality_total = $order['reality_total'];
					if(!empty($order['counts'])){
						$counts = $order['counts'];
						echo $counts;
					}else{
						echo '0';
					}
					}?></span>
						</div>
						<div class="ys6">
							<h6>总客人</h6>
							<span><?php foreach($orders as $order){
					if(!empty($order['number'])){
						$number = $order['number'];
						echo $number;
					}else{
						echo '0';
					}
					}?></span>
						</div>
						<div class="ys7">
									<a href="turnover.html">查看详细营业额>>></a>
							</div>
						</div>
		            </div>
		        </li>
	    	</ul>
			<ul class="mui-table-view" style="margin-top: 10px;"> 
	        <li class="mui-table-view-cell mui-collapse">
	            <a class="mui-navigate-right" href="#">本月收益</a>
	            <div class="mui-collapse-content">
	            	<div class="time">
	            		当前时间：<span><?php echo date('Y-m');?></span>
	            	</div> 
	                <div class="ys">
							<div class="ys1" style="">
								<h6>应收</h6>
								<span><?php echo $Paymentmethod[0];?></span>
							</div>
							<div class="ys2" style="">
								<h6>折扣</h6>
								<span><?php foreach($months as $month){
									$reality_totals = $month['reality_total'];
									}
									foreach ($refunds as $refund) {
										$pay_amount = $refund['pay_amount'];
									}
								echo $reality_totals-$Paymentmethod[0]+$pay_amount;
									?></span>
							</div>
							<div class="ys3" style="margin-left: 220px;padding-top: 1px;margin-top: 30px;">
								<h6>人均</h6>
								<span><?php foreach($months as $month){
									if(!empty($month['number'])){
										$numbers = $month['number'];}}
									if(!empty($numbers)){
										echo round($Paymentmethod[0]/$numbers,2);}else{
											echo "0";
										}
										?></span>
									
							</div>
							<div class="ys5" style="">
								<h6>订单数</h6>
								<span><?php foreach($months as $month){
									if(!empty($month['counts'])){
										echo $month['counts'];
									}else{
										echo '0';
									}
									}?></span>
							</div>
							<div class="ren">
								<h6>总客人</h6>
								<span><?php if(!empty($numbers)){echo $numbers;}else{echo "0";}?></span>
							</div>
						</div>
		            </div>
		        </li>
	    	</ul>
	    	<ul class="mui-table-view" style="margin-top: 10px;"> 
	        <li class="mui-table-view-cell mui-collapse">
	            <a class="mui-navigate-right" href="#">今日活跃会员<span>
	            	<?php 
	            if(!empty($Member)){
	            	$arrays = array();
	            	foreach($Member as $paytype){
	            		 array_push($arrays,$paytype['paytype_id']);
	            	}
	            	$array = array_unique($arrays);
	            	 echo count($array);
	            }else{
	            	echo '0';
	            }
	            ?>
	            </span>/人</a>

	            <div class="mui-collapse-content"> 
	                <div class="ys" style="">
					<div class="ys1" style="">
						<h6>今日新增会员</h6>
						<span class="span"><?php echo count($card);?></span>人
					</div>
					<div class="ys2">
						<h6>今日老会员</h6>
						<span class="span"><?php if(!empty($Member)){ echo count($array);}else{ echo '0';}?></span>人
					</div>
					<div class="ys4">
						<h6>今日领卡数</h6>
						<span class="span"><?php echo count($card);?></span>人
					</div>
					<div class="ys5">
						<h6>今日充值金额</h6>
						<span class="span"><?php foreach($Recharges as $Recharge){if(!empty($Recharge['reality_money'])){echo round($Recharge['reality_money']);}else{ echo '0';}}?></span>元
					</div>
					<div class="ren">
						<h6>今日充值次数</h6>
						<span class="span"><?php foreach($Recharges as $Recharge){if(!empty($Recharge['count'])){echo $Recharge['count'];}else{ echo '0';}}?></span>次
					</div>
					<div style="float: none;"></div>
					<div class="ys7">
								<a>查看会员信息汇总</a>
							</div>
						</div>
		            </div>
		        </li>
	    	</ul>
	<!--报表中心-->
	<div class="bb">
		<ul class="mui-table-view">
			<li class="mui-table-view-cell mui-media">
				<div class="mui-media-body">
					<div class="bb1">
						<h4>报表中心</h4>
					</div>
					<div class="bb2">
						<a name="yysj" href="<?php echo $this->createUrl('appReport/yysj',array('companyId'=>$this->companyId));?>">营业数据</a>
					</div>
					<div class="bb3">
						<a name='sdbb' href="<?php echo $this->createUrl('appReport/sdbb',array('companyId'=>$this->companyId));?>">时段报表</a>
					</div>
					<div class="bb2">
						<a name="zffs" href="<?php echo $this->createUrl('appReport/zffs',array('companyId'=>$this->companyId));?>">支付方式</a>
					</div>
					<div class="bb8">
						<a name="dpxs" href="<?php echo $this->createUrl('appReport/dpxs',array('companyId'=>$this->companyId));?>">单品销售</a>
					</div>
					<div class="bb2">
						<a name="tcxs" href="<?php echo $this->createUrl('appReport/tcxs',array('companyId'=>$this->companyId));?>">套餐销售</a>
					</div>
					<div class="bb4">
						<a name="yclxh" href="<?php echo $this->createUrl('appReport/yclxh',array('companyId'=>$this->companyId));?>">原材料消耗</a>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<!--店铺管理-->
	<div class="bb">
		<ul class="mui-table-view">
			<li class="mui-table-view-cell mui-media">
				<div class="mui-media-body">
					<div class="bb1">
						<h4>店铺管理</h4>
					</div>
					<div class="bb2">
						<a name="operator" href="<?php echo $this->createUrl('appReport/operator',array('companyId'=>$this->companyId));?>">操作员管理</a>
					</div>
					<div class="bb6">
						<a>收银机设置</a>
					</div>
					<div style="float: none;"></div>
				</div>
			</li>
		</ul>
	</div>
	<!--基础设置-->
	<div class="bb">
		<ul class="mui-table-view">
			<li class="mui-table-view-cell mui-media">
				<div class="mui-media-body">
					<div class="bb1">
						<h4>基础设置</h4>
					</div>
					<div class="bb2">
						<a>菜品录入</a>
					</div>
					<div class="bb3">
						<a>套餐设置</a>
					</div>
					<div class="bb2">
						<a>口味设置</a>
					</div>
					<div class="bb5">
						<a>产品图片</a>
					</div>
					<div style="float: none;"></div>
				</div>
			</li>
		</ul>
	</div>
	<!--营销活动-->
	<div class="bb">
		<ul class="mui-table-view">
			<li class="mui-table-view-cell mui-media">
				<div class="mui-media-body">
					<div class="bb1">
						<h4>营销活动</h4>
					</div>
					<div class="bb2">
						<a>折扣模板</a>
					</div>
					<div class="bb3">
						<a>普通优惠</a>
					</div>
					<div class="bb2">
						<a>满送优惠</a>
					</div>
					<div class="bb7">
						<a>满减优惠</a>
					</div>
					<div style="float: none;"></div>
				</div>
			</li>
		</ul>
	</div>
	<!--进销存管理-->
	<div class="bb">
		<ul class="mui-table-view">
			<li class="mui-table-view-cell mui-media">
				<a href="javascript:;">
					<div class="mui-media-body">
						<div class="bb1">
							<h4>供应链</h4>
						</div>
						<div class="bb2">
							<a>安全库存</a>
						</div>
						<div class="bb3">
							<a>预估额采购</a>
						</div>
						<div class="bb2">
							<a>实时库存</a>
						</div>
						<div class="bb8">
							<a>库存调整</a>
						</div>
						<div class="bb2">
							<a>单据审核</a>
						</div>
						<div class="bb8">
							<a>采购入库</a>
						</div>
						<div style="float: none;"></div>
						<div class="bb9">
							<a>供应链详情>>></a>
						</div>
					</div>
				</a>
			</li>
		</ul>
	</div>
	<!--外卖管理-->
	<div class="bb">
		<ul class="mui-table-view">
			<li class="mui-table-view-cell mui-media">
				<a href="javascript:;">
					<div class="mui-media-body">
						<div class="bb1">
							<h4>外卖管理</h4>
						</div>
						<div class="bb2">
							<a>美团外卖</a>
						</div>
						<div class="bb3">
							<a>饿了么外卖</a>
						</div>
						<div class="bb10">
							<a>设置</a>
						</div>
					</div>
				</a>
			</li>
		</ul>
	</div>
</div>
