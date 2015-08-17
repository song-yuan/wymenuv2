
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
        width:5.0em;
        height:4.2em;			
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
/*.navigation li a:link, .navigation li a:visited{
    //background-color:#c11136;
    color:#000;
}
.navigation li a:hover{                     鼠标经过时 
    background-color:#90111A;             改变背景色 
    color:#000;                         改变文字颜色 
}*/
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
    background-color:#add;
}
.calc_num {
        width: 56%;
        display: inline-block;
        margin: 2%;
    }
    .calc_button {
        width: 33%;
        display: inline-block;
        margin: 2%;
    }
    .calc_num ul li {
        float: left;
        width: 20%;
        height: 100px;
        border: 1px solid #add;
        margin: 5px;
        font-size: 20px;
        font-weight: 700;
        background-color: #add;
        list-style: none;
        text-align: center;
        vertical-align: middle;
      }
      .calc_button ul li {
        float: left;
        width: 40%;
        height: 100px;
        border: 1px solid #add;
        margin: 5px;
        font-size: 15px;
        font-weight: 700;        
        list-style: none;
        text-align: center;
        vertical-align: middle;
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
                <div class="col-md-6">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green" id="create_btn" value="<?php echo yii::t('app','当前台：卡座->B02');?>">
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green-stripe" id="create_btn" value="<?php echo yii::t('app','转台>>');?>">
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green-stripe" id="create_btn" value="<?php echo yii::t('app','并台>>');?>">
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green-stripe" id="create_btn" value="<?php echo yii::t('app','撤台>>');?>">
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
                <div class="col-md-6" style="">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
			<input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="create_btn" value="<?php echo yii::t('app','退款<<');?>">
			<input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="create_btn" value="<?php echo yii::t('app','挂单--');?>">
			<input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="create_btn" value="<?php echo yii::t('app','全单口味');?>">
			<input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="printerKitchen" value="<?php echo yii::t('app','下单&厨打&收银&结单');?>">
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	
	<div class="row">
		
                <div class="col-md-3">
                        <div class="navigation" style="">
                            <ul class="selectedproduct">
                                <li><a lid="all" href="#" class="selectProductA">
                                        <span class="badge selectProductNum">7</span>
                                        <span class="selectProductPrice" style="color:#976125">11.21</span>
                                        <span class="selectProductInfo">三文鱼</span>
                                        <img class="selectProductDel" style="float:right; width: 30px;height: 20px;margin:5px 10px 5px 10px;" 
                                             src="<?php echo Yii::app()->request->baseUrl;?>/img/product/icon_cart_m.png">
                                    </a>
                                </li>
                                <li><a lid="all" href="#" class="selectProductA">
                                        <span class="badge selectProductNum">7</span>
                                        <span class="selectProductPrice" style="color:#976125">11.21</span>
                                        <span class="selectProductInfo">三文鱼</span>
                                        <img class="selectProductDel" style="float:right; width: 30px;height: 20px;margin:5px 10px 5px 10px;" 
                                             src="<?php echo Yii::app()->request->baseUrl;?>/img/product/icon_cart_m.png">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    
                    
                            <div class="" id="productInfo" style="display: none;">
                                <div style="margin:10px;">
                                <span style="color:#000088;font-size: 1.5em;">菜品设置</span>
                                <div class="clear"></div>                                                                            
                                <div style="display: block; width:100%;overflow-y:auto;height:100%;" id="product-detail">
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
            
            <div class="col-md-9">			
                        <div class="tabbable tabbable-custom">
                                <ul class="nav nav-tabs">
                                        <?php 
                                        foreach ($categories as $categorie): 
                                            if($categorie->pid=="0000000000"):?>
                                            <li lid="<?php echo $categorie->lid; ?>" class="tabtitle"><a href="#tab_1_tempsite" data-toggle="tab"><?php echo $categorie->category_name; ?></a></li>
                                        <?php 
                                            endif;
                                        endforeach; ?>                                        
                                        <!--<li typeId="reserve" class="tabtitle"><a href="#tab_1_reserve" data-toggle="tab">套餐</a></li>-->
                                </ul>
                                <?php 
                                foreach ($categories as $categorie): 
                                    if($categorie->pid=="0000000000"):?>
                            <div class="tab-content" style="display:none;" lid="<?php echo $categorie->lid; ?>">                                        
                                        <div style="width:100%;height:100%;">
                                            <div class="product_list">
                                                <ul class="firstcategory">
                                                    <?php 
                                                        foreach ($categories as $categorie2): 
                                                            if($categorie2->pid==$categorie->lid):?>
                                                        <li style="width:2.2em;background-color: #add"><a lid="<?php echo $categorie2->lid; ?>" href="#"><?php echo $categorie2->category_name; ?></a></li>
                                                            <?php 
                                                                foreach ($products as $product): 
                                                                    if($product->is_show=="1" and $product->category_id==$categorie2->lid):?>
                                                                    <li><a lid="<?php echo $product->lid; ?>" href="#"><?php echo $product->product_name; ?></a></li>                                                                    
                                                            <?php  endif;                                                         
                                                            endforeach; ?>
                                                    <?php 
                                                        endif;
                                                    endforeach; ?>
                                                </ul>
                                            </div>                                        												
                                        </div>
                                    </div>
                                <?php 
                                    endif;
                                endforeach; ?> 
                                
                        </div>
                </div>
            
                                    <div id="accountbox" style="display:none;">
                
                                        <div style="margin:20px;">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title"><span style="color:red;"><?php echo yii::t('app','现金收款');?></span><br>
                                                            <?php echo yii::t('app','总额：');?>
                                                                <?php echo yii::t('app','，已付：');?>
                                                                    <?php echo yii::t('app','，应付：');?><br>
                                                                    <span style="width:90px; text-align:right; display: inline-block"><?php echo yii::t('app','收款：');?></span>
                                                                    <span id="cash_in" pointat="0" style="color:blue;width:190px; text-align:right; display: inline-block">0</span>
                                                                    <span style="width:90px; text-align:right; display: inline-block"><?php echo yii::t('app','找零：');?></span>
                                                                    <span id="cash_out" style="color:red;width:190px; text-align:right; display: inline-block">0</span></h4>
                                                        
                                               
                                                
                                                    <div class="calc_num">
                                                        <ul>
                                                            <li>1</li>
                                                            <li>2</li>
                                                            <li>3</li>
                                                            <li>4</li>
                                                            <li>5</li>
                                                            <li>6</li>
                                                            <li>7</li>
                                                            <li>8</li>
                                                            <li>9</li>
                                                            <li>0</li>
                                                            <li>00</li>
                                                            <li>.</li>
                                                        </ul>
                                                    </div>
                                                    <div class="calc_button">
                                                        <ul>
                                                            <li id="clearall" style="background-color: #add"><?php echo yii::t('app','清空');?></li>
                                                            <li id="clearone" style="background-color: #add"><?php echo yii::t('app','退格');?></li>
                                                            <li id="pay-btn" style="background-color: #0099FF"><?php echo yii::t('app','收银');?></li>
                                                            <li id="account-btn" style="background-color: #0099FF"><?php echo yii::t('app','结单');?></li>
                                                            <li id="other-btn" style="background-color: #009f95"><?php echo yii::t('app','其他付款方式');?></li>
                                                            <li id="layer2_close" class="default" style="background-color: #00FFFFFF"><?php echo yii::t('app','取消');?></li>
                                                        </ul>
                                                    </div>                                                            
                                                </div>
                                    </div>
	</div>
        <script type="text/javascript">
            var layer_index1;
            var layer_index2;
            var first_tab="<?php echo $categories[0]['lid']; ?>";
            if (typeof Androidwymenuprinter == "undefined") {
                event_clicktouchstart="click";
                event_clicktouchend="click";
            }else{
                event_clicktouchstart="touchstart";
                event_clicktouchend="touchend";
            }
            alert(event_clicktouchstart);
            $(document).ready(function() {
                $('body').addClass('page-sidebar-closed');
                //$('.nav-tabs').find('li[lid='+first_tab+']').addClass("active");
                $('.tabtitle[lid='+first_tab+']').addClass("active");
                $('.tab-content[lid='+first_tab+']').show();
                //tab-content
            });
            
            $('.tabtitle').on(event_clicktouchstart, function(){
                var lid=$(this).attr('lid');
                //alert(lid);
                $('.tab-content').hide();
                $('.tab-content[lid='+lid+']').show();
            });
            
            $('.selectProductInfo').on(event_clicktouchstart, function(){
               
                 layer_index1=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     //area: ['80%', '90%'],
                     content: $('#productInfo'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                 });                                          
            });
            $('#printerKitchen').on(event_clicktouchstart, function(){
               
                 layer_index2=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['80%', '90%'],
                     content: $('#accountbox'),//$('#productInfo'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                 });   
                //$('#portlet-config3').modal();
                //portlet-config3
            });
            $('#layer2_close').on(event_clicktouchstart, function(){               
                 layer.close(layer_index2);                    
            });
            
            
            $('.selectProductDel').on('click', function(){
                alert("selectProductDel");
                return false;
            });
            $('.selectProductA').on('click', function(){
                //alert("selectProductA");
            });
            
            var now_should_pay=100;
            $('.calc_num').on(event_clicktouchstart,'li',function(){
                var inval=$("#cash_in").html();
                //alert(inval);
                if(inval=="0" || inval=="00")
                {
                    if($(this).html()!=".")
                    {
                        $("#cash_in").html($(this).html());
                    }
                }else{
                    if(inval.indexOf(".")>0 && $(this).html()==".")
                    {

                    }else{
                        $("#cash_in").html(inval+$(this).html());
                    }
                }
//                                var inval=$(this).html();
//                                var cashin="0";
//                                var cashint=0;
//                                var pointat=0;
//                                if(inval!='.')
//                                {
//                                    cashin=$("#cash_in").html();
//                                     pointat=$("#cash_in").attr("pointat");
//                                     if(pointat=='0')
//                                     {
//                                        cashint=parseInt(cashin);
//                                        if(inval=="00")
//                                        {
//                                            $("#cash_in").html(cashint*100);
//                                        }else{
//                                            //alert(cashint);alert(inval)
//                                            $("#cash_in").html(cashint*10+parseInt(inval));
//                                        }
//                                     }else if(pointat=='1'){
//                                        if(inval!="00")
//                                        {
//                                            $("#cash_in").html(cashin.substr(0,cashin.length-2)+inval+"0");
//                                            $("#cash_in").attr("pointat","10");
//                                        }
//                                     }else if(pointat=='10'){
//                                        $("#cash_in").html(cashin.substr(0,cashin.length-1)+inval);
//                                        $("#cash_in").attr("pointat","100");
//                                     }
//                                }else{
//                                    $("#cash_in").attr("pointat","1");
//                                    $("#cash_in").html($("#cash_in").html()+".00");
//                                }
                var cashinf=parseFloat($("#cash_in").html());
                //alert($("#cash_in").html());alert(parseFloat($("#cash_in").html()));
                //alert(now_should_pay);
                if(cashinf-now_should_pay>0)
                {
                    $("#cash_out").html(Math.round((cashinf-now_should_pay)*100)/100);//little than 0 not show
                }else{
                    $("#cash_out").html("0");
                }
            });

            $('#clearall').on(event_clicktouchstart,function(){
                $("#cash_in").html("0");
                //$("#cash_in").attr("pointat","0");
                //cash_out
                $("#cash_out").html("0");
            });

            $('#clearone').on(event_clicktouchstart,function(){
                var cashin=$("#cash_in").html();
                if(cashin.length>1)
                {
                    $("#cash_in").html(cashin.substr(0,cashin.length-1));
                }else{
                    $("#cash_in").html("0");
                }
                //var pointat=$("#cash_in").attr("pointat");
//                                var cashin=$("#cash_in").html();
//                                
//                                if(pointat=="100")
//                                {
//                                    //xxx.x0
//                                    $("#cash_in").html(cashin.substr(0,cashin.length-1)+"0");
//                                    $("#cash_in").attr("pointat","10");
//                                }else if(pointat=="10"){
//                                    //xxx.00
//                                    $("#cash_in").html(cashin.substr(0,cashin.length-2)+"00");
//                                    $("#cash_in").attr("pointat","1");
//                                }else if(pointat=="1"){
//                                    //xxx
//                                    $("#cash_in").html(cashin.substr(0,cashin.length-3));
//                                    $("#cash_in").attr("pointat","0");
//                                }else if(pointat=="0"){
//                                    if(cashin.length>1)
//                                    {
//                                        $("#cash_in").html(Math.round((cashinf-now_should_pay)*100)/100);
//                                    }else{
//                                        $("#cash_in").html("0");
//                                    }
//                                    //xx
//                                }
                var cashinf=parseFloat($("#cash_in").html());
                if(cashinf-now_should_pay>0)
                {
                    $("#cash_out").html(cashinf-now_should_pay);//little than 0 not show
                }else{
                    $("#cash_out").html("0");
                }
            });
            $('#other-btn').on(event_clicktouchstart,function(){
                 bootbox.confirm("<?php echo yii::t('app','你确定切换到其他支付方式吗？');?>", function(result) {
                        if(result){
                                openaccount('0');
                        }
                 });
            });
                            
	</script>

