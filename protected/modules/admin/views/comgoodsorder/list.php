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
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}

		.ku-grey{
			background-color:rgb(68,111,120);
		}
		.ku-item.splr{
			background-image:url(../../../../../../img/waiter/icon-goods.png);
			background-position: 7px 15px;
    		background-repeat: no-repeat;
    		background-size: 88%;
		}

		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
	</style>
	<div class="page-content">
		
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','进销存'),'url'=>''))));?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-shopping-cart"></i><?php echo yii::t('app','进销存');?></div>
				</div>
				<div class="portlet-body" style="min-height: 750px">
					<a href="<?php echo $this->createUrl('goodsorder/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple splr"></div>
							<div class="ku-item-info">采购单列表</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('goodsmaterialback/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple splr"></div>
							<div class="ku-item-info">运输损耗列表</div>
						</div>
					</a>
					
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