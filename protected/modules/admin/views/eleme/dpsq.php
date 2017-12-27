<div class="page-content">
 <div id="responsive" class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
				<div class="modal-footer">
					<button type="button" class="btn blue">Save changes</button>
					<button type="button" class="btn default" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','饿了么外卖'),'url'=>$this->createUrl('eleme/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','店铺授权'),'url'=>$this->createUrl('eleme/dpsq' , array('companyId'=>$this->companyId,'type'=>0)))),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('eleme/index' , array('companyId' => $this->companyId)))));?>

<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','饿了么授权');?></div>
				</div>
				<div class="portlet-body">
					<iframe frameborder="0" width= 100% height= 500px src="<?php echo $sqUrl;?>?response_type=code&client_id=<?php echo $clientId;?>&redirect_uri=<?php echo $url;?>&state=<?php echo $companyId;?>&scope=all"></iframe>
				</div>
			</div>
		</div>
	</div>
</div>