
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
		.ku-purple{
			
		}
		.ku-grey{
			/*background-color:#DB7093;*/
		}
		.ku-item.dpgl{
			background-image:url(../../../../../../img/waiter/icon-dpjcsz.png);
			background-position: 15px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.cgzh{
			background-image:url(../../../../../../img/waiter/icon-kcsj.png);
			background-position: 15px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.cszh{
			background-image:url(../../../../../../img/waiter/icon-kcsj.png);
			background-position: -135px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.csls{
			background-image:url(../../../../../../img/waiter/icon-kcsj.png);
			background-position: -285px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.kczh{
			background-image:url(../../../../../../img/waiter/icon-kcsj.png);
			background-position: -440px 15px;
    		background-repeat: no-repeat;
		}	
		.ku-item.sdbb{
			background-image:url(../../../../../../img/waiter/icon-sdbb.png);
			background-position: 13px 15px;
    		background-repeat: no-repeat;
			background-size: 80%;
		}
		.ku-item.bkjl{
			background-image:url(../../../../../../img/waiter/icon-bkjl.png);
			background-position: 12px 13px;
    		background-repeat: no-repeat;
			background-size: 80%;
		}	
		.ku-item.sskc{
			background-image:url(../../../../../../img/waiter/icon-kcsj.png);
			background-position: -590px 15px;
    		background-repeat: no-repeat;
		}	
			
		.ku-item.sktj{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: 8px 16px;
    		background-repeat: no-repeat;
		}
		.ku-item.yysr{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -140px 16px;
    		background-repeat: no-repeat;
		}		
		.ku-item.czjl{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -295px 16px;
    		background-repeat: no-repeat;
		}		
		.ku-item.cpxs{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -443px 16px;
    		background-repeat: no-repeat;
		}
		.ku-item.tcxs{
			background-image:url(../../../../../../img/waiter/icon-tcxs.png);
			background-position: 15px 16px;
			background-size: 70%;
    		background-repeat: no-repeat;
		}		
		.ku-item.zdxq{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -600px 16px;
    		background-repeat: no-repeat;
		}		
		.ku-item.yysj{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -750px 16px;
    		background-repeat: no-repeat;
		}		
		.ku-item.qdzb{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: 14px -155px;
    		background-repeat: no-repeat;
		}		
		.ku-item.tzqy{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -140px -155px;
    		background-repeat: no-repeat;
		}		
		.ku-item.tcmx{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -290px -155px;
    		background-repeat: no-repeat;
		}		
		.ku-item.tcyy{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -443px -155px;
    		background-repeat: no-repeat;
		}		
		.ku-item.djqsy{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -600px -155px;
    		background-repeat: no-repeat;
		}		
		.ku-item.jcrs{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -750px -155px;
    		background-repeat: no-repeat;
		}		
		.ku-item.ygyye{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: 14px -335px;
    		background-repeat: no-repeat;
		}	
		.ku-item.scyyj{
			background-image:url(../../../../../../img/waiter/icon-scyyj.png);
			background-position: 14px 10px;
			background-size: 80%;
    		background-repeat: no-repeat;
		}
		.ku-item.zdzffs{
			background-image:url(../../../../../../img/waiter/icon-zdzffs.png);
			background-position: 14px 20px;
			background-size: 70%;
    		background-repeat: no-repeat;
		}		
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
		.cf-black{
			color: #000 !important;
			
		}		
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
					<div class="caption"><em class=" fa <?php if($type==2){echo '';}else{echo 'cf-black';}?> fa-shopping-cart">&nbsp</em><a href="<?php echo $this->createUrl('statementmember/list',array('companyId'=>$this->companyId,'type'=>2));?>"><span class="tab <?php if($type==2){ echo 'tab-active';}?>"><?php echo yii::t('app','会员数据');?></span></a></div>
					<div class="caption"><em class=" fa <?php if($type==3){echo '';}else{echo 'cf-black';}?> fa-shopping-cart">&nbsp</em><a href="<?php echo $this->createUrl('statementmember/list',array('companyId'=>$this->companyId,'type'=>3));?>"><span class="tab <?php if($type==3){ echo 'tab-active';}?>"><?php echo yii::t('app','清除数据');?></span></a></div>
					
					<!--<em class=" fa <?php if($type==1){echo '';}else{echo 'cf-black';}?> fa-truck">&nbsp</em><a href="<?php echo $this->createUrl('statements/list',array('companyId'=>$this->companyId,'type'=>1));?>"><span class="tab <?php if($type==1){ echo 'tab-active';}?>" ><?php echo yii::t('app','库存数据');?></span></a> -->
					<div class="actions">
						<!-- <a class="btn blue relation" href="javascript:;"> <?php echo yii::t('app','查看关系图');?></a>  -->
					</div>
				</div>
				<div class="portlet-body" style="min-height: 750px">
					<?php if($type==0):?>
					<!-- <a href="<?php echo $this->createUrl('statements/payallReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey sktj"></div>
							<div class="ku-item-info">收款统计</div>
						</div>
					</a>
					 -->
					<a href="<?php echo $this->createUrl('statements/incomeReport',array('companyId' => $this->companyId,'text'=>'3','setid'=>'1','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey yysr"></div>
							<div class="ku-item-info">营业收入</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/businessdataReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d 00:00:00',time()),'end_time'=>date('Y-m-d 23:59:59',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey yysj"></div>
							<div class="ku-item-info">营业数据</div>
						</div>
					</a>
					
					<a href="<?php echo $this->createUrl('statements/ceshiproductReport',array('companyId' => $this->companyId,'text'=>'3','setid'=>'1','ordertype'=>'0','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey cpxs"></div>
							<div class="ku-item-info">单品销售</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/ceshiproductsetReport',array('companyId' => $this->companyId,'text'=>'3','setid'=>'1','ordertype'=>'0','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey tcxs"></div>
							<div class="ku-item-info">套餐销售</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/orderdetail',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey zdxq"></div>
							<div class="ku-item-info">账单详情</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/takeaway',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey scyyj"></div>
							<div class="ku-item-info">送餐员业绩</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/timedataReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey sdbb"></div>
							<div class="ku-item-info">时段报表</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/recharge',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey czjl"></div>
							<div class="ku-item-info">充值记录</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/membercard',array('companyId' => $this->companyId,'text'=>'1','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey bkjl"></div>
							<div class="ku-item-info">办卡记录</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/channelsproportion',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey qdzb"></div>
							<div class="ku-item-info">渠道占比</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/tableareaReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey tzqy"></div>
							<div class="ku-item-info">台桌区域</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/retreatdetailReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d 00:00:00',time()),'end_time'=>date('Y-m-d 23:59:59',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey tcmx"></div>
							<div class="ku-item-info">退菜明细</div>
					</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/retreatreasonReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey tcyy"></div>
							<div class="ku-item-info">退菜原因</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/cuponReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey djqsy"></div>
							<div class="ku-item-info">代金券使用</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/diningNum',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey jcrs"></div>
							<div class="ku-item-info">聚餐人数</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/paymentReport',array('companyId' => $this->companyId,'text'=>'3','userid'=>'0','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey sktj"></div>
							<div class="ku-item-info">支付方式(员工营业额)</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/orderpaytype',array('companyId' => $this->companyId,'text'=>'3','paymentid'=>'0','paytype'=>'-1','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey zdzffs"></div>
							<div class="ku-item-info">账单支付方式</div>
						</div>
					</a>
					<!-- 
					<a href="<?php echo $this->createUrl('statements/turnOver',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey ygyye"></div>
							<div class="ku-item-info">员工营业额</div>
						</div>
					</a>
					
					<a href="<?php echo $this->createUrl('orderManagement/index',array('companyId'=>$this->companyId,'begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey dpgl"></div>
							<div class="ku-item-info">订单报表</div>
						</div>
					</a>
					 -->
					
					
					<?php elseif($type==1):?>
					<a href="<?php echo $this->createUrl('statements/list',array('companyId'=>$this->companyId,'type'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey cgzh"></div>
							<div class="ku-item-info">采购综合</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/list',array('companyId'=>$this->companyId,'type'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey cszh"></div>
							<div class="ku-item-info">产商综合</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/list',array('companyId'=>$this->companyId,'type'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey csls"></div>
							<div class="ku-item-info">产商零售</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/list',array('companyId'=>$this->companyId,'type'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey kczh"></div>
							<div class="ku-item-info">库存综合</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statements/list',array('companyId'=>$this->companyId,'type'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey sskc"></div>
							<div class="ku-item-info">实时库存</div>
						</div>
					</a>
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