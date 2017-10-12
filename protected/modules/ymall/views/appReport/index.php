<style>
	body{
		/*background-image: url(../../../../img/shouji/b1.jpg);
		background-repeat:no-repeat;
		background-attachment:fixed;*/
		background-color: #ffffff;
	}
	h4{
		padding: 10px;
		color: #000000;
	}
</style>
<!--导航栏-->
<div style="width: 100%;height: 45px;">
	<div style="float: right;padding-top:15px;margin-right: 20px;"><span><?php echo Helper::getCompanyName($this->companyId);?></span>　<span></span></div>
</div>
<!--今日营业收益-->
<div style="width: 100%;margin-top: 10px;">
	<div style=" display: inline-block;background-color: #00C598;width: 45%;margin-top: 10px;margin-left: 10px;text-align: center;line-height: 135px;padding-bottom: 10px;height: 135px;">
		<h4 style="color: #FFFFFF;">今日应收</h4>
		<h3 style="color: #FFFFFF;margin-top: 20px;"><?php foreach($todayProfit as $today){
			if(!empty($today['pay_amount'])){
				echo $today['pay_amount'];
			}else{
				echo '0';
			}
			}?></h3>
	</div>
	<div style=" display: inline-block;background-color: #11ACE5;width: 45%;margin-top: 10px;margin-left: 10px;text-align: center;padding-bottom: 10px;height: 135px;">
		<h4 style="color: #FFFFFF;">今日实收</h4>
		<h3 style="color: #FFFFFF;margin-top: 20px;"><?php foreach($todayProfit as $today){
			if(!empty($today['pay_amount'])){
				$pay_amount = $today['pay_amount'];
				echo $pay_amount;
			}else{
				echo '0';
			}
			}?></h3>
	</div>
	<div style=" display: inline-block;width: 45.5%;margin-top: 10px;margin-left: 10px;text-align: center;padding-bottom: 10px;border-right: 1px solid #999;height: 135px;">
		<h4 style="color: #999999;">总订单数</h4>
		<h3 style="margin-top: 20px;"><?php foreach($orders as $order){
			$reality_total = $order['reality_total'];
			if(!empty($order['counts'])){
				$counts = $order['counts'];
				echo $counts;
			}else{
				echo '0';
			}
			}?></h3>
	</div>
	<div style=" display: inline-block;width: 45%;margin-top: 10px;margin-left: 10px;text-align: center;padding-bottom: 10px;height: 135px;">
		<h4 style="color: #999999;">订单平均价</h4>
		<h3 style="margin-top: 20px;"><?php 
			if(!empty($counts)){
				echo round($pay_amount/$counts,2);
			}else{
				echo '0';
			}
			?></h3>
	</div>
	<div style="width: 91%;margin-left: 10px"><hr style="border: 1px solid #999;"></div>
	<div style=" display: inline-block;width: 45.5%;margin-top: 10px;margin-left: 10px;text-align: center;padding-bottom: 10px;border-right: 1px solid #999;height: 135px;">
		<h4 style="color: #999999;">总消费人数</h4>
		<h3 style="margin-top: 20px;"><?php foreach($orders as $order){
			if(!empty($order['number'])){
				$number = $order['number'];
				echo $number;
			}else{
				echo '0';
			}
			}?></h3>
	</div>
	<div style=" display: inline-block;width: 45%;margin-top: 10px;margin-left: 10px;text-align: center;padding-bottom: 10px;height: 135px;margin-bottom: 10px;">
		<h4 style="color: #999999;">人均消费</h4>
		<h3 style="margin-top: 20px;"><?php 
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
            	<div style="margin-top: 10px;margin-bottom: 30px;">
            		当前时间：<span><?php echo date('Y-m-d');?></span>
            	</div> 
                <div style="width: 100%;height: 200px;border-top: none;">
				<div style="border-right: 1px solid #6D6D72;width: 100px;float: left;">
					<h6>应收</h6>
					<span><?php if(!empty($pay_amount)){
						echo $pay_amount;
					}else{
						echo '0';
					}?></span>
				</div>
				<div style="float: left;margin-left: 10px;width: 100px;border-right: 1px solid #6D6D72;">
					<h6>折扣</h6>
					<span><?php 
			if(!empty($pay_amount)){
				echo $reality_total-$pay_amount;
			}else{
				echo '0';
			}
			?></span>
				</div>
				<div style="margin-left: 220px;padding-top: 1px;margin-top: 30px;">
					<h6>人均</h6>
					<span><?php if(!empty($number)){
				$round = round($pay_amount/$number,2);
				echo $round;
			}else{
				echo '0';
			}?></span>
				</div>
				<div style="border-right: 1px solid #6D6D72;width: 100px;float: left;margin-top: 30px;">
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
				<div style="float: left;margin-left: 10px;width: 100px;margin-top: 30px;">
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
				<div align="right" style="margin-top: 120px;">
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
            	<div style="margin-top: 10px;margin-bottom: 30px;">
            		当前时间：<span><?php echo date('Y-m');?></span>
            	</div> 
                <div style="width: 100%;border: 1px solid #FFFFFF;border-top: none;">
				<div style="border-right: 1px solid #6D6D72;width: 100px;float: left;">
					<h6>应收</h6>
					<span><?php echo $Paymentmethod[0];?></span>
				</div>
				<div style="float: left;margin-left: 10px;width: 100px;border-right: 1px solid #6D6D72;">
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
				<div style="margin-left: 220px;padding-top: 1px;margin-top: 30px;">
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
				<div style="border-right: 1px solid #6D6D72;width: 100px;float: left;margin-top: 30px;">
					<h6>订单数</h6>
					<span><?php foreach($months as $month){
						if(!empty($month['counts'])){
							echo $month['counts'];
						}else{
							echo '0';
						}
						}?></span>
				</div>
				<div style="float: left;margin-left: 10px;width: 100px;margin-top: 30px;">
					<h6>总客人</h6>
					<span><?php if(!empty($numbers)){echo $numbers;}else{echo "0";}?></span>
						</div>
					</div>
	            </div>
	        </li>
    	</ul>
    	<ul class="mui-table-view" style="margin-top: 10px;"> 
        <li class="mui-table-view-cell mui-collapse">
            <a class="mui-navigate-right" href="#">今日活跃会员<span style="margin-left: 30px;">
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
            </span>人</a>

            <div class="mui-collapse-content"> 
                <div style="width: 100%;height: 200px;border: 1px solid #FFFFFF;border-top: none;">
				<div style="border-right: 1px solid #6D6D72;width: 100px;float: left;line-height: 30px;">
					<h6>今日新增会员</h6>
					<span style="margin-left: 30px;"><?php echo count($card);?></span>人
				</div>
				<div style="float: left;margin-left: 10px;width: 100px;border-right: 1px solid #6D6D72;line-height: 30px;">
					<h6>今日老会员</h6>
					<span style="margin-left: 30px;"><?php if(!empty($Member)){ echo count($array);}else{ echo '0';}?></span>人
				</div>
				<div style="margin-left: 220px;padding-top: 1px;line-height: 30px;">
					<h6>今日领卡数</h6>
					<span style="margin-left: 30px;"><?php echo count($card);?></span>人
				</div>
				<div style="border-right: 1px solid #6D6D72;width: 100px;float: left;margin-top: 30px;line-height: 30px;">
					<h6>今日充值金额</h6>
					<span style="margin-left: 10px;"><?php foreach($Recharges as $Recharge){if(!empty($Recharge['reality_money'])){echo $Recharge['reality_money'];}else{ echo '0';}}?></span>元
				</div>
				<div style="float: left;margin-left: 10px;width: 100px;margin-top: 30px;line-height: 30px;">
					<h6>今日充值次数</h6>
					<span style="margin-left: 30px;"><?php foreach($Recharges as $Recharge){if(!empty($Recharge['count'])){echo $Recharge['count'];}else{ echo '0';}}?></span>次
				</div>
				<div style="float: none;"></div>
				<div align="right" style="margin-top: 110px;">
							<a>查看会员信息汇总>>></a>
						</div>
					</div>
	            </div>
	        </li>
    	</ul>
    	<!--报表中心-->
<div style="width: 100%;margin-top: 10px;">
	<div>
		<h4>报表中心</h4>
	</div>
	<div style="float: left;margin-top: 20px;margin-left: 30px;">
			<a name="yysj" href="<?php echo $this->createUrl('appReport/yysj',array('companyId'=>$this->companyId));?>">营业数据</a>
	</div>
	<div style="margin-top: 30px;margin-left: 170px;">
		<a name='sdbb' href="<?php echo $this->createUrl('appReport/sdbb',array('companyId'=>$this->companyId));?>">时段报表</a>
	</div>
	<div style="float: left;margin-top: 20px;margin-left: 30px;">
		<a name="zffs" href="<?php echo $this->createUrl('appReport/zffs',array('companyId'=>$this->companyId));?>">支付方式</a>
	</div>
	<div style="margin-top: 20px;margin-left: 170px;">
		<a name="dpxs" href="<?php echo $this->createUrl('appReport/dpxs',array('companyId'=>$this->companyId));?>">单品销售</a>
	</div>
	<div style="float: left;margin-top: 20px;margin-left: 30px;">
		<a name="tcxs" href="<?php echo $this->createUrl('appReport/tcxs',array('companyId'=>$this->companyId));?>">套餐销售</a>
	</div>
	<div style="margin-left: 170px;margin-top: 20px;padding-bottom: 20px;">
		<a name="yclxh" href="<?php echo $this->createUrl('appReport/yclxh',array('companyId'=>$this->companyId));?>">原材料消耗</a>
	</div>
</div>
<!--店铺管理-->
<div style="margin-top: 10px;width: 100%;height: 100px;">
	<div>
		<h4>店铺管理</h4>
	</div>
	<div style="margin-top: 20px;margin-left: 30px;float: left;">
		<a name="operator" href="<?php echo $this->createUrl('appReport/operator',array('companyId'=>$this->companyId));?>">操作员管理</a>
	</div>
	<div style="margin-top: 25px;margin-left: 170px;">
		<a>收银机设置</a>
	</div>
	<div style="float: none;"></div>
</div>
<!--基础设置-->
<div style="margin-top: 10px;width: 100%;height: 150px;">
	<div>
		<h4>基础设置</h4>
	</div>
	<div style="float: left;margin-left: 30px;margin-top: 20px;">
		<a>菜品录入</a>
	</div>
	<div style="margin-left: 170px;margin-top: 30px;">
		<a>套餐设置</a>
	</div>
	<div style="float: left;margin: 10px;margin-left: 30px;margin-top: 20px;">
		<a>口味设置</a>
	</div>
	<div style="margin: 10px;margin-left: 170px;margin-top: 20px;">
		<a>产品图片</a>
	</div>
	<div style="float: none;"></div>
</div>
<!--营销活动-->
<div style="width: 100%;margin-top: 10px;">
	<div>
		<h4>营销活动</h4>
	</div>
	<div style="float: left;margin-top: 20px;margin-left: 30px;">
		<a>折扣模板</a>
	</div>
	<div style="margin-top: 30px;margin-left: 170px;">
		<a>普通优惠</a>
	</div>
	<div style="float: left;margin-top: 20px;margin-left: 30px;">
		<a>满送优惠</a>
	</div>
	<div style="margin-top: 20px;margin-left: 170px;padding-bottom: 30px;">
		<a>满减优惠</a>
	</div>
	<div style="float: none;"></div>
</div>
<!--进销存管理-->
<div style="width: 100%;margin-top: 10px;">
	<div>
		<h4>供应链</h4>
	</div>
	<div style="float: left;margin-left: 30px;margin-top: 20px;">
		<a>安全库存</a>
	</div>
	<div style="margin-left: 170px;margin-top: 30px;">
		<a>预估额采购</a>
	</div>
	<div style="float: left;margin-left: 30px;margin-top: 20px;">
		<a>实时库存</a>
	</div>
	<div style="margin-left: 170px;margin-top: 20px;">
		<a>库存调整</a>
	</div>
	<div style="float: left;margin-left: 30px;margin-top: 20px;">
		<a>单据审核</a>
	</div>
	<div style="margin-left: 170px;margin-top: 20px;">
		<a>采购入库</a>
	</div>
	<div style="float: none;"></div>
	<div style="margin-top:20px;margin-left: 210px;padding-bottom: 20px;">
		<a>供应链详情>>></a>
	</div>
</div>
<!--外卖管理-->
<div style="margin-top: 10px;width: 100%;">
	<div>
		<h4>外卖管理</h4>
	</div>
	<div style="float: left;margin-top: 20px;margin-left: 30px;">
		<a>美团外卖</a>
	</div>
	<div style="margin-top: 30px;margin-left: 170px;">
		<a>饿了么外卖</a>
	</div>
	<div style="margin-top: 20px;margin-left: 30px;padding-bottom: 20px;">
		<a>设置</a>
	</div>
</div>