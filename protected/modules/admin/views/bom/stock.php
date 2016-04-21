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
	.row{}
	.content{width:100%; height:80%;}
	.contentMid{width:100%; height:70%; margin:0 auto; background:url(../../../../img/ruku.png) no-repeat; background-size:100% 100%; position:relative;}
	.contentMid div{font-size:3vw; color:#fff;}
	
	
	.contentMid .one{position:absolute; top:30%; left:18%; width:11%; height:7%;}
	.contentMid .two{position:absolute; top:40%; left:18%; width:11%; height:7%;}
	.contentMid .three{position:absolute; top:30%; left:80%; width:11%; height:7%;}
	.contentMid .four{position:absolute; top:39%; left:80%; width:11%; height:7%;}
	.contentMid .five{position:absolute; top:18%; left:42%; width:11%; height:7%;}
	.contentMid .six{position:absolute; top:47%; left:56%; width:11%; height:7%;}
	.contentMid .sever{position:absolute; top:69%; left:50%; width:13%; height:9%;}
	.contentMid .eight{position:absolute; top:34%; left:4%; width:8%; height:8%;}	
</style>

	<!-- BEGIN PAGE CONTENT-->
	<div class="row" style="height:1000px;">
		<div class="content">
			<div class="contentMid">
				<a href="<?php echo $this->createUrl('/admin/orgClassification/index',array('companyId'=>$this->companyId));?>" class="one"></a>
				<a href="<?php echo $this->createUrl('/admin/orgInformation/index',array('companyId'=>$this->companyId));?>" class="two"></a>
				<a href="<?php echo $this->createUrl('/admin/mfrClassification/index',array('companyId'=>$this->companyId));?>" class="three"></a>
				<a href="<?php echo $this->createUrl('/admin/mfrInformation/index',array('companyId'=>$this->companyId));?>" class="four"></a>
				<a href="<?php echo $this->createUrl('/admin/purchaseOrder/index',array('companyId'=>$this->companyId));?>" class="five"></a>
				<a href="<?php echo $this->createUrl('/admin/storageOrder/index',array('companyId'=>$this->companyId));?>" class="six"></a>
				<a href="<?php echo $this->createUrl('/admin/refundOrder/index',array('companyId'=>$this->companyId));?>" class="sever"></a>
				<a href="<?php echo $this->createUrl('/admin/commit/index',array('companyId'=>$this->companyId));?>" class="eight"></a>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	<script>
		var divOne=document.getElementById('divOne');
		divOne.style.width=divOne.offsetHeight+'px';
	</script>