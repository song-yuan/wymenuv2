<style>
		span.tab{
			color: black;
			border-right:1px dashed white;
			margin-right:10px;
			padding-right:10px;
			display:inline-block;
		}
        span.tab-active{
			color:white;
		}
		.ku-item{
			width:100px;
			height:100px;
			margin-right:20px;
			margin-top:20px;
			margin-left:20px;
			border-radius:5px !important;
		/*	border:2px solid black;
			box-shadow: 5px 5px 5px #888888;*/
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
		.cf-black{
			color: #000 !important;

		}
/*        .portlet-body a{
            display: inline-block;
        	height: 80px;
        	border: 1px solid white;
        }*/
	</style>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

	<!-- BEGIN PAGE CONTENT-->
	<?php if($type==0):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>''))));?>
	<?php elseif($type==1):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','库存数据'),'url'=>''))));?>
	<?php endif;?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
			      	<div class="portlet-title">
					<div class="caption"><em class=" fa <?php if($type==0){echo '';}else{echo 'cf-black';}?> fa-shopping-cart">&nbsp</em><a href="<?php echo $this->createUrl('statements/list',array('companyId'=>$this->companyId,'type'=>0));?>"><span class="tab <?php if($type==0){ echo 'tab-active';}?>"><?php echo yii::t('app','营业数据');?></span></a></div>
					<?php if(!in_array(Yii::app()->user->role, array(8,10))):?>
					<div class="caption"><em class=" fa <?php if($type==1){echo '';}else{echo 'cf-black';}?> fa-truck">&nbsp</em><a href="<?php echo $this->createUrl('statementstock/list',array('companyId'=>$this->companyId,'type'=>1));?>"><span class="tab <?php if($type==1){ echo 'tab-active';}?>" ><?php echo yii::t('app','进销存数据');?></span></a></div>
					<div class="caption"><em class=" fa <?php if($type==2){echo '';}else{echo 'cf-black';}?> fa-group">&nbsp</em><a href="<?php echo $this->createUrl('statementmember/list',array('companyId'=>$this->companyId,'type'=>2));?>"><span class="tab <?php if($type==2){ echo 'tab-active';}?>"><?php echo yii::t('app','会员数据');?></span></a></div>
					<div class="caption"><em class=" fa <?php if($type==3){echo '';}else{echo 'cf-black';}?> fa-warning ">&nbsp</em><a href="<?php echo $this->createUrl('statementmember/list',array('companyId'=>$this->companyId,'type'=>3));?>"><span class="tab <?php if($type==3){ echo 'tab-active';}?>"><?php echo yii::t('app','清除数据');?></span></a></div>
					<?php endif;?>
				</div>
				<div class="portlet-body clearfix" >
        		<?php if($type==0):?>
        			<?php if(Yii::app()->user->role == 10):?>
        			<div class="panel_body row">
                        <p>支付统计报表</p>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/rijieReport',array('companyId' => $this->companyId,'text'=>'3','userid'=>'0','page'=>1));?>">
                                <div class="list_big">日结统计(优化)</div>
                                <div class="list_small">查询门店日结详情数据，日结完成后方可显示数据</div>
                            </a> 
                        </div>
                    </div>
                    <div class="panel_body row">
                        <p>菜品明细报表</p>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/productSalseReport',array('companyId' => $this->companyId,'text'=>'3','setid'=>'1','ordertype'=>'-1'));?>">
                                <div class="list_big">单品销售统计</div>
                                <div class="list_small">查询不同时间段的单品以及套餐内单品的销售数据</div>
                            </a> 
                        </div>
                    </div>
                    <?php elseif (Yii::app()->user->role == 8):?>
                    <div class= "panel_body row">
                        <p>营销活动统计报表</p>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/cuponReport',array('companyId' => $this->companyId));?>">
                                <div class="list_big">代金券发放明细</div>
                                <div class="list_small">查询所有发出的代金券情况</div>
                            </a> 
                        </div>
        				<div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/cuponReport',array('companyId' => $this->companyId));?>">
                                <div class="list_big">代金券汇总</div>
                                <div class="list_small">查询所有发出的代金券使用情况</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/cuponReportDetail',array('companyId' => $this->companyId));?>">
                                <div class="list_big">代金券明细</div>
                                <div class="list_small">查询某张代金券按店铺统计使用情况</div>
                            </a> 
                        </div>
                    </div>
        			<?php else:?>
                    <div class="panel_body row">
                        <p>基础营业报表</p>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/incomeReport',array('companyId' => $this->companyId,'text'=>'3','setid'=>'1'));?>">
                                <div class="list_big"> 单品分类销售详情</div>
                                <div class="list_small">查询单品分类的销量、实收款和退款数据等</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/businessdataReport',array('companyId' => $this->companyId,'text'=>'3'));?>">
                                <div class="list_big"> 营业数据</div>
                                <div class="list_small">按年月日查询营业额的基础信息等</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/orderdetail',array('companyId' => $this->companyId,'text'=>'3'));?>">
                                <div class="list_big"> 账单详情</div>
                                <div class="list_small">查询所有订单的账单明细，不作统计</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/timedataReport',array('companyId' => $this->companyId,'text'=>'3'));?>">
                                <div class="list_big"> 时段报表</div>
                                <div class="list_small">按所要查询的时间段查看销售金额及菜品单数</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/memberconsume',array('companyId' => $this->companyId));?>">
                                <div class="list_big">会员卡消费</div>
                                <div class="list_small">查询所有会员卡的消费记录</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/channelsproportion',array('companyId' => $this->companyId,'text'=>'3'));?>">
                                <div class="list_big">渠道占比</div>
                                <div class="list_small">查询所有账单渠道在总账单渠道中占的百分比信息等</div>
                            </a> 
                        </div>
                    </div>
                    
                    <div class= "panel_body row">
                        <p>菜品明细报表</p>
        			     <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/timeproductReport',array('companyId' => $this->companyId,'text'=>'3','setid'=>'1','ordertype'=>'-1'));?>">
                                <div class="list_big">时段单品报表</div>
                                <div class="list_small">查询不同店铺、不同时间段、不同类别单品的销售数据</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/productSalseReport',array('companyId' => $this->companyId,'text'=>'3','setid'=>'1','ordertype'=>'-1'));?>">
                                <div class="list_big">单品销售统计</div>
                                <div class="list_small">查询不同时间段的单品以及套餐内单品的销售数据</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/productdetailReport',array('companyId' => $this->companyId,'text'=>'3','setid'=>'1','ordertype'=>'-1'));?>">
                                <div class="list_big">单品销售详情</div>
                                <div class="list_small">查询单品以及套餐内单品的销售时间</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/productsetSalseReport',array('companyId' => $this->companyId,'text'=>'3','setid'=>'1','ordertype'=>'-1'));?>">
                                <div class="list_big">套餐销售</div>
                                <div class="list_small">查询不同时间段的套餐销售数据</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/retreatdetailReport',array('companyId' => $this->companyId,'text'=>'3'));?>">
                                <div class="list_big">退菜明细</div>
                                <div class="list_small">查询所退菜品的信息记录</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/retreatreasonReport',array('companyId' => $this->companyId,'text'=>'3'));?>">
                                <div class="list_big">退菜原因</div>
                                <div class="list_small">记录所退菜品的原因明细</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/orderproductsReport',array('companyId' => $this->companyId,'ordertype'=>'-1'));?>">
                                <div class="list_big">单品明细报表</div>
                                <div class="list_small">查询单品的名称、账单号以及销售金额等</div>
                            </a> 
                        </div>
                    </div>
                    <div class= "panel_body row">
                        <p>支付统计报表</p>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/rijieReport',array('companyId' => $this->companyId,'text'=>'3','userid'=>'0'));?>">
                                <div class="list_big">日结统计</div>
                                <div class="list_small">查询门店日结详情数据，日结完成后方可显示数据</div>
                            </a> 
                        </div>
                        <?php if(Yii::app()->user->role <=11):?>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/paymentReport',array('companyId' => $this->companyId,'text'=>'3','userid'=>'0'));?>">
                                <div class="list_big">支付方式(员工业绩)</div>
                                <div class="list_small">查询不同服务员的销售数据</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/comPayYueReport',array('companyId' => $this->companyId));?>">
                                <div class="list_big">支付方式(微信点餐)</div>
                                <div class="list_small">查询门店账单总营业额和总单数及微信端的账单数据和金额数据</div>
                            </a> 
                        </div>
                        <?php endif;if(Yii::app()->user->role <6):?>
        				<div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/comPaymentReport',array('companyId' => $this->companyId,'text'=>'3'));?>">
                                <div class="list_big">支付方式</div>
                                <div class="list_small">查看不同门店的所有不同支付方式的账单数据以及总单数</div>
                            </a> 
                        </div>
                        <?php endif;?>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/orderpaytype',array('companyId' => $this->companyId,'text'=>'3','paymentid'=>'0','paytype'=>'-1'));?>">
                                <div class="list_big">账单支付方式(列表)</div>
                                <div class="list_small">查询账单的账单号、下单时间、金额和支付方式</div>
                            </a> 
                        </div>
                        <?php if(Yii::app()->user->role <6):?>
        				<div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/posfee',array('companyId' => $this->companyId));?>">
                                <div class="list_big">收款机续费报表</div>
                                <div class="list_small">查看一段时间内收款机续费的门店</div>
                            </a> 
                        </div>
                        <?php endif;?>
                    </div>
                    <div class= "panel_body row">
                        <p>营销活动统计报表</p>
        				<div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/cuponReport',array('companyId' => $this->companyId));?>">
                                <div class="list_big">代金券汇总</div>
                                <div class="list_small">查询所有发出的代金券使用情况</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/cuponReportDetail',array('companyId' => $this->companyId));?>">
                                <div class="list_big">代金券明细</div>
                                <div class="list_small">查询某张代金券按店铺统计使用情况</div>
                            </a> 
                        </div>
                    </div>
                   <div class="panel_body row">
                   		<p>成本</p>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('costs/costsReport',array('companyId' => $this->companyId,'time'=>date('Y-m-d',time())));?>">
                                <div class="list_big">成本管控</div>
                                <div class="list_small">查询所有支出</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('costs/costsMaterialReport',array('companyId' => $this->companyId,'time'=>date('Y-m-d',time())));?>">
                                <div class="list_big">原料成本</div>
                                <div class="list_small">按条件查询原料成本支出</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('costs/costsDayReport',array('companyId' => $this->companyId,'time'=>date('Y-m-d',time())));?>">
                                <div class="list_big">支出成本</div>
                                <div class="list_small">按日期查询支出成本</div>
                            </a> 
                        </div>
                    </div>
                    <div class= "panel_body row">
                        <p>其他</p>
        				<div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('statements/takeaway',array('companyId' => $this->companyId,'text'=>'3'));?>">
                                <div class="list_big">送餐员业绩</div>
                                <div class="list_small">查询外卖送餐员的送餐单数和营业额等</div>
                            </a> 
                        </div>
                        <?php if(Yii::app()->user->role < '1'):?>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('pos/index',array('companyId' => $this->companyId,'pos_type'=>'0','status'=>'0'));?>">
                                <div class="list_big">收银机统计</div>
                                <div class="list_small"></div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('poscount/used',array('companyId' => $this->companyId,'pos_type'=>'0'));?>">
                                <div class="list_big">收银机排序</div>
                                <div class="list_small">用于总部查询和记录门店收银有无结算的信息等</div>
                            </a> 
                        </div>
                        <?php endif;?>
                        <?php if(Yii::app()->user->role <= '5'):?>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                           <a href="<?php echo $this->createUrl('poscount/hqindex',array('companyId'=>$this->companyId));?>">
                                <div class="list_big">收银机排序</div>
                                <div class="list_small">用于总部查询和记录门店收银有无结算的信息等</div>
                            </a> 
                        </div>
                        <?php endif;?>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/diningNum',array('companyId' => $this->companyId,'text'=>'3'));?>">     
                                <div class="list_big">聚餐人数</div>
                                <div class="list_small">查询不同时间段的聚餐人数</div>           
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('statements/tableareaReport',array('companyId' => $this->companyId,'text'=>'3'));?>">     
                                <div class="list_big">台桌区域</div>
                                <div class="list_small">查询桌台的客流、单数、金额统计和占比等</div>
                            </a> 
                        </div>
                    </div> 
                    <?php endif;?> 
                <?php endif;?>
                </div> 
				
			</div>
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