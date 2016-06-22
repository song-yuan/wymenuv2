
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
			border:2px solid black;
			box-shadow: 5px 5px 5px #888888;
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.ku-purple{
			background-color:#852b99;
		}
		.ku-grey{
			background-color:rgb(68,111,120);
		}
		.ku-item.zkmb{
			background-image:url(../../../../../../img/waiter/icon-xxhd.png);
			background-position: 15px 10px;
			background-size: 500%;
    		background-repeat: no-repeat;
		}
		.ku-item.ptyh{
			background-image:url(../../../../../../img/waiter/icon-xxhd.png);
			background-position: -185px 10px;
			background-size: 500%;
    		background-repeat: no-repeat;
		}
		.ku-item.msmj{
			background-image:url(../../../../../../img/waiter/icon-xxhd.png);
			background-position: -395px 10px;
			background-size: 500%;
    		background-repeat: no-repeat;
		}
		.ku-item.yxpsz{
			background-image:url(../../../../../../img/waiter/icon-xshd.png);
			background-position: 15px 17px;
    		background-repeat: no-repeat;
		}		
		.ku-item.wxhb{
			background-image:url(../../../../../../img/waiter/icon-xshd.png);
			background-position: -140px 15px;
    		background-repeat: no-repeat;
		}		
		.ku-item.yxhd{
			background-image:url(../../../../../../img/waiter/icon-yxhd.png);
			background-position: center center;
			background-size: 80%;
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
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','活动中心'),'subhead'=>yii::t('app','线下活动'),'breadcrumbs'=>array(array('word'=>yii::t('app','线下活动'),'url'=>''))));?>
	<?php elseif($type==1):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','活动中心'),'subhead'=>yii::t('app','线上活动'),'breadcrumbs'=>array(array('word'=>yii::t('app','线上活动'),'url'=>''))));?>
	<?php endif;?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><em class=" fa <?php if($type==0){echo '';}else{echo 'cf-black';}?> fa-chain-broken"></em><a href="<?php echo $this->createUrl('discount/list',array('companyId'=>$this->companyId,'type'=>0));?>"><span class="tab <?php if($type==0){ echo 'tab-active';}?>"><?php echo yii::t('app','线下活动');?></span></a><em class=" fa <?php if($type==1){echo '';}else{echo 'cf-black';}?> fa-chain"></em><a href="<?php echo $this->createUrl('discount/list',array('companyId'=>$this->companyId,'type'=>1));?>"><span class="tab <?php if($type==1){ echo 'tab-active';}?>" ><?php echo yii::t('app','线上活动');?></span></a></div>
					<div class="actions">
						<!-- <a class="btn blue relation" href="javascript:;"> <?php echo yii::t('app','查看关系图');?></a>  -->
					</div>
				</div>
				<div class="portlet-body" style="min-height: 750px">
					<?php if($type==0):?>
					<a href="<?php echo $this->createUrl('discount/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple zkmb"></div>
							<div class="ku-item-info">折扣模板</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('normalpromotion/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple ptyh"></div>
							<div class="ku-item-info">普通优惠</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('fullSentPromotion/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple msmj"></div>
							<div class="ku-item-info">满送优惠</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('fullMinusPromotion/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple msmj"></div>
							<div class="ku-item-info">满减优惠</div>
						</div>
					</a>
					<?php elseif($type==1):?>
					<a href="<?php echo $this->createUrl('cashcard/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple yxpsz"></div>
							<div class="ku-item-info">营销品设置</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('wxRedpacket/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple wxhb"></div>
							<div class="ku-item-info">微信红包</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('promotionActivity/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple yxhd"></div>
							<div class="ku-item-info">营销活动</div>
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