<link href="../../../../css/jxcgl.css" rel="stylesheet" type="text/css">
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

	<!-- BEGIN PAGE CONTENT-->
	<div class="row" style="height: 700px;">
        <div class="middle">
            <div class="tabButton">
                <ul>
                    <li class="marginLeft">
                        <a href="#" class="first">库存设置</a>
                    </li>
                    <li >
                        <a href="#">品项信息</a>
                    </li>
                    <li>
                        <a href="#">库存管理</a>
                    </li>
                </ul>
            </div>
            <!--库存设置-->
            <div class="inventory">
                <div class="one"><a href="<?php echo $this->createUrl('/admin/stockSetting/index',array('companyId'=>$this->companyId));?>"></a></div>
            </div>

            <!--品项信息-->
            <div class="itemsMid" >
                <div class="one"><a href="<?php echo $this->createUrl('/admin/productBom/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="two"><a href="<?php echo $this->createUrl('/admin/productMaterial/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="three"><a href="<?php echo $this->createUrl('/admin/product/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="four"><a href="<?php echo $this->createUrl('/admin/materialCategory/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="five"><a href="<?php echo $this->createUrl('/admin/materialUnit/index',array('companyId'=>$this->companyId,'type'=>"0"));?>"></a></div>
                <div class="six"><a href="<?php echo $this->createUrl('/admin/materialUnit/index',array('companyId'=>$this->companyId,'type'=>"1"));?>"></a></div>
                <div class="sever"><a href="<?php echo $this->createUrl('/admin/productCategory/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="eight"><a href="<?php echo $this->createUrl('/admin/materialUnitRatio/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="nine"><a href="<?php echo $this->createUrl('/admin/materialStockLog/index',array('companyId'=>$this->companyId));?>"></a></div>
            </div>

            <!--库存管理-->
            <div class="stockMid" >
                <div class="one"><a href="<?php echo $this->createUrl('/admin/orgClassification/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="two"><a href="<?php echo $this->createUrl('/admin/orgInformation/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="three"><a href="<?php echo $this->createUrl('/admin/mfrInformation/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="four"><a href="<?php echo $this->createUrl('/admin/mfrClassification/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="five"><a href="<?php echo $this->createUrl('/admin/purchaseOrder/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="six"><a href="<?php echo $this->createUrl('/admin/refundOrder/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="sever"><a href="<?php echo $this->createUrl('/admin/storageOrder/index',array('companyId'=>$this->companyId));?>"></a></div>
                <div class="eight"><a href="<?php echo $this->createUrl('/admin/commit/index',array('companyId'=>$this->companyId));?>"></a></div>
            </div>
        </div>
	</div>
	<!-- END PAGE CONTENT-->
	<script>
        $(document).ready(function(e) {
            $(".tabButton li a").click(function(){
                $(".tabButton li a").css({"background":"#00b7ee"})
                $(this).css({"background":"#fff"})
                $(".tabButton li a").css({"color":"#fff"})
                $(this).css({"color":"#000"})
                var i=$(this).parent().index();
                $(this).parents(".middle").children().not(".tabButton").hide();
                $(this).parents(".middle").children().eq(i+1).show();
                switch(i){
                    case 0:
                        $(this).parents(".middle").css("background-image","url(../../../../img/jxcgl/aqkcsz.png)");break;
                    case 1:
                        $(this).parents(".middle").css("background-image","url(../../../../img/jxcgl/pxxxz.png)");break;
                    case 2:
                        $(this).parents(".middle").css("background-image","url(../../../../img/jxcgl/kcgl.png)");break;
                }
            });
        });
	</script>