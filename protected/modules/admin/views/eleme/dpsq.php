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
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','饿了么外卖'),'url'=>$this->createUrl('eleme/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','店铺授权'),'url'=>$this->createUrl('eleme/dpsq' , array('companyId'=>$this->companyId,'type'=>0)))),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('waimai/list' , array('companyId' => $this->companyId,'type' => '0')))));?>
	<?php if(!empty($token)){?>
	<table cellpadding="0" cellspacing="0" width="100%" border="1" style="text-align: center;">
		<tr>
			<td>店铺ID</td>
			<td>绑定时间</td>
			<td>更新时间</td>
			<td>授权有效时间</td>
			<td>操作</td>
		</tr>
		<tr>
			<td><?php echo $token['dpid'];?></td>
			<td><?php echo $token['create_at'];?></td>
			<td><?php echo $token['update_at'];?></td>
			<td><?php echo date("Y-m-d H:i:s",$token['expires_in']);?></td>
			<td><a href="<?php echo $this->createUrl('eleme/dpsq',array('companyId'=>$this->companyId));?>/type/1">解绑</a></td>
		</tr>
	</table>
	<?php }else{?>
	<iframe frameborder="0" width= 100% height= 500px src="<?php echo $sqUrl;?>?response_type=code&client_id=<?php echo $clientId;?>&redirect_uri=<?php echo $url;?>&state=<?php echo $companyId;?>&scope=all"></iframe>
	<?php }?>
</div>