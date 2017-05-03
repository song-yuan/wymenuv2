
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
	<?php if($type==1):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','进销存数据'),'url'=>''))));?>
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
				<div class="portlet-body" style="min-height: 750px">
					
					<?php if($type==1):?>
					<a href="<?php echo $this->createUrl('statementstock/stockReport',array('companyId'=>$this->companyId,'type'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey cgzh"></div>
							<div class="ku-item-info">进销存日报</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statementstock/stockmonthReport',array('companyId'=>$this->companyId,'text'=>2));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey cszh"></div>
							<div class="ku-item-info">进销存月报</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statementstock/stockallReport',array('companyId'=>$this->companyId,'text'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey csls"></div>
							<div class="ku-item-info">进销存汇总</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('statementstock/stockdifferReport',array('companyId'=>$this->companyId,'text'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey kczh"></div>
							<div class="ku-item-info">库存差异报</div>
						</div>
					</a>
					<a style="display: none;" href="<?php echo $this->createUrl('statementstock/list',array('companyId'=>$this->companyId,'type'=>1));?>">
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