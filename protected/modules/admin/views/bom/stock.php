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
	html,body{width:100%; height:100%;}
	.content{width:100%; height:100%;}
	.contentMid{height:80%; margin:0 auto; background:url(../../../../img/ruku.png) no-repeat; background-size:contain; position:relative;}
	.contentMid div{font-size:3vw; color:#fff;}
	.contentMid .one{position:absolute; top:4%; left:23%; width:20%; height:8%;}
	.contentMid .two{position:absolute; top:17%; left:23%; width:20%; height:8%;}
	.contentMid .three{position:absolute; top:15%; left:73%; width:20%; height:8%;}
	.contentMid .four{position:absolute; top:27%; left:73%; width:20%; height:8%;}
	.contentMid .five{position:absolute; top:67%; left:5%; width:20%; height:8%;}
	.contentMid .six{position:absolute; top:79%; left:5%; width:20%; height:8%;}
	.contentMid .sever{position:absolute; top:75%; left:70%; width:20%; height:8%;}
	.contentMid .eight{position:absolute; top:88%; left:70%; width:20%; height:8%;}
	.contentMid .nine{position:absolute; top:46%; left:41%; width:16%; height:6%;}
	.contentMid .ten{position:absolute; top:55%; left:43%; width:16%; height:6%;}
</style>

	<!-- BEGIN PAGE CONTENT-->
	<div class="row" style="height: 760px;">
		<div class="content">
			<div class="contentMid" id="divOne">
				<a href="<?php echo $this->createUrl('/admin/orgClassification/index',array('companyId'=>$this->companyId));?>" class="one"></a>
				<a href="<?php echo $this->createUrl('/admin/orgInformation/index',array('companyId'=>$this->companyId));?>" class="two"></a>
				<a href="<?php echo $this->createUrl('/admin/purchaseOrder/index',array('companyId'=>$this->companyId));?>" class="three"></a>
				<a href="<?php echo $this->createUrl('/admin/purchaseOrderDetail/index',array('companyId'=>$this->companyId));?>" class="four"></a>
				<a href="<?php echo $this->createUrl('/admin/storageOrder/index',array('companyId'=>$this->companyId));?>" class="five"></a>
				<a href="<?php echo $this->createUrl('/admin/storageOrderDetail/index',array('companyId'=>$this->companyId));?>" class="six"></a>
				<a href="<?php echo $this->createUrl('/admin/mfrClassification/index',array('companyId'=>$this->companyId));?>" class="sever"></a>
				<a href="<?php echo $this->createUrl('/admin/mfrInformation/index',array('companyId'=>$this->companyId));?>" class="eight"></a>
				<a href="<?php echo $this->createUrl('/admin/refundOrder/index',array('companyId'=>$this->companyId));?>" class="nine"></a>
				<a href="<?php echo $this->createUrl('/admin/refundOrderDetail/index',array('companyId'=>$this->companyId));?>" class="ten"></a>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	<script>
		var divOne=document.getElementById('divOne');
		divOne.style.width=divOne.offsetHeight+'px';
	</script>