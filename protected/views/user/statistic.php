<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('手机报表（实时数据）');
	$tc = 0;$ytc = 0; //堂吃
	$wm = 0;$ywm = 0; //外卖
	$xj = 0;$yxj = 0; //现金
	$wx = 0;$ywx = 0; //微信
	$zfb = 0;$yzfb = 0;//支付宝
	$ylk = 0;$yylk = 0;//银联卡
	$mt = 0;$ymt = 0; //美团
	$dz = 0;$ydz = 0; //大众支付
	foreach($orderTypeStatistic as $order){
		if($order['order_type']==0 || $order['order_type']==1){
			$tc += $order['total'];
		}else{
			$wm += $order['total'];
		}
	}
	foreach($yorderTypeStatistic as $yorder){
		if($yorder['order_type']==0 || $yorder['order_type']==1){
			$ytc += $yorder['total'];
		}else{
			$ywm += $yorder['total'];
		}
	}
	foreach($payTypeStatistic as $pay){
		if($pay['paytype']==0){
			$xj += $pay['total'];
		}elseif($pay['paytype']==1){
			$wx += $pay['total'];
		}elseif($pay['paytype']==2){
			$zfb += $pay['total'];
		}elseif($pay['paytype']==3){
			$ylk += $pay['total'];
		}
	}
	foreach($ypayTypeStatistic as $ypay){
		if($ypay['paytype']==0){
			$yxj += $ypay['total'];
		}elseif($ypay['paytype']==1){
			$ywx += $ypay['total'];
		}elseif($ypay['paytype']==2){
			$yzfb += $ypay['total'];
		}elseif($ypay['paytype']==3){
			$yylk += $ypay['total'];
		}
	}
?>

<link rel="stylesheet" href="<?php echo $baseUrl;?>/css/report/index_hd_v2.0.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $baseUrl;?>/css/report/mobilereport.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $baseUrl;?>/css/report/mobilecommon.css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo $baseUrl;?>/js/report/mobilejquery.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/report/mobilejquery.touchSlider.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/report/mobilestyle.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/report/mobilejquery.circliful.min.js" ></script>		
<script type="text/javascript">
    $(function() {
        $('#myStat1').circliful();
        $('#myStat2').circliful();
    });
</script>
<style type="text/css">
    .circliful {
        position: relative; 
    }

    .circle-text, .circle-info, .circle-text-half, .circle-info-half {
        width: 100%;
        position: absolute;
        text-align: center;
        display: inline-block;
    }

    .circle-info, .circle-info-half {
        color: #999;
    }

    .circliful .fa {
        margin: -10px 3px 0 3px;
        position: relative;
        bottom: 4px;
    }
</style>
<body>
		<div class="bodydiv">
			<header class="dd_hd"  id="hometop">
            <div class="hd_comm">
				<div class="fl">
					<a href=""><span class="dd_logo"></span></a>
				</div>
				<div class="mid-a">
					<a>营业数据</a>
				</div>
				
            </div>
			</header> 
			<!--
			<div class="bdjr">
				<div class="flbaobiao l">
					<select id="text" class="btn selectl ">
					</select>
				</div>
				<div class="flbaobiao m">
					<span></span>
						<select id="shop" class="btn selectm ">
							<option class="optionr" value="1" >陶然居（丽水店）</option>
							<option class="optionr" value="2" >上海一号（静安店）</option>
							<option class="optionr" value="3" selected>有家川菜同心店</option>
						</select>
					
				</div>
				<div class="flbaobiao r">
					<select id="text" class="btn selectr ">
						<option class="optionr" value="1" >年</option>
						<option class="optionr" value="2" >月</option>
						<option class="optionr" value="3" selected>日</option>
					</select>
				</div>
				<div class="clear"></div>
			</div>
			-->
			<div style="box-shadow: 0px 0px 5px #000000;background-color:#FFFFFF;">
				<div class="today">
					<a>
						<div class="button">
						<?php echo date('m-d ', time());
						switch(date('w')){
							case 0: echo "(周日)";break;
							case 1: echo "(周一)";break;
							case 2: echo "(周二)";break;
							case 3: echo "(周三)";break;
							case 4: echo "(周四)";break;
							case 5: echo "(周五)";break;
							case 6: echo "(周六)";break;
							default:echo "...";break;
						}?></div>
					</a>
					
				</div>
				<div class="search">
					<div class="l">
					<div class="icon1">
						<a href=""><img src="<?php echo $baseUrl;?>/img/mall/left.png" /></a>
					</div>	
					</div>
					<div class="m">
					<a>
						<div class="button">
						<?php echo date('m-d ', strtotime('-1 day'));
						switch(date('w', strtotime('-1 day'))){
							case 0: echo "(周日)";break;
							case 1: echo "(周一)";break;
							case 2: echo "(周二)";break;
							case 3: echo "(周三)";break;
							case 4: echo "(周四)";break;
							case 5: echo "(周五)";break;
							case 6: echo "(周六)";break;
							default:echo "...";break;
						}?></div>
					</a>
					</div>
					<div class="r">
					<div class="icon1">
						<a href=""><img src="<?php echo $baseUrl;?>/img/mall/right.png" /></a>
					</div>	
					</div>
					<div class="clear"></div>
					
				</div>
				<div class="classification">
					
					<div class="div1">
						<div class="div2">
							
							<div class="cbox">
								<div class="zhanbitu1">占比图</div>
								<div class="zhanbitu2">
									<div id="myStat1" data-dimension="100" data-text="<?php echo $tc+$wm ? number_format($tc/($tc+$wm)*100,2):0?>%" data-info="New Clients" data-width="18" data-fontsize="16" data-percent="<?php echo $tc+$wm ? number_format($tc/($tc+$wm)*100,2):0?>" data-fgcolor="#F78644" data-bgcolor="#ddd" data-fill="#eee"></div>
								</div>
								<div class="clear"></div>
							</div>
							<div class="cbox">
								<div class="zhanbijiage">
									<div style="background-color:#F78644;" class="divl"></div>
									<div class="divm"><a>堂食</a></div>
									<div class="divprice">元</div>	
									<div class="divr"><a><?php echo number_format($tc,2);?></a></div>
									<div class="clear"></div>									
								</div>	
								<div class="zhanbijiage">
									<div style="background-color:#ddd;" class="divl"></div>
									<div class="divm"><a>外卖</a></div>
									<div class="divprice">元</div>	
									<div class="divr"><a><?php echo number_format($wm,2);?></a></div><?php echo number_format($tc + $wm,2);?>
									<div class="clear"></div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div >
						<div class="div2">
							
							<div class="cbox">
								<div class="zhanbitu1">   </div>
								<div class="zhanbitu2">
								<div id="myStat2" data-dimension="100" data-text="<?php echo $ytc+$ywm ?number_format($ytc/($ytc+$ywm)*100,2):0;?>%" data-info="New Clients" data-width="18" data-fontsize="16" data-percent="<?php echo $ytc+$ywm ? number_format($ytc/($ytc+$ywm)*100,2):0;?>" data-fgcolor="#48D148" data-bgcolor="#ddd" data-fill="#eee"></div>
								</div>
								<div class="clear"></div>
							</div>
							<div class="cbox">
								
								<div class="zhanbijiage">
									<div style="background-color:#48D148;" class="divl"></div>
									<div class="divm"><a>堂食</a></div>
									<div class="divprice">元</div>	
									<div class="divr"><a><?php echo number_format($ytc,2);?></a></div>
									<div class="clear"></div>									
								</div>	
								<div class="zhanbijiage">
									<div style="background-color:#ddd;" class="divl"></div>
									<div class="divm"><a>外卖</a></div>
									<div class="divprice">元</div>	
									<div class="divr"><a><?php echo number_format($ywm,2);?></a></div><?php echo number_format($ytc + $ywm,2);?>
									<div class="clear"></div>
								</div>
							
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="clear"></div>
					
				</div>
				<div class="clear"></div>
			</div>
			<div class="acty1">
				
				<div class="diva">
					<div class="tup1">
						<div>
							<div class="typename">现金支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($xj/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($xj,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($xj/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%;background-color:red;"></div>
							</div>							
						</div>
					</div>
					<div class="tup1" >
						<div>
							<div class="typename">微信支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($wx/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($wx,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($wx/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%;background-color:yellow;"></div>
							</div>							
						</div>
					</div>
					<div class="tup1" >
						<div>
							<div class="typename">支付宝支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($zfb/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($zfb,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($zfb/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%;background-color:pink;"></div>
							</div>							
						</div>
					</div>
					<div class="tup1" >
						<div>
							<div class="typename">银联卡支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($ylk/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($ylk,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($ylk/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%;background-color:green;"></div>
							</div>							
						</div>
					</div>
					<div class="tup1">
						<div>
							<div class="typename">美团支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($mt/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($mt,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($mt/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%;background-color:pink;"></div>
							</div>							
						</div>
					</div>
					<div class="tup1" >
						<div>
							<div class="typename">大众支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($dz/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($dz,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $xj+$wx+$zfb+$ylk+$mt+$dz ? number_format($dz/($xj+$wx+$zfb+$ylk+$mt+$dz)*100,2):0;?>%;background-color:blue;"></div>
							</div>							
						</div>
					</div>
					
				</div>
				<div class="diva">
					<div class="tup2">
						<div>
							<div class="typename">现金支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($yxj/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($yxj,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($yxj/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%;background-color:red;"></div>
							</div>							
						</div>
					</div>
					<div class="tup2" >
						<div>
							<div class="typename">微信支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($ywx/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($ywx,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($ywx/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%;background-color:yellow;"></div>
							</div>							
						</div>
					</div>
					<div class="tup2" >
						<div>
							<div class="typename">支付宝支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($yzfb/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($yzfb,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($yzfb/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%;background-color:pink;"></div>
							</div>							
						</div>
					</div>
					<div class="tup2" >
						<div>
							<div class="typename">银联卡支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($yylk/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($yylk,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($yylk/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%;background-color:green;"></div>
							</div>							
						</div>
					</div>
					<div class="tup2">
						<div>
							<div class="typename">美团支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($ymt/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($ymt,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($ymt/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%;background-color:pink;"></div>
							</div>							
						</div>
					</div>
					<div class="tup2" >
						<div>
							<div class="typename">大众支付:</div>
							<div class="typename">
								<div class="zhanbi"><a ><?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($ydz/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%</a></div>
								<div class="danwei">元</div>
								<div class="jiage"><?php echo number_format($ydz,2)?></div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:<?php echo $yxj+$ywx+$yzfb+$yylk+$ymt+$ydz ? number_format($ydz/($yxj+$ywx+$yzfb+$yylk+$ymt+$ydz)*100,2):0;?>%;background-color:blue;"></div>
							</div>							
						</div>
					</div>
					
				</div>
				<div class="clear"></div>
			</div>
			<div style="width:100%;height:2px;margin-top:4px;"></div>
		</div>

	</body>


  
