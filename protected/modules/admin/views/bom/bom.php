<?php
$basepath = Yii::app()->baseUrl;

?>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<style>
		h1{ margin:0;padding:0; padding-bottom:10px;}
		html,body{width:100%; height:100%;}
		.content{width:100%; height:100%;}
		.contentMid{width:80%; height:60%; margin:0 auto; background:url(../../../../img/BOM.gif) no-repeat; background-size:100% 100%; position:relative;}
		.contentMid div{font-size:3vw; color:#fff;}
		.contentMid .one{position:absolute; top:0%; left:34%; width:27%; height:16%;}
		.contentMid .two{position:absolute; top:26%; left:16%; width:26%; height:16%;}
		.contentMid .three{position:absolute; top:26%; left:54%; width:26%; height:16%; }
		.contentMid .four{position:absolute; top:60%; left:0%; width:23%; height:14%; }
		.contentMid .five{position:absolute; top:60%; left:25%; width:23%; height:14%; }
		.contentMid .six{position:absolute; top:60%; left:52%; width:23%; height:14%; }
		.contentMid .sever{position:absolute; top:60%; left:77%; width:23%; height:14%;}
		.contentMid .eight{position:absolute; top:86%; left:39%; width:23%; height:14%;}
		.bottom a{ background-color:#0f0;color:#fff; box-shadow: 4px 4px 4px #888888; width:18%; height:50px; display:inline-block; line-height:50px; text-align:center; font-size:25px;font-weight:bold; margin-left:10%; text-decoration:none;}
	</style>

	<!-- BEGIN PAGE CONTENT-->
	<div class="row" style="height: 760px;">
		<div class="content">
			<div class="contentMid">
				<a href="<?php echo $this->createUrl('/admin/productBom/index',array('companyId'=>$this->companyId));?>" class="one"></a>
				<a href="<?php echo $this->createUrl('/admin/productMaterial/index',array('companyId'=>$this->companyId));?>" class="two"></a>
				<a href="<?php echo $this->createUrl('/admin/product/index',array('companyId'=>$this->companyId));?>" class="three"></a>
				<a href="<?php echo $this->createUrl('/admin/materialCategory/index',array('companyId'=>$this->companyId));?>" class="four"></a>
				<a href="<?php echo $this->createUrl('/admin/materialUnit/index',array('companyId'=>$this->companyId,'type'=>"0"));?>" class="five"></a>
				<a href="<?php echo $this->createUrl('/admin/materialUnit/index',array('companyId'=>$this->companyId,'type'=>"1"));?>" class="six"></a>
				<a href="<?php echo $this->createUrl('/admin/productCategory/index',array('companyId'=>$this->companyId));?>" class="sever"></a>
				<a href="<?php echo $this->createUrl('/admin/materialUnitRatio/index',array('companyId'=>$this->companyId));?>" class="eight"></a>
			</div>
			<div class="bottom"><a href="<?php echo $this->createUrl('/admin/materialStockLog/index',array('companyId'=>$this->companyId));?>">品项库存日志</a></div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	