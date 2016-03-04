<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('手机报表（实时数据）');
?>

<link rel="stylesheet" href="<?php echo $baseUrl;?>/css/report/index_hd_v2.0.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $baseUrl;?>/css/report/mobilereport.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $baseUrl;?>/css/report/mobilecommon.css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo $baseUrl;?>/js/report/mobilejquery.touchSlider.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/report/mobilestyle.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/report/mobilejquery.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/report/mobilejquery.circliful.min.js" ></script>		
<script type="text/javascript">
    $(function() {
        $('#myStat1').circliful();
        $('#myStat2').circliful();
        $('#myStat3').circliful();
        $('#myStat4').circliful();
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
						<a href=""><img src="images/left.png" /></a>
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
						<a href=""><img src="images/right.png" /></a>
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
									<div id="myStat1" data-dimension="100" data-text="58%" data-info="New Clients" data-width="18" data-fontsize="16" data-percent="58" data-fgcolor="#F78644" data-bgcolor="#ddd" data-fill="#eee"></div>
								</div>
								<div class="clear"></div>
							</div>
							<div class="cbox">
								<div class="zhanbijiage">
									<div style="background-color:#F78644;" class="divl"></div>
									<div class="divm"><a>堂食</a></div>
									<div class="divprice">元</div>	
									<div class="divr"><a>11243.12</a></div>
									<div class="clear"></div>									
								</div>	
								<div class="zhanbijiage">
									<div style="background-color:#ddd;" class="divl"></div>
									<div class="divm"><a>外卖</a></div>
									<div class="divprice">元</div>	
									<div class="divr"><a>8141.57</a></div>19384.69
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
								<div id="myStat2" data-dimension="100" data-text="65.67%" data-info="New Clients" data-width="18" data-fontsize="16" data-percent="65.67" data-fgcolor="#48D148" data-bgcolor="#ddd" data-fill="#eee"></div>
								</div>
								<div class="clear"></div>
							</div>
							<div class="cbox">
								
								<div class="zhanbijiage">
									<div style="background-color:#48D148;" class="divl"></div>
									<div class="divm"><a>堂食</a></div>
									<div class="divprice">元</div>	
									<div class="divr"><a>13418.43</a></div>
									<div class="clear"></div>									
								</div>	
								<div class="zhanbijiage">
									<div style="background-color:#ddd;" class="divl"></div>
									<div class="divm"><a>外卖</a></div>
									<div class="divprice">元</div>	
									<div class="divr"><a>7014.69</a></div>20433.12
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
								<div class="zhanbi"><a >36.27%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">7030.80</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:36.27%;background-color:red;"></div>
							</div>							
						</div>
					</div>
					<div class="tup1" >
						<div>
							<div class="typename">银联卡支付:</div>
							<div class="typename">
								<div class="zhanbi"><a >26%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">5040.05</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:26%;background-color:green;"></div>
							</div>							
						</div>
					</div>
					<div class="tup1" >
						<div>
							<div class="typename">支付宝支付:</div>
							<div class="typename">
								<div class="zhanbi"><a >6.42%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">1244.50</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:6.42%;background-color:pink;"></div>
							</div>							
						</div>
					</div>
					<div class="tup1" >
						<div>
							<div class="typename">微信支付:</div>
							<div class="typename">
								<div class="zhanbi"><a >5.46%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">1058.40</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:5.46%;background-color:yellow;"></div>
							</div>							
						</div>
					</div>
					
					<div class="tup1">
						<div>
							<div class="typename">美团支付:</div>
							<div class="typename">
								<div class="zhanbi"><a >13.37%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">2591.73</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:13.37%;background-color:pink;"></div>
							</div>							
						</div>
					</div>
					<div class="tup1" >
						<div>
							<div class="typename">大众支付:</div>
							<div class="typename">
								<div class="zhanbi"><a >12.48%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">2419.21</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:12.48%;background-color:blue;"></div>
							</div>							
						</div>
					</div>
					
				</div>
				<div class="diva">
					<div class="tup2">
						<div>
							<div class="typename">现金支付:</div>
							<div class="typename">
								<div class="zhanbi"><a >30%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">6129.00</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:30%;background-color:pink;"></div>
							</div>							
						</div>
					</div>
					<div class="tup2" >
						<div>
							<div class="typename">银联卡支付:</div>
							<div class="typename">
								<div class="zhanbi"><a >25%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">5109.22</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:25%;background-color:red;"></div>
							</div>							
						</div>
					</div>
					<div class="tup2" >
						<div>
							<div class="typename">美团支付:</div>
							<div class="typename">
								<div class="zhanbi"><a >9.57%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">1955.45</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:9.57%;background-color:blue;"></div>
							</div>							
						</div>
					</div>
					<div class="tup2" >
						<div>
							<div class="typename">支付宝支付:</div>
							<div class="typename">
								<div class="zhanbi"><a >8.86%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">1810.37</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:8.86%;background-color:pink;"></div>
							</div>							
						</div>
					</div>
					
					<div class="tup2">
						<div>
							<div class="typename">大众支付:</div>
							<div class="typename">
								<div class="zhanbi"><a>12.25%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">2503.06</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:12.25%;background-color:pink;"></div>
							</div>							
						</div>
					</div>
					<div class="tup2" >
						<div>
							<div class="typename">微信支付:</div>
							<div class="typename">
								<div class="zhanbi"><a >14.32%</a></div>
								<div class="danwei">元</div>
								<div class="jiage">2926.02</div>
								<div class="clear"></div>
							</div>
							<div class="caisetiao">
								<div style="width:14.32%;background-color:pink;"></div>
							</div>							
						</div>
					</div>
					
				</div>
				<div class="clear"></div>
			</div>
			<div style="width:100%;height:2px;margin-top:4px;"></div>
			<!--<div class="btmn">精彩推荐</div>
			<div class="selling">
				<div class="diva">
					<div class="tup"></div>
					<div class="cpxx">
						<p class="p1">产品名称 (科技园)<span>详情</span></p>
						<P class="p2">[活动]活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息</P>
						<p class="p3">
							<span class="span1">活动</span>
							<span class="span2">25452人浏览</span>
						</p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="diva">
					<div class="tup"></div>
					<div class="cpxx">
						<p class="p1">产品名称<span>详情</span></p>
						<P class="p2">[促销]活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息</P>
						<p class="p3">
							<span class="span1">促销</span>
							<span class="span2">25452人浏览</span>
						</p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="diva">
					<div class="tup"></div>
					<div class="cpxx">
						<p class="p1">产品名称<span>详情</span></p>
						<P class="p2">[科技园 科技南十路]活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息活动详询信息</P>
						<p class="p3">
							¥:50.00
							<span class="span2">25452人浏览</span>
						</p>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="btmn">品牌专区</div>
			<div class="ppzq">
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="logo1">
					<img alt="尺寸：120X100" />
				</div>
				<div class="clear"></div>
			</div>-->
			
			
		</div>

	</body>


  
