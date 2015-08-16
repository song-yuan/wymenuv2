
<style>
.product_list {
        padding-right:10px;
}
.product_list {
        display:inline-block;
}
.product_list ul {
        padding-left:5px;
        padding-left:0px;
}
.product_list ul li {
        float:left;
        width:80px;
        height:60px;			
        border: 1px solid #add;
        margin:5px;
        list-style:none;
        text-align:center;
        vertical-align:middle;
}
    
.navigation {
    width:100%;
    font-family:Arial;
    float: left;
    height: 98%;
}
.navigation ul {
    list-style-type:none;                /* 不显示项目符号 */
    margin:0px;
    padding:0px;
    height: 100%;
    overflow-y:auto; 
}
.navigation li {
    border-bottom:1px solid #ED9F9F;    /* 添加下划线 */
    font-size: 18px;
}
.navigation li a{
    display:block;                        /* 区块显示 */
    padding:8px 8px 8px 0.5em;
    text-decoration:none;
    border-left:12px solid #711515;        /* 左边的粗红边 */
    border-right:1px solid #711515;        /* 右侧阴影 */
}
.navigation li a:link, .navigation li a:visited{
    //background-color:#c11136;
    color:#000;
}
.navigation li a:hover{                    /* 鼠标经过时 */
    background-color:#90111A;            /* 改变背景色 */
    color:#000;                        /* 改变文字颜色 */
}
.clear{
    clear: both;
}
.slectliclass{
    background-color:#90111A;
}

.firstcategory{
    background-color:#DDDDDD;
}

.secondcategory{
    background-color:#DDDDDD;
}

.productstyle{
    background-color:#78ccf8;
}

.selectedproduct{
    background-color:#0088cc;
}

</style>

<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/default.css'); ?>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-wide">
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
	<!-- BEGIN PAGE HEADER-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
			<input style="" type="button" class="btn blue" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	
	<div class="row">
		<div class="col-md-10">			
                        <div class="tabbable tabbable-custom">
                                <ul class="nav nav-tabs">
                                        <li typeId="tempsite" class="tabtitle active"><a href="#tab_1_tempsite" data-toggle="tab">海鲜</a></li>
                                        <li typeId="reserve" class="tabtitle"><a href="#tab_1_reserve" data-toggle="tab">套餐</a></li>
                                </ul>
                                <div class="tab-content" id="tabsiteindex">
                                                <div style="width:100%;height:100%;">                                                                        
                                                        <div class="product_list">
                                                            <ul class="firstcategory">
                                                                <li><a lid="all" href="#">三文鱼</a></li>
                                                                <li><a lid="special" href="#">八爪鱼</a></li>

                                                            </ul>
                                                        </div>
                                        <!-- END EXAMPLE TABLE PORTLET-->												
                                        </div>
                                </div>		
                        </div>
                </div>
                <div class="col-md-2">
                        <div class="navigation" style="">
                            <ul class="selectedproduct">
                                <li><a lid="all" href="#">三文鱼</a><img style="float:right; width: 50px;height: 20px;" src="<?php echo Yii::app()->request->baseUrl;?>/img/product/icon_cart_m.png"></li>
                                    <li><a lid="special" href="#">八爪鱼</a></li>
                            </ul>
                        </div>
                            <div class="navigation hide" style="width:18%;height:80%;">
                                <span style="color:#000088;font-size: 1.5em;">菜品设置</span>
                                <div class="clear"></div>                                                                            
                                <div style="display: none; width:100%;overflow-y:auto;height:100%;" id="product-detail">
                                    <select  id="product-detail-isgiving" style="width:90%;" class="form-control" placeholder="赠送" name="OrderProduct[is_giving]" id="OrderProduct_is_giving">
                                        <option value="0" selected="selected">不赠送</option>
                                        <option value="1">赠送</option>
                                    </select>
                                    <div class="clear"></div>
                                    <label style="width:40%;">下单价格</label>
                                    <input id="product-detail-price" style="width:50%;display:inline-block;" class="form-control" placeholder="下单时价格" name="OrderProduct[price]" id="OrderProduct_price" type="text" maxlength="10" value="0.00">
                                    <div class="clear"></div>
                                    <label style="width:40%;">数量</label>
                                    <div class="clear"></div>
                                    <div style="width:100%;">
                                    <span id="product-detail-amount-m1" style="width:20%;margin:2px;border: 1px solid red;background: rgb(245,230,230);height: 34px;padding: 6px 12px;">-1</span>
                                    <span id="product-detail-amount" style="width:30%;display:inline-block;" class="form-control" placeholder="下单数量" name="OrderProduct[amount]" id="OrderProduct_amount" type="text" value="1">0</span>
                                    <span id="product-detail-amount-a1" style="width:20%;margin:2px;border: 1px solid red;background: rgb(245,230,230);height: 34px;padding: 6px 12px;">+1</span>
                                    <span id="product-detail-amount-ah" style="width:20%;margin:2px;border: 1px solid red;background: rgb(245,230,230);height: 34px;padding: 6px 6px;">+0.5</span>
                                    </div>
                                    <div class="clear"></div>
                                    <label style="width:20%;">只数</label>
                                    <div class="clear"></div>
                                    <div style="width:100%;">
                                    <span id="product-detail-zhiamount-m1" style="width:20%;margin:2px;border: 1px solid red;background: rgb(245,230,230);height: 34px;padding: 6px 12px;">-1</span>
                                    <span id="product-detail-zhiamount" style="width:30%;display:inline-block;disabled:disabled;" class="form-control" placeholder="下单只数" name="OrderProduct[zhiamount]" id="OrderProduct_zhiamount" type="text" value="0">0</span>
                                    <span id="product-detail-zhiamount-a1" style="width:20%;margin:2px;border: 1px solid red;background: rgb(245,230,230);height: 34px;padding: 6px 12px;">+1</span>
                                    <span id="product-detail-zhiamount-ah" style="width:20%;margin:2px;border: 1px solid red;background: rgb(245,230,230);height: 34px;padding: 6px 6px;">+0.5</span>
                                    </div>                                                                                                                                                     
                                </div>                                                                            
                            </div>                 	
                </div>
		
	</div>

