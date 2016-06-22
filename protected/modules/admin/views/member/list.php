
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
		.ku-item.wxhysz{
			background-image:url(../../../../../../img/waiter/icon-wxhy.png);
			background-position: 15px 10px;
    		background-repeat: no-repeat;
		}
		.ku-item.wxhylb{
			background-image:url(../../../../../../img/waiter/icon-wxhy.png);
			background-position: -145px 10px;
    		background-repeat: no-repeat;
		}
		.ku-item.ctkhylb{
			background-image:url(../../../../../../img/waiter/icon-ckhy.png);
			background-position: center center;
			background-position: 60%;
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
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','会员中心'),'subhead'=>yii::t('app','微信会员'),'breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>''))));?>
	<?php elseif($type==1):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','会员中心'),'subhead'=>yii::t('app','传统卡会员'),'breadcrumbs'=>array(array('word'=>yii::t('app','传统卡会员'),'url'=>''))));?>
	<?php endif;?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><em class=" fa <?php if($type==0){echo '';}else{echo 'cf-black';}?> fa-comments-o">&nbsp</em><a href="<?php echo $this->createUrl('member/list',array('companyId'=>$this->companyId,'type'=>0));?>"><span class="tab <?php if($type==0){ echo 'tab-active';}?>"><?php echo yii::t('app','微信会员');?></span></a><em class=" fa <?php if($type==1){echo '';}else{echo 'cf-black';}?> fa-credit-card">&nbsp</em><a href="<?php echo $this->createUrl('member/list',array('companyId'=>$this->companyId,'type'=>1));?>"><span class="tab <?php if($type==1){ echo 'tab-active';}?>" ><?php echo yii::t('app','传统卡会员');?></span></a></div>
					<div class="actions">
						<!-- <a class="btn blue relation" href="javascript:;"> <?php echo yii::t('app','查看关系图');?></a>  -->
					</div>
				</div>
				<div class="portlet-body" style="min-height: 750px">
					<?php if($type==0):?>
					<a href="<?php echo $this->createUrl('wxlevel/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple wxhysz"></div>
							<div class="ku-item-info">会员设置</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('member/wxmember',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple wxhylb"></div>
							<div class="ku-item-info">会员列表</div>
						</div>
					</a>
					<?php elseif($type==1):?>
					<a href="<?php echo $this->createUrl('member/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple ctkhylb"></div>
							<div class="ku-item-info">会员列表</div>
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