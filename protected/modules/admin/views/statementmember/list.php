
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
/*		.ku-purple{
			
		}
		.ku-item.sdbb{
			background-image:url(../../../../../../img/waiter/icon-sdbb.png);
			background-position: 13px 15px;
    		background-repeat: no-repeat;
			background-size: 80%;
		}	
		.ku-item.yysj{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -750px 16px;
    		background-repeat: no-repeat;
		}
		.ku-item.qcsj{
			background-image:url(../../../../../../img/deletedata.jpg);
			background-position: 10px 10px;
    		background-repeat: no-repeat;
    		background-size: 88%;
		}
		.ku-item.czjl{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: -295px 16px;
    		background-repeat: no-repeat;
		}
		.ku-item.sktj{
			background-image:url(../../../../../../img/waiter/icon-yysj.png);
			background-position: 8px 16px;
    		background-repeat: no-repeat;
		}*/
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
		.cf-black{
			color: #000 !important;
			
		}
/*		.portlet-body a{
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
	<?php if($type==2):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','会员数据'),'url'=>''))));?>
	<?php elseif($type==3):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','清除数据'),'url'=>''))));?>
	<?php endif;?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
		      	<div class="portlet-title">
				<div class="caption"><em class=" fa <?php if($type==0){echo '';}else{echo 'cf-black';}?> fa-shopping-cart">&nbsp</em><a href="<?php echo $this->createUrl('statements/list',array('companyId'=>$this->companyId,'type'=>0));?>"><span class="tab <?php if($type==0){ echo 'tab-active';}?>"><?php echo yii::t('app','营业数据');?></span></a></div>
				<div class="caption"><em class=" fa <?php if($type==1){echo '';}else{echo 'cf-black';}?> fa-truck">&nbsp</em><a href="<?php echo $this->createUrl('statementstock/list',array('companyId'=>$this->companyId,'type'=>1));?>"><span class="tab <?php if($type==1){ echo 'tab-active';}?>"><?php echo yii::t('app','进销存数据');?></span></a></div>
				<div class="caption"><em class=" fa <?php if($type==2){echo '';}else{echo 'cf-black';}?> fa-group">&nbsp</em><a href="<?php echo $this->createUrl('statementmember/list',array('companyId'=>$this->companyId,'type'=>2));?>"><span class="tab <?php if($type==2){ echo 'tab-active';}?>"><?php echo yii::t('app','会员数据');?></span></a></div>
				<div class="caption"><em class=" fa <?php if($type==3){echo '';}else{echo 'cf-black';}?> fa-warning ">&nbsp</em><a href="<?php echo $this->createUrl('statementmember/list',array('companyId'=>$this->companyId,'type'=>3));?>"><span class="tab <?php if($type==3){ echo 'tab-active';}?>"><?php echo yii::t('app','清除数据');?></span></a></div>
			</div>
		<?php if($type==2):?>
			<div class="portlet-body clearfix" >
				<div class= "panel_body row">
	                <p>会员记录报表</p>
	                <div class="list col-sm-3 col-xs-12">
	                    <a href="<?php echo $this->createUrl('statements/recharge',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
	                        <div class="list_big">充值记录</div>
	                        <div class="list_small">查询实体卡和微会员的所有充值记录</div>
	                    </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
	                    <a href="<?php echo $this->createUrl('statements/membercard',array('companyId' => $this->companyId,'text'=>'1','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
	                        <div class="list_big">办卡记录</div>
	                        <div class="list_small">查询所有实体卡和微信会员的生成记录</div>
	                    </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
	                    <a href="<?php echo $this->createUrl('statementmember/wxmemberReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
	                        <div class="list_big">微信会员</div>
	                        <div class="list_small">按条件查询微信会员的基础信息</div>
	                    </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
	                    <a href="<?php echo $this->createUrl('statementmember/cardmemberReport',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
	                        <div class="list_big">实卡会员</div>
	                        <div class="list_small">按条件查询实体卡会员的基础信息</div>
	                    </a> 
	                </div>
	                <?php if(yii::app()->user->role <=5):?>
	                <div class="list col-sm-3 col-xs-12">
	                    <a href="<?php echo $this->createUrl('statementmember/wxRecharge',array('companyId' => $this->companyId,'text'=>'2','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
	                        <div class="list_big">充值统计</div>
	                        <div class="list_small">查询微信会员的总充值及总消费信息等</div>
	                    </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
	                    <a href="<?php echo $this->createUrl('statementmember/paymentReport',array('companyId' => $this->companyId,'text'=>'3','userid'=>'0','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
	                        <div class="list_big">会员支付方式</div>
	                        <div class="list_small">查询所有会员的在店铺的消费信息及支付方式</div>
	                    </a> 
	                </div>
	            	<?php endif;?>
	            </div>
	        </div>
	    	<?php elseif($type==3):?>
			<div class="portlet-body clearfix" >
				<div class= "panel_body row">
	                <p>数据清除</p>
					<div class="list col-sm-3 col-xs-12">
                    	<a href="<?php echo $this->createUrl('statementmember/clearTestdata',array('companyId' => $this->companyId,'text'=>'3','begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">     
                        	<div class="list_big">数据清除</div>
                        	<div class="list_small"></div>
                    	</a> 
                	</div>
                </div>
            </div>
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