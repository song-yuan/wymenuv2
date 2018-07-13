
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
		/*.ku-purple{
			
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
	<?php if($type==1):?>
		 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','进销存数据'),'url'=>''))));?>
	<?php elseif($type==2):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','会员数据'),'url'=>''))));?>
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
			<div class="portlet-body clearfix" >
			<?php if($type==1):?>
				<div class= "panel_body row">
                <p>进销存数据</p>
					<div class="list col-sm-3 col-xs-12">
	                   <a href="<?php echo $this->createUrl('statementstock/stockReport',array('companyId'=>$this->companyId,'type'=>1));?>">
	                        <div class="list_big">进销存日报</div>
	                        <div class="list_small">统计上次日盘到本次日盘进销存的消耗信息</div>
	                    </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
	                   <a href="<?php echo $this->createUrl('statementstock/stockweekReport',array('companyId'=>$this->companyId,'text'=>2));?>">
	                        <div class="list_big">进销存周报</div>
	                        <div class="list_small">统计上次周盘盘到本次周盘进销存的消耗信息</div>
	                    </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
	                   <a href="<?php echo $this->createUrl('statementstock/stockmonthReport',array('companyId'=>$this->companyId,'text'=>3));?>">
	                        <div class="list_big">进销存月报</div>
	                        <div class="list_small">统计上次月盘盘到本次月盘进销存的消耗信息</div>
	                    </a> 
	                </div>
	                <div class="list col-sm-3 col-xs-12">
	                   <a href="<?php echo $this->createUrl('statementstock/stockallReport',array('companyId'=>$this->companyId));?>">
	                        <div class="list_big">进销存汇总</div>
	                        <div class="list_small">统计进销存总量的消耗信息 <br><br></div>
	                    </a> 
	                </div>
	                <?php if(yii::app()->user->role < 1):?>
	                <div class="list col-sm-3 col-xs-12">
	                   <a href="<?php echo $this->createUrl('statementstock/list',array('companyId'=>$this->companyId,'type'=>1));?>">
	                        <div class="list_big">实时库存</div>
	                        <div class="list_small">查询</div>
	                    </a> 
	                </div>
	            	<?php endif;?>
	            	<div class="list col-sm-3 col-xs-12">
		                   <a href="<?php echo $this->createUrl('statementstock/stockdifferReport',array('companyId'=>$this->companyId,'text'=>1));?>">
		                        <div class="list_big">库存差异报</div>
		                        <div class="list_small">统计库存的销售总成本和差异总成本信息等</div>
		                    </a> 
		                </div>
	                <div class="list col-sm-3 col-xs-12">
	                   <a href="<?php echo $this->createUrl('statementstock/stocksalesReport',array('companyId'=>$this->companyId,'type'=>1));?>">
	                        <div class="list_big">库存消耗</div>
	                        <div class="list_small">查询实时的库存消耗量</div>
	                    </a> 
	                </div>
	            </div>
	        <?php endif;?>
			</div>
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