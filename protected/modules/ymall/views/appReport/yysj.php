<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="../../../../css/mui.min.css" rel="stylesheet" />
		<script src="../../../../js/mui.min.js"></script>
		<script type="text/javascript">
			mui.init()
		</script>
		<style>
			body{
				margin: 0px;
				padding: 0px;
				background-color: #CCCCCC;
				width: 100%;
			}
			body div{
				background-color: #FFFFFF;
				line-height: 30px;
			}
			body .padding{
				padding-left: 20px;
			}
		</style>
	</head>

	<body>
		<div>
			<table cellpadding="0" cellspacing="0" width="100%" >
				<tr>
					<td class="padding" colspan="2"><h4><span><?php echo date('Y-m-d H:i:s',time());?></span></h4></td>
				</tr>
				<tr>
					<td  class="padding" class="padding" colspan="2"><h4>上海斗石</h4></td>
				</tr>
				<tr style="text-align: center;">
					<td colspan="2"><h4>营业数据</h4></td>
				</tr>
				<tr>
					<td class="padding">开始时间:</td>
					<td><span><?php echo date('Y-m-d',time());?></span> 00:00:00</td>
				</tr>
				<tr>
					<td class="padding">结束时间:</td>
					<td><span><?php echo date('Y-m-d',time());?></span> 23:59:59</td>
				</tr>
				<tr>
					<td class="padding" colspan="2"><h4>交易统计</h4></td>
				</tr>
				<tr>
					<td class="padding">交易数</td>
					<td><?php foreach($todayProfit as $Profit){
						if(!empty($Profit['counts'])){
							echo $Profit['counts'];
						}else{
							echo '0';
						}
						}?></td>
				</tr>
				<tr>
					<td class="padding">票单价</td>
					<td><?php foreach($todayProfit as $Profit){
							$reality_total = $Profit['reality_total'];
							$counts = $Profit['counts'];
						}
						$array = array();
						foreach ($Paymentmethod as $Pay) {
							
								array_push($array,$Pay['pay_amount']);	
						}
						$sum = array_sum($array);
						foreach($refunds as $refund){
							$pay_amount = $refund['pay_amount'];
						}
						if(!empty($counts)){
							echo round(($sum+$pay_amount)/$counts,2);
						}else{
							echo "0";
						}
						?>
						</td>
				</tr>
				<tr>
					<td class="padding">来客数</td>
					<td><?php foreach($todayProfit as $Profit){
						if(!empty($Profit['number'])){
							$number = $Profit['number'];
							echo $number;
						}else{
							echo '0';
						}
						}?></td>
				</tr>
				<tr>
					<td class="padding">客单价</td>
					<td><?php if(!empty($number)){echo round(($sum+$pay_amount)/$number,2);}else{echo "0";} ?></td>
				</tr>
				<tr>
					<td class="padding" colspan="2"><h4>营业数据</h4></td>
				</tr>
				<tr>
					<td class="padding">营业毛额</td>
					<td><?php echo $reality_total;?></td>
				</tr>
				<tr>
					<td class="padding">优惠/折扣</td>
					<td><?php if(!empty($sum)){
								echo round($reality_total-$sum+$pay_amount,2);
							   }else{
									echo "0";
							   }?>
					</td>
				</tr>
				<tr>
					<td class="padding">营业收入</td>
					<td><?php echo $sum;?></td>
				</tr>
				<tr>
					<td class="padding" colspan="2"><h4>收款统计</h4></td>
				</tr>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==0):?>
				<tr>
					<td class="padding">现金</td>
					<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
				</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==1):?>
				<tr>
					<td class="padding">微信支付</td>
					<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
				</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==2):?>
				<tr>
					<td class="padding">支付宝</td>
					<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
				</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay){
					if ($Pay['paytype']==3) {
						$juan = $Pay['pay_amount'];
						$juanci = $Pay['counts'];
						$id = $Pay['payment_method_id'];
					}
					}
					if(!empty($id)){
						$sql = "select name from nb_payment_method where lid=".$id;
					$names = Yii::app()->db->createCommand($sql)->queryRow();
					}
					?>
					<?php if(!empty($names)):?>
					<tr>
						<td class="padding"><?php echo $names['name'];?></td>
						<td><?php echo $juan."(".$juanci."次)";?></td>
					</tr>
					<?php endif;?>
					<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==4):?>
				<tr>
					<td class="padding">会员卡</td>
					<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
				</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==5):?>
				<tr>
					<td class="padding">银联</td>
					<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
				</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==10):?>
				<tr>
					<td class="padding">微信储值支付</td>
					<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
				</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==12):?>
				<tr>
					<td class="padding">微点单</td>
					<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
				</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==13):?>
						<tr>
							<td class="padding">微外卖</td>
							<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
						</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==14):?>
						<tr>
							<td class="padding">美团外卖</td>
							<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
						</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==15):?>
						<tr>
							<td class="padding">饿了么外卖</td>
							<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
						</tr>
					<?php endif;?>
				<?php endforeach;?>
				<tr>
					<td class="padding">总计</td>
					<td><?php echo $sum;?></td>
				</tr>
				<tr>
					<td class="padding" colspan="2"><h4>充值统计</h4></td>
				</tr>
				<tr>
					<td class="padding">传统会员</td>
					<td><?php foreach($Recharges as $Recharge){
							if(!empty($Recharge['reality_money'])){
								echo $Recharge['reality_money'];
							}else{
								echo '0.00';
							}
						}?></td>
				</tr>
				<tr>
					<td class="padding">传统会员赠送</td>
					<td><?php foreach($Recharges as $Recharge){
							if(!empty($Recharge['give_money'])){
								echo $Recharge['give_money'];
							}else{
								echo '0.00';
							}
						}?></td>
				</tr>
				<tr>
					<td class="padding">微信会员</td>
					<td><?php foreach($records as $record){
							if(!empty($record['recharge_money'])){
								echo $record['recharge_money'];
							}else{
								echo '0.00';
							}
						}?></td>
				</tr>
				<tr>
					<td class="padding">微信会员赠送</td>
					<td><?php foreach($records as $record){
							if(!empty($record['cashback_num'])){
								echo $record['cashback_num'];
							}else{
								echo '0.00';
							}
						}?></td>
				</tr>
				<tr>
					<td class="padding" colspan="2"><h4>外卖营收</h4></td>
				</tr>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==13):?>
						<tr>
							<td class="padding">微外卖</td>
							<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
						</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==14):?>
						<tr>
							<td class="padding">美团外卖</td>
							<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
						</tr>
					<?php endif;?>
				<?php endforeach;?>
				<?php foreach($Paymentmethod as $Pay):?>
					<?php if($Pay['paytype']==15):?>
						<tr>
							<td class="padding">饿了么外卖</td>
							<td><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></td>
						</tr>
					<?php endif;?>
				<?php endforeach;?>
				<tr>
					<td class="padding">外卖总计</td>
					<td><?php 
					$array = array();
					$counts = array();
					foreach($Paymentmethod as $Pay){
							if($Pay['paytype']==13 || $Pay['paytype']==14 || $Pay['paytype']==15){
								array_push($array,$Pay['pay_amount']);	
								array_push($counts,$Pay['counts']);
							}
						}
						$arraysum = array_sum($array);
						$countssum = array_sum($counts);
						echo $arraysum; 
						?>(<?php echo $countssum;?>次)</td>
				</tr>
				<tr>
					<td class="padding" colspan="2"><h4>其他</h4></td>
				</tr>
				<tr>
					<td class="padding">退款金额</td>
					<td><?php if(!empty($pay_amount)){
						echo $pay_amount;
						}else{
							echo "0";
							}?></td>
				</tr>
				<tr>
					<td class="padding">退款次数</td>
					<td><?php foreach($refunds as $refund){
						if(!empty($refund['count'])){
							echo $refund['count'];
						}else{
							echo '0';
						}
						}?></td>
				</tr>
				<tr style="text-align: center;">
					<td colspan="2"><button><a href="<?php echo $this->createUrl('shoujiduan/index',array('companyId'=>$this->companyId));?>#yysj">返回</a></button></td>
				</tr>
			</table>
		</div>
	</body>

</html>