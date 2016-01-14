
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

.firstCategory {
        padding-right:10px;
}
.firstCategory {
        display:inline-block;
}
.firstCategory ul {
        padding-left:5px;
        padding-left:0px;
}
.firstCategory ul li {
        float:left;
        background-color: #DDDDDD;
        min-width: 5.0em;
        height:2.8em;			
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
    font-size: 1.2em;
}
.navigation li{
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
    background-color:#0099FF !important;
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

.productClick{
    //background-color:#78ccf8;
}

.productSetClick{
    
}

.selectedproduct{
    background-color:#add;
}

.calc_zhe ul li{
    	line-height:30px;
        float: left;
        width: 40%;
        height: 30px;
        border: 1px solid #add;
        margin-top: 8px;
		margin-left: 8px;
		margin-right: 8px;
        font-size: 20px;
        font-weight: 700;
        background-color: pink;
        list-style: none;
        text-align: center;
        vertical-align: middle;
	
}
.calc_num {
        width: 72%;
        display: inline-block;
        margin-top: 10px;
    }
.alphabet {
        width: 100%;
        display: inline-block;
        margin-top: 5px;
    }
.calc_button {
        width: 24%;
        display: inline-block;
        margin-top: 10px;
    	margin-left:0px;
    }
.calc_num ul li {
    	line-height:50px;
        float: left;
        width: 20%;
        height: 2.5em;
        border: 1px solid #add;
        margin: 5px;
        font-size: 20px;
        font-weight: 700;
        background-color: #add;
        list-style: none;
        text-align: center;
        vertical-align: middle;
      }
 .alphabet ul li {
    	//line-height:40px;
        float: left;
        width: 80px;
        height: 50px;
        border: 1px solid #add;
        margin: 3px;
        font-size: 15px;
        font-weight: 700;
        background-color: #add;
        list-style: none;
        text-align: center;
        vertical-align: middle;
      }
      .calc_button ul li {
      	padding-left:0px;
      	line-height:50px;
        float: left;
        width: 100%;
        height: 3.5em;
        border: 1px solid #add;
        margin: 5px;
        font-size: 15px;
        font-weight: 700;        
        list-style: none;
        text-align: center;
       
      }
 .calc_dan {
		width:70%;
		display:inline-block;
		margin:5px;
}
	.calc_dan ul li{
		line-height:30px;
        float: left;
        width: 30%;
        height: 2.5em;
        border: 1px solid #add;
        margin: 5px;
        font-size: 20px;
        font-weight: 700;
        background-color: #add;
        list-style: none;
        text-align: center;
        vertical-align: middle;
	}

.dan_button {
		width:25%;
		display:inline-block;
		margin:5px;
}	
.dan_button ul li {
    line-height:45px;
    float: left;
    width: 90%;
    height: 3.5em;
    border: 1px solid #add;
    margin: 5px;
    font-size: 15px;
    font-weight: 700;        
    list-style: none;
    text-align: center;
}
.edit_span_hide {
    display: none;
}
.edit_span_select {
    border:1px solid red;
    background-color:#ED9F9F !important;    
}
.edit_span_select_zero {
    border:1px solid red;
    background-color:#add !important;
}

.edit_span_select_member {
    border:1px solid red;
    background-color:#ED9F9F !important;
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
                        <div class="modal fade" id="portlet-config2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Modal title2</h4>
                                                </div>
                                                <div class="modal-body">
                                                        Widget settings form goes here2
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
        <div  id="order_row" style="display:none;">
        <div class="row">
                <div class="col-md-5">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green" id="site_list_button" value="<?php echo yii::t('app','临时座');?>">
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green-stripe" id="btnswitchsite" value="<?php echo yii::t('app','转台>');?>">
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green-stripe" id="btnunionsite" value="<?php echo yii::t('app','并台>');?>">
			<input style="margin:-10px 0 10px 0;" type="button" class="btn green-stripe" id="btnclosesite" value="<?php echo yii::t('app','撤台>');?>">
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
                <div class="col-md-7" style="">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="tempsaveprint_btn" value="<?php echo yii::t('app','挂单打印');?>">
                        <input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="tempsave_btn" value="<?php echo yii::t('app','挂单');?>">
			<!--<input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="printlist_btn" value="<?php echo yii::t('app','打印清单');?>">-->
			<input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="alltaste_btn" value="<?php echo yii::t('app','全单设定');?>">
			<input style="margin:-10px 10px 10px 0;float:right;" type="button" class="btn blue" id="printerKitchen" value="<?php echo yii::t('app','下单&厨打&收银&结单');?>">
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	
	<div class="row">
		
                <div class="col-md-4">
                        <div class="navigation" id="orderdetailauto" style="">
                            <ul orderid="0000000000" class="selectProduct">
                                <span id="order_create_at">2004/12/12 12:00:00</span>
                                <li lid="0000000000" class="selectProductA">                                    
                                        已付<span id="order_reality_pay">0</span>元/应付<span id="order_should_pay">0</span>元
                                </li>    
                            </ul>
                            全单设定：
                            <span id="ordertasteall" tid=""></span>
                            <span id="ordertastememoall"></span>
                            
                        </div>
                        <div class="" id="productInfo" style="display: none;">
                                <div style="margin:0.5em;">
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanLid"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanProductId"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanIsRetreat"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanOrderStatus"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanProductDiscountOrig"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanNowPriceOrig"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanTasteIds"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;display: none;" id="spanTasteMemo"></span>
                                    <span style="font-size:2.0em;margin-left:1.0em;" id="spanProductName">菜品名称</span>
                                    <input style="float:right;margin-right:2.0em;" type="button" class="btn green" id="btn-reminder" value="<?php echo yii::t('app','催菜');?>">
                                    <input style="float:right;margin-right:1.0em;" type="button" class="btn green" id="btn-return" value="<?php echo yii::t('app','转菜');?>">
                               <!--    <input style="float:right;margin-right:1.0em;" type="button" class="btn green" id="btn-retreat" value="<?php echo yii::t('app','退菜');?>">-->
                                    <input style="float:right;margin-right:3.0em;" type="button" class="btn green" id="btn-reprint" value="<?php echo yii::t('app','厨打');?>">-->
                                </div>
                                <div style="float:left;width:65%;">
                               		 <div style="float:left;width:96%;margin:1px 5px 5px 10px;padding:8px;border:1px solid red;">
                                             <div style="float:left;font-size:1.5em;width:40%;"><?php echo yii::t('app','原价：');?><span id="spanOriginPrice">0.00</span></div>
                                             <div style="float:left;font-size:1.5em;width:20%;"><div style="width:100%;"><?php echo yii::t('app','数量：');?><span id="spanNumber">1</span></div></div>
                                            <div style="float:left;font-size:1.5em;width:40%;"><?php echo yii::t('app','现价：');?><span id="spanNowPrice">0.00</span></div>
                                            <div style="float:left;font-size:1.5em;width:100%;">
                                                优惠：
                                                <div style="width:50%;" class="btn-group" data-toggle="buttons" style="margin: 5px;border: 1px solid red;background: rgb(245,230,230);">
                                                    <label style="margin:5px;float: right;" id="checkboxDiscount" class="selectDiscount btn btn-default active"><input type="checkbox" class="toggle"> 折扣</label>
                                                    <label style="margin:5px;float: right;" id="checkboxMinus" class="selectDiscount btn btn-default"><input type="checkbox" class="toggle"> 减价</label>
                                                    <label style="margin:5px;float: right;" id="checkboxGiving" class="selectDiscount btn btn-default"><input type="checkbox" class="toggle"> 赠送</label>
                                                </div>
                                                <div style="float:right;font-size:1.5em;width:30%;"><div style="width:100%;background-color: #78ccf8;text-align: right;"><span id="spanProductDiscount">%</span></div></div>
                                            
                                            </div>    
	                            	 </div>
                                 <DIV style="width:96%;height:175px;margin:1px;border:0px solid green;">
 	                            <div style="margin-left:10px;border:0px solid red;" class="calc_dan">
                                      <ul style="padding:5px 20px 5px ;">
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
                                    <div style="margin:3px;border:0px solid green;" class="dan_button">
                                     <ul style="padding:0px;">
                                       <li id="cl_one" style="background-color: #add"><?php echo yii::t('app','退格');?></li>
                                       <li id="cl_all" style="background-color: red"><?php echo yii::t('app','清除');?></li>
                                       <li id="product_yes" style="background-color: #0099FF"><?php echo yii::t('app','确认');?></li>
                                       <li id="product_close"><?php echo yii::t('app','取消');?></li>
                                     </ul>
                                    </div>	                            
	                        </DIV> 	                            	 
	                    </div>
                                <div style="float:left;width:33%;margin:1px;border:1px solid red;">
                                <div id="productstatus" style="width:100%;">
                                	<div style="float:left;height:2.0em;width:100%;line-height:2.0em;font-size:1.5em;" class="btn-group" data-toggle="buttons"><?php echo yii::t('app','状态：');?>  </div>
                                	<div class="btn-group" data-toggle="buttons" style="margin: 5px;border: 1px solid red;background: rgb(245,230,230);">
                                		<label style="margin:5px;float: right;" group="status" id="checkboxNow" class="checkboxNow btn btn-default active"><input type="radio" value="0" class="toggle"><?php echo yii::t('app','即起');?></label>
                                        <label style="margin:5px;float: right;" group="status" id="checkboxWait" class="checkboxNow btn btn-default "><input type="radio" value="1" class="toggle"><?php echo yii::t('app','等叫');?></label>
                                        <label style="margin:5px;float: right;" group="status" id="checkboxHurry" class="checkboxNow btn btn-default "><input type="radio" value="2" class="toggle"><?php echo yii::t('app','加急');?></label>
                                    </div>
                                </div>
                                    <div style="float:left;height:2.0em;width:100%;line-height:2.0em;font-size:1.5em;"><?php echo yii::t('app','口味：');?></div>
                                    <div id="productTaste" style="width:100%;">

                                    </div>
                                </div>
	                            <!-- <div style="float:left;height:2.0em;width:100%;line-height:2.0em;font-size:1.5em;" class="btn-group" data-toggle="buttons"><?php echo yii::t('app','状态：');?>  
                                		<label style="margin:5px;float: right;" id="checkboxNow" class="checkboxNow btn btn-default active"><input type="radio" class="toggle"><?php echo yii::t('app','即起');?></label>
                                        <label style="margin:5px;float: right;" id="checkboxWait" class="checkboxNow btn btn-default "><input type="radio" class="toggle"><?php echo yii::t('app','等叫');?></label>
                                        <label style="margin:5px;float: right;" id="checkboxHurry" class="checkboxNow btn btn-default "><input type="radio" class="toggle"><?php echo yii::t('app','加急');?></label>
                                    </div>
                                 -->
                            </div>
                </div>	
            
                <div class="col-md-8">			
                        <div class="tabbable tabbable-custom">
                            <div class="firstCategory">
                                <ul class="">
                                        <li lid="productfind" class="tabProduct">查找</li>
                                        <li lid="productset" class="tabProduct">套餐</li>
                                        <?php 
                                        foreach ($categories as $categorie): 
                                            if($categorie->pid=="0000000000"):?>
                                            <li lid="<?php echo $categorie->lid; ?>" class="tabProduct"><?php echo $categorie->category_name; ?></li>
                                        <?php 
                                            endif;
                                        endforeach; ?>                                       
                                        
                                </ul>
                            </div>
                                    <div class="tab-content" style="display:none;" lid="productfind">                                        
                                        <div style="width:100%;height:100%;">
                                            <div class="product_list">
                                                <div class="alphabet">                                                
                                                    <ul>
                                                        <li id="alphabetlist" style="width:180px;background-color:red;" deal="none"></li>
                                                        <li deal="A">A</li><li deal="A">B</li><li deal="A">C</li><li deal="A">D</li><li deal="A">E</li><li deal="A">F</li><li deal="A">G</li>
                                                        <li deal="A">H</li><li deal="A">I</li><li deal="A">J</li><li deal="A">K</li><li deal="A">L</li><li deal="A">M</li><li deal="A">N</li>
                                                        <li deal="A">O</li><li deal="A">P</li><li deal="A">Q</li><li deal="A">R</li><li deal="A">S</li><li deal="A">T</li>
                                                        <li deal="A">U</li><li deal="A">V</li><li deal="A">W</li><li deal="A">X</li><li deal="A">Y</li><li deal="A">Z</li>
                                                        <li deal="del" style="width:100px;background-color:#0a0;">删除</li>
                                                    </ul>
                                                </div>
                                                <ul class="">
                                                    <!--productset list;-->
                                                    <?php 
                                                        foreach ($productSets as $productSet): 
                                                            $setdetail="";
                                                                foreach ($productSet->productsetdetail as $psd):
                                                                    if(!empty($pn[$psd->product_id]))
                                                                    {
                                                                        $tempdetail="gp".$psd->group_no.",".$psd->product_id.",".$psd->is_select.",".$psd->number.",".$psd->price.",".$pn[$psd->product_id];
                                                                        if(empty($setdetail))
                                                                            $setdetail=$tempdetail;
                                                                        else
                                                                            $setdetail.=";".$tempdetail;   
                                                                    }
                                                                endforeach;
                                                            ?>
                                                            <li class="productSetClick" search="search" lid="<?php echo $productSet->lid; ?>" simplecode="<?php echo $productSet->simple_code;?>" setselect="<?php echo $setdetail; ?>" store="<?php echo $productSet->store_number; ?>" price="<?php echo $setprice[$productSet->lid]; ?>"><?php echo $productSet->set_name; ?>(<?php echo $setprice[$productSet->lid]; ?>)</li>                                                                    
                                                    <?php                                                         
                                                    endforeach; ?>
                                                   <!-- product list -->
                                                   <?php 
                                                        foreach ($categories as $categorie2): 
                                                            //if($categorie2->pid==$categorie->lid):?>
                                                            <?php 
                                                                foreach ($products as $product): 
                                                                    if($product->is_show=="1" and $product->category_id==$categorie2->lid):?>
                                                                    <li class="productClick" search="search" lid="<?php echo $product->lid; ?>" simplecode="<?php echo $product->simple_code;?>" store="<?php echo $product->store_number; ?>" price="<?php echo $product->original_price; ?>" name="<?php echo $product->product_name; ?>"><?php echo $product->product_name; ?>(<?php echo $product->original_price; ?>)</li>                                                                    
                                                            <?php  endif;                                                         
                                                            endforeach; ?>
                                                    <?php 
                                                        //endif;
                                                    endforeach; ?>
                                                </ul>
                                            </div>                                        												
                                        </div>
                                    </div>
                                    <div class="tab-content" style="display:none;" lid="productset">                                        
                                        <div style="width:100%;height:100%;">
                                            <div class="product_list">
                                                <ul class="">
                                                    <?php 
                                                        foreach ($productSets as $productSet): 
                                                            $setdetail="";
                                                                foreach ($productSet->productsetdetail as $psd):
                                                                    if(!empty($pn[$psd->product_id]))
                                                                    {
                                                                        $tempdetail="gp".$psd->group_no.",".$psd->product_id.",".$psd->is_select.",".$psd->number.",".$psd->price.",".$pn[$psd->product_id];
                                                                        if(empty($setdetail))
                                                                            $setdetail=$tempdetail;
                                                                        else
                                                                            $setdetail.=";".$tempdetail;   
                                                                    }
                                                                endforeach;
                                                            ?>
                                                            <li class="productSetClick" lid="<?php echo $productSet->lid; ?>" setselect="<?php echo $setdetail; ?>" store="<?php echo $productSet->store_number; ?>" price="<?php echo $setprice[$productSet->lid]; ?>"><?php echo $productSet->set_name; ?>(<?php echo $setprice[$productSet->lid]; ?>)</li>                                                                    
                                                    <?php                                                         
                                                    endforeach; ?>                                                    
                                                </ul>
                                            </div>                                        												
                                        </div>
                                    </div>
                                <?php 
                                foreach ($categories as $categorie): 
                                    if($categorie->pid=="0000000000"):?>
                            <div class="tab-content" style="display:none;" lid="<?php echo $categorie->lid; ?>">                                        
                                        <div style="width:100%;height:100%;">
                                            <div class="product_list">
                                                <ul class="">
                                                    <?php 
                                                        foreach ($categories as $categorie2): 
                                                            if($categorie2->pid==$categorie->lid):?>
                                                        <li style="width:2.2em;background-color: #add" lid="<?php echo $categorie2->lid; ?>"><?php echo $categorie2->category_name; ?></li>
                                                            <?php 
                                                                foreach ($products as $product): 
                                                                    if($product->is_show=="1" and $product->category_id==$categorie2->lid):?>
                                                                    <li class="productClick" lid="<?php echo $product->lid; ?>" store="<?php echo $product->store_number; ?>" price="<?php echo $product->original_price; ?>" name="<?php echo $product->product_name; ?>"><?php echo $product->product_name; ?>(<?php echo $product->original_price; ?>)</li>                                                                    
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
            <!--------pay box begin-------->
                                    <div id="accountbox" style="display:none;">
                                        <div>
                                            <div style="width: 95%;margin:1.0em;font-size: 1.5em;">
                                                    <DIV style="float:left;width:27%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','总额');?><span id="payOriginAccount">10000.23</span></DIV>
                                                    <DIV id="payDiscountAccountDiv" class="edit_span" selectid="discount" style="float:left;width:15%;background-color:#9acfea;"><?php echo yii::t('app','折扣');?><span id="payDiscountAccount" disid="0000000000" disnum="1" dismoney="0.00">100%</span></DIV>
                                                    <DIV class="edit_span" selectid="minus" style="float:left;width:20%;background-color:#9acfea;"><?php echo yii::t('app','优惠');?><span id="payMinusAccount">0</span></DIV>
                                                    <DIV class="" id="cancel_zero" style="float:left;width:10%;background-color:#9acfea;"><?php echo yii::t('app','抹零');?><span id="payCancelZero" style="display:none;">0</span></DIV>
                                                    <DIV style="float:left;width:27%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','应付');?><span id="payShouldAccount">10000.23</span></DIV>
                                            </div>
                                            
                                            <div style="float: left;width:73%;margin-top: 2.0em;">
                                                <DIV style="float:left;width:100%;border:0px solid red;">
                                                 <div style="margin-left:0px;vertical-align: top;border:0px solid red;" class="calc_num">
                                                     <ul>
                                                         <li>1</li>
                                                         <li>2</li>
                                                         <li>3</li>
                                                         <li>10</li>
                                                         <li>4</li>
                                                         <li>5</li>
                                                         <li>6</li>
                                                         <li>20</li>
                                                         <li>7</li>
                                                         <li>8</li>
                                                         <li>9</li>
                                                         <li>50</li>
                                                         <li>0</li>
                                                         <li>00</li>
                                                         <li>.</li>
                                                         <li>100</li>
                                                     </ul>
                                                 </div>
                                                <div style="margin:0px;margin-top: -0px;border:0px solid green;" class="calc_button">
                                                    <ul style="padding-left:0px;margin-top:-20px;">
                                                        <li id="pay_clearone" style="background-color: #add"><?php echo yii::t('app','退格');?></li>
                                                        <li id="pay_clearall" style="background-color: red"><?php echo yii::t('app','清除/全额');?></li>
                                                        <li id="pay_btn" style="background-color: #0099FF"><?php echo yii::t('app','收银');?></li>    
                                                        <li id="printlistaccount" class="default" style="background-color: #00FFFFFF"><?php echo yii::t('app','打印预结单');?></li>
                                                        <li id="layer2_close" class="default" style="background-color: #0a0"><?php echo yii::t('app','关闭');?></li>
                                                    </ul>
                                                </div> 
                                              </DIV> 
                                            </div>
                                            <div style="float: left;width:25%;height: 100%;">
                                                <div style="width: 85%;margin:1.0em;font-size:1.5em;height: 100%;">
                                                    实收<span style="text-align:right;" id="payRealityAccount">0.00</span><br>
                                                    找零<span style="text-align:right;" id="payChangeAccount">0.00</span><br>
                                                    <DIV class="edit_span edit_span_normal edit_span_select" selectid="pay_cash" style="float:left;width:100%;background-color:#9acfea;padding:10px;"><?php echo yii::t('app','现金');?><span id="payCashAccount">0.00</span></DIV>
                                                    <DIV class="edit_span edit_span_normal" selectid="pay_union_card" style="float:left;width:100%;background-color:#9acfea;padding:10px;"><?php echo yii::t('app','银联卡');?><span style="text-align:right;" id="payUnionAccount">0.00</span></DIV>
                                                    <DIV class="edit_span edit_span_normal" selectid="pay_member_card" style="float:left;width:100%;background-color:#9acfea;padding:10px;"><?php echo yii::t('app','会员卡');?><span  style="text-align:right;" cardno="0000000000" cardtotal="0.00" id="payMemberAccount">0.00</span></DIV>
                                                    <?php 
                                                    $otherdetail="0000000000,0";
                                                    if(!empty($paymentmethod))
                                                    {
                                                        foreach($paymentmethod as $method)
                                                        {
                                                             $otherdetail=$otherdetail."|".$method->lid.",0.00";                                                             
                                                         }
                                                    }?>
                                                    <?php 
                                                    if(!empty($otherdetail)):                                                        
                                                    ?>
                                                    <DIV class="edit_span_show" selectid="pay_others" style="float:left;width:100%;background-color:#006dcc;padding:10px;">其他<span detail="<?php echo $otherdetail;?>" detailorigin="<?php echo $otherdetail;?>" style="text-align:right;" id="payOthers" >0.00</span></DIV>                                                    
                                                    <?php endif;?>
                                                    <?php
                                                    if(!empty($paymentmethod)):
                                                         foreach($paymentmethod as $method):?>
                                                    <DIV class="edit_span edit_span_other edit_span_hide" selectid="pay_others_detail" spanid="<?php echo 'paymethod'.$method->lid; ?>" style="float:left;width:100%;background-color:#9acfea;padding:10px;"><?php echo $method->name;?><span  style="text-align:right;" id="<?php echo 'paymethod'.$method->lid; ?>" lid="<?php echo $method->lid; ?>">0.00</span></DIV>
                                                    <?php endforeach;
                                                    endif;?>
                                                </div>    
                                                <!--<input style="position:absolute;right:3%;bottom: 4%;width:6.0em;height:3.0em;" type="button" class="btn green" id="layer2_close" value="<?php echo yii::t('app',' 关 闭 ');?>">-->
                                            </div>
                                        </div>
                                    </div>
            </div>
        </div>
            <!-------------site----------->
            <div class="row" id="site_row" style="display:block;">
                <div class="col-md-12">
                    <div class="tabbable tabbable-custom">
                        <div class="firstCategory">
                            <ul class="">
                            <?php if($siteTypes):?>
                            <?php foreach ($siteTypes as $key=>$siteType):?>
                                    <li typeId="<?php echo $key ;?>" class="tabSite <?php if($key == $typeId) echo 'slectliclass';?>"><?php echo $siteType ;?></li>
                            <?php endforeach;?>
                            <?php endif;?>
                               <!--     <li typeId="tempsite" class="tabSite <?php if($typeId == 'tempsite') echo 'slectliclass';?>"><?php echo yii::t('app','临时座');?></li>-->
                                <li typeId="others" class="tabSite <?php if($typeId == 'others') echo 'slectliclass';?>">其他</li>
                            </ul>
                        </div>
                            <div class="tab-content" id="tabsiteindex">
                                     <!-- END EXAMPLE TABLE PORTLET-->												
                            </div>
                        
                    </div>
		</div>
            </div>
            <!---------------taste------------------>
            <div id="tastebox" style="display: none">
                
            </div>
            <!---------------productsetselect------------------>
            <div id="productsetselect" style="display: none">                
                <div  style="margin:10px;">
                    <input style="width:7.0em;" type="button" class="btn green" id="productset_select_sure" value="确定">
                    <input style="width:7.0em; float: right;" type="button" class="btn gray" id="productset_select_cancel" value="取消">
                </div>
                <div id="product-set-detail" style="margin:10px;">
                    
                </div>                
            </div>
            <!---printRsultList printresult -->
            <div id="printRsultList" style="display: none">
                <div style="margin:10px;">
                <h4 id="printalljobs"></h4>
                <span style="color:red;" id="minustimes">30</span><?php echo yii::t('app','秒倒计时...');?></br>
                <span style="color:red;" id="successnumid">0</span><?php echo yii::t('app','...个菜品厨打已经成功');?></br>
                <span style="color:red;" id="notsurenumid">0</span><?php echo yii::t('app','...个菜品正在打印');?></br>
                <span style="color:red;" id="errornumid">0</span><?php echo yii::t('app','...个菜品厨打失败，');?></br></br>
                <?php echo yii::t('app','有打印失败的菜品，请重新厨打。');?><br><br>
                <div style="text-align:center;">
                    <input type="button" class="btn green" id="print_box_close" value="<?php echo yii::t('app','确 定');?>">
                </div>
                </div>
            </div>
            <!---printRsultList printresult -->
                        
            <div id="printRsultListdetail" style="display: none">
                <div style="margin:10px;">
                    <h4 style="color:#900;"><span id="failprintjobs">0</span>个任务打印失败</h4>
                <!--<textarea id="printjobresultlist" style="width:90%;height:16.0em;margin:10px;" value="">                    
                </textarea>-->
                <div style="text-align:center;margin: 10px;">
                    <input type="button" class="btn green-stripe" id="print_box_close_failjobs" value="<?php echo yii::t('app','关 闭');?>">
                    <input type="button" style="margin-left: 20px;" class="btn green" id="print_box_account_direct" value="<?php echo yii::t('app','直接结单');?>">
                </div>
                <div class="navigation" id="printRsultListdetailsub">
                    <ul>
                        <li>                                    
                            任务222打印失败，打印机IP(192.168.1.37)
                            <input style="float:right;" type="button" class="btn red" value="重新打印">
                        </li>
                        
                    </ul>
                </div>
                
                </div>
            </div>
            <!---membercardInfo  -->
            <div id="membercardInfo" style="display:none;">
                <div style="width: 95%;margin:1.0em;font-size: 1.5em;">
                        <DIV style="float:left;width:95%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','还欠:');?><span id="card_pay_span_money">10000.23</span></DIV>
                        <DIV class="member_card_div edit_span_select_member" style="float:left;width:95%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','会员卡支付：');?><span id="card_pay_span_should" actual=""></span></DIV>
                        <DIV class="member_card_div" style="float:left;width:95%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','请刷卡');?><span id="card_pay_span_card" actual=""></span></DIV>
                        <DIV class="member_card_div" style="float:left;width:95%;font-size: 1.5em;border:1px solid red;"><?php echo yii::t('app','密码');?><span id="card_pay_span_password" actual=""></span></DIV>
                </div>
                <div style="text-align:center;width: 95%;margin:1.0em;">
                    <input style="margin:1.0em;" type="button" class="btn green" id="member_card_pay" value="<?php echo yii::t('app','确 定');?>">
                    <input style="margin:1.0em;" type="button" class="btn green" id="member_card_pay_close" value="<?php echo yii::t('app','取 消');?>">
                </div>
            </div>
            <!---------------退菜box------------------>
            <input type="hidden" value="0" id="selectproductnumfordelete">
            <div id="retreatbox" style="display: none">
                
            </div>
            <!---------------折扣类型选择------------------>
            <div id="alldiscountselect" style="display: none">
                
            </div>
            <!---------------结单确认框------------------>
            <div id="orderaccountsure" style="display: none">
                
            </div>
        <script type="text/javascript">
            var gsid=0;
            var gistemp=0;
            var gtypeid=0;
            var gssid=0;
            var gsistemp=0;
            var gstypeid=0;
            var gop=0;
            var tabcurrenturl="";
            //var tabcurrentlid="";
            var layer_index1=0;
            var layer_index2=0;
            var layer_index3=0;
            var layer_productset_click=0;
            var layer_order_partial=0;
            var layer_pay_others=0;
            //var layer_index_account;
            var layer_index_printresult=0;
            var layer_index_membercard=0;
            var layer_index_retreatbox=0;
            var layer_index_selectalldiscount=0;
            var layer_index_orderaccountsure=0;
            var first_tab="<?php echo empty($categories)?"0":$categories[0]['lid']; ?>";
            var ispaybuttonclicked=false;
            var intervalQueueList;
            var reloadsitestatelock=false;
            var public_account_sendjson="";
            //var member_card_pop_flag=0;
            if (typeof Androidwymenuprinter == "undefined") {
                event_clicktouchstart="click";
                event_clicktouchend="click";
            }else{
                event_clicktouchstart="touchstart";
                event_clicktouchend="touchend";
            }
            //alert(event_clicktouchstart);
            
            function reloadsitestate()
            {
                if(reloadsitestatelock)
                {
                    return;
                }
                reloadsitestatelock=true;
                //site显示时才做这样的操作
                if($("#tab_sitelist").css("display")=="block" || (gtypeid!="others" && gtypeid!="tempsite"))
                {
                    //alert(111);
                    var padid="0000000046";
                    if (typeof Androidwymenuprinter == "undefined") {
                        //alert("找不到PAD设备");
                        //return false;
                    }else{
                        var padinfo=Androidwymenuprinter.getPadInfo();
                        padid=padinfo.substr(10,10);
                    }
                    $.ajax({
                        url:"/wymenuv2/admin/defaultSite/getSiteAll/companyId/<?php echo $this->companyId; ?>/typeId/"+gtypeid+"/padId/"+padid,
                        type:'GET',
                        timeout:1000,
                        cache:false,
                        async:false,
                        dataType: "json",
                        success:function(msg){
                            //$('#tabsiteindex').load(tabcurrenturl);
                            //重新修改成用ajax动态加载
                            if(gtypeid=="others")
                            {
                                //获取排队信息，并更新状态,不存在删减的
                                $.each(msg.models,function(key,value){
                                    var siteobj=$(".modalaction[typeid='others'][sid="+value.splid+"][istemp="+value.typeid+"]");
                                    siteobj.removeClass("bg-yellow");
                                    siteobj.removeClass("bg-green");                                                    
                                    //改变背景颜色///
                                    if(value.queuepersons>0)
                                    {                                                
                                        if(value.sitefree>0)
                                        {
                                            siteobj.addClass("bg-green");                                                    
                                        }else{
                                            siteobj.addClass("bg-yellow");                                                    
                                        }
                                    }
                                    //修改排队数和空位数文字..
                                    if(value.sitefree==null)
                                    {
                                        value.sitefree=0;
                                    }
                                    if(value.queuepersons==null)
                                    {
                                        value.queuepersons=0;
                                    }
                                    siteobj.find("span[typename='sitefree']").text("空座:"+value.sitefree);
                                    siteobj.find("span[typename='queuenum']").text("排队:"+value.queuepersons); 
                                 });
                            }else if(gtypeid=="tempsite"){
                                //获取临时座位信息，并更新状态
                                //存在删减临时座位的,暂不修改，以后添加！！                    
                                //....
                            }else{
                                //获取座位信息，并更新状态
                                //不存在删减座位的
                                if($("#tab_sitelist").css("display")=="block")
                                {
                                    $.each(msg.models,function(key,value){
                                        var siteobj=$(".modalaction[typeid="+value.type_id+"][sid="+value.lid+"][istemp=0]");                                        
                                        var nowstatus=value.min_status;
                                        if(value.min_status=="1" || value.status=="1")
                                        {
                                            nowstatus=1;
                                        }
                                        siteobj.attr("status",nowstatus);
                                        siteobj.attr("maxstatus",value.max_status);
                                        siteobj.find("span[typename=updateat]").html("<br>"+value.update_at.substr(5,11));
                                        siteobj.removeClass("bg-yellow");
                                        siteobj.removeClass("bg-blue");
                                        siteobj.removeClass("bg-green");
                                        if(value.min_status=="1" || value.status=="1")
                                        {
                                            siteobj.addClass("bg-yellow");
                                        }else if(value.min_status=="2")
                                        {
                                            siteobj.addClass("bg-blue");
                                        }else if(value.min_status=="3")
                                        {
                                            siteobj.addClass("bg-green");
                                        }
                                        if("12".indexOf(value.order_type)>=0
                                                && ("123".indexOf(value.min_status)>=0))
                                        {
                                            siteobj.find("div").show();
                                        }else{
                                            siteobj.find("div").hide();
                                        }
                                        if(value.newitem > 0)
                                        {
                                            siteobj.find("div").css("background-color","green");
                                            //需要打印
                                            //然后再打印本机器
                                        }else{
                                            siteobj.find("div").css("background-color","");
                                        }
                                    });
                                }
                                //开始打印任务
                                //alert("34234");
                                var printresult=false;
                                var successjobs="00000000";
                                if(typeof(Androidwymenuprinter)=="undefined")
                                {
                                    //return;
                                }
                                var times=0;
                                $.each(msg.ret9arr,function(key,value){
                                    alert(value);
                                    setTimeout("Androidwymenuprinter.ordercall('"+value+"')", 6000*times+1000 );
                                    times++;
                                });
                                $.each(msg.ret8arr,function(key,value){
                                    alert(value);
                                    setTimeout("Androidwymenuprinter.paycall('"+value+"')", 6000*times+1000 );
                                    times++;
                                });
//                                $.each(msg.modeljobs,function(key,value){
//                                    printresult=false;
//                                    for(var itemp=1;itemp<4;itemp++)
//                                    {
//                                        if(printresult)
//                                        {
//                                            successjobs=successjobs+","+value.jobid;
//                                            break;
//                                        }
//                                        var addressdetail=value.address.split(".");
//                                        if(addressdetail[0]=="com")
//                                        {
//                                            var baudrate=parseInt(addressdetail[2]);
//                                            printresult=Androidwymenuprinter.printComJob(value.dpid,value.jobid,addressdetail[1],baudrate);
//                                        }else{
//                                            printresult=Androidwymenuprinter.printNetJob(value.dpid,value.jobid,value.address);
//                                            //printresult=true;
//                                        }                                                                        
//                                    }
//                                });
//                                //alert(successjobs);
//                                if("00000000"!=successjobs)
//                                {
//                                    $.ajax({
//                                        url:"/wymenuv2/admin/defaultSite/finshPauseJobs/companyId/<?php echo $this->companyId; ?>/successjobs/"+successjobs,
//                                        type:'GET',
//                                        timeout:2000,
//                                        cache:false,
//                                        async:false,
//                                        dataType: "json",
//                                        success:function(msg){
//
//                                        }
//                                    });
//                                }
                            }                            
                        },
                        error: function(msg){
                            //alert("网络可能有问题，再试一次！");
                        },
                        complete : function(XMLHttpRequest,status){
                            if(status=='timeout'){
                               // alert("网络可能有问题，再试一次！");                                            
                            }
                        }
                    });               
                }
                reloadsitestatelock=false;
                //setTimeout(reloadsitestate,"15000");
            }
            
            
            $(document).ready(function() {
                $('body').addClass('page-sidebar-closed');
                //$('.nav-tabs').find('li[lid='+first_tab+']').addClass("slectliclass");
                $('.firstCategory').find('li[lid='+first_tab+']').addClass("slectliclass");
                $('.tab-content[lid='+first_tab+']').show();
                //tabcurrentlid=first_tab;
                gtypeid="<?php echo $typeId; ?>"
                //tab-content
                tabcurrenturl='<?php echo $this->createUrl('defaultSite/showSiteAll',array('typeId'=>$typeId,'companyId'=>$this->companyId));?>';
                $('#tabsiteindex').load(tabcurrenturl);
                //clearInterval(intervalQueueList);
                //intervalQueueList = setInterval(reloadsitestate,"15000");
                //setTimeout(reloadsitestate,"15000");
            });
            
            function sitevisible()
            {
                $('#pxbox_button').hide();
                $('#tabsiteindex').show();
                $("#tab_sitelist").show();
                $('#site_row').show();
                $('#order_row').hide();
            }           
            
            
            $('.tabSite').on(event_clicktouchstart, function(){
                $('.tabSite').removeClass('slectliclass');
                $(this).addClass('slectliclass');
                var typeId=$(this).attr('typeid');
                gtypeid=typeId;
                $('.modalaction').css('display','none');
                $('.modalaction[typeid='+gtypeid+']').css('display','block');

            });
            
            $('.tabProduct').on(event_clicktouchstart, function(){
                $('.tabProduct').removeClass('slectliclass');
                $(this).addClass('slectliclass');
                var lid=$(this).attr('lid');
                
                    $('.tab-content').hide();
                    $('.tab-content[lid='+lid+']').show();
                
            });
            
            $('.productSetClick').on(event_clicktouchstart, function(){
                    var setselect=$(this).attr("setselect");                    
                    $("#product-set-detail").remove();
                    $("#productsetselect").append("<div id='product-set-detail'  style='margin:10px;' setid="+$(this).attr("lid")+"></div>");
                    $.each(setselect.split(";"),function(setkey,setvalue){
                        //$psd->group_no."|".$psd->product_id.name"|".$psd->is_select."|".$psd->number."|".$psd->price;
                        var setdetail=setvalue.split(",");
                        var instr="";
                        var active="";
                        var btngroup=$("#product-set-detail").find("div[groupid='"+setdetail[0]+"'][class='btn-group']");
                        if(setdetail[2]=="1")
                        {
                            active="active";
                        }
                        if(typeof btngroup.attr("groupid")=="undefined")
                        {
                            instr='<div class="btn-group" groupid='+setdetail[0]+ ' data-toggle="buttons" style="float:left;width:45%;margin-top:2px;margin-right:10px;border: 2px solid red;background: rgb(245,230,230);"> '                                                                                       
                                        +'<label style="width:95%;margin-right: 2px;margin-left:2px;" productid="'+setdetail[1]+ '" price="'+setdetail[4]+'" pname="'+setdetail[5]+'" number="'+setdetail[3]+'" class="selectSetProduct btn btn-default '+active+'">'
                                           +' <input type="checkbox" class="toggle">' +setdetail[5]+"  "+setdetail[3]+" X "+setdetail[4]
                                        + '</label>'                                                                                    
                                    + '</div>';
                            $("#product-set-detail").append(instr);
                        }else{
                            instr='<label style="width:95%;margin-right: 2px;margin-left:2px;" productid="'+setdetail[1]+ '" price="'+setdetail[4]+'" pname="'+setdetail[5]+'" number="'+setdetail[3]+'" class="selectSetProduct btn btn-default '+active+'">'
                                           +' <input type="checkbox" class="toggle">' +setdetail[5]+"  "+setdetail[3]+" X "+setdetail[4]
                                        + '</label>';
                            btngroup.append(instr);
                        }
                    });
                    if(layer_productset_click!=0)
                    {
                        return;
                    }
                    //alert(layer_index3);
                    layer_productset_click=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['40%', 'auto'],
                     content: $('#productsetselect'),//$('#productInfo'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
                         layer_productset_click=0;
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                 });
             });
            
            $('#productset_select_cancel').on('click',function(){
                layer.close(layer_productset_click);
                layer_productset_click=0;
            });
            
            $('#productset_select_sure').on('click',function(){
                var setid=$("#product-set-detail").attr("setid");
                var lid="";
                var price="";
                var pname="";
                var number="";
                $.each($("#product-set-detail").find(".selectSetProduct.active"),function(skey,sobj){
                    lid=sobj.getAttribute("productid");
                    price=sobj.getAttribute("price");
                    pname=sobj.getAttribute("pname");
                    number=sobj.getAttribute("number");
                    addProductInTempOrder(setid,lid,price,pname,number);
                });  
                //$("#productTempOrderNum").val(parseInt($("#productTempOrderNum").val())+1);
                layer.close(layer_productset_click);
                layer_productset_click=0;
            });
             
            function change0(word)
            {
                return word.substr(0,word.length-2)+"0,";
            }

            $('.selectSetProduct').live('click',"label",function(){
                $(this).parent().find("label").removeClass("active");
//                var groupno=$(this).parent().attr("groupid");
//                var productid=$(this).attr("productid");
//                var setid=$("#product-set-detail").attr("setid");
//                var objset=$('.productSetClick').find('lid['+setid+']');
//                var setselect=objset.attr("setselect");
//                var reg=groupno+",[0-9]{10},1,";
//                str = setselect.replace(new RegExp(reg,"g"),change0);
//                var reg2=groupno+","+productid+",0,";
//                str = str.replace(new RegExp(reg2,"g"),change1);
//                objset.attr("setselect",str);
            }); 
            
            function addProductInTempOrder(setid,lid,origin_price,pname,number)
            {
                var obj=$('.selectProductA[productid="'+lid+'"][order_status="0"][setid="'+setid+'"]');//.find('span[class="badge"]'); 
                var taotext="";
                if(setid!="0000000000")
                {
                    taotext="套";
                }
                if(typeof obj.attr("lid")== "undefined")
                {
                    var appendstr=' <li lid="0000000000"'
                                  +'      orderid="0000000000"'
                                  +'      setid="'+setid+'"'
                                  +'      productid="'+lid+'"'
                                  +'      order_status="0"' 
                                  +'      is_giving="0" '
                                  +'      product_status="0" '//添加cf
                                  +'      is_print="0" '
                                  +'       is_retreat="0"' 
                                  +'      tasteids="" tastememo=""' 
                                  +'      class="selectProductA">'
                                  +'  <span style="background-color:#005580;" class="special badge" content="">'+taotext
                                  +'      </span>'
                                  +'  <span style="font-size:1.3em !important;height:auto;" class="badge">'+number+'</span>'
                                  +'  <span class="selectProductPrice" style="color:#976125;display:none">'+origin_price+'</span>'
                                  +'  <span class="selectProductDiscount" style="color:#976125;display:none">100%</span>'
                                  +'      <span class="selectProductNowPrice" style="color:#976125">'+origin_price+'</span>'
                                  +'      <span style="position:absolute;" class="selectProductName">'+pname+'</span>'
                                  +'      <img class="selectProductDel" style="position: absolute;right:0.3em; width: 2.5em;height: 2.0em;padding:5px 10px 5px 10px;" '
                                  +'           src="<?php echo Yii::app()->request->baseUrl;?>/img/product/icon_cart_m.png"> '                                  
                                  +' </li>'
                    $(".selectProduct").append(appendstr);
                }else{
                    var curnum = parseFloat(obj.find('span[class="badge"]').text().replace(",",""))+parseFloat(number);                    
                    obj.find('span[class="badge"]').text(curnum);
                }
            }
            
            $('.productClick').on(event_clicktouchstart, function(){
                var origin_price=$(this).attr("price");
                var lid=$(this).attr("lid");
                var pname=$(this).attr("name");                
                addProductInTempOrder("0000000000",lid,origin_price,pname,1);
            });            
           
            
            function getallproductinfo()
            {
                //取得整体订单的tasteids tastememo
                var ordertasteids=$("#ordertasteall").attr("tid");
                var ordertastememo=$("#ordertastememoall").text();
                var orderlist=$(".selectProduct").attr("orderlist");
                var productlist="";
                var tempproduct="";
                //取得所有未下单状态的单品，没有打印和厨打都是0,1就不能修改了。
                $(".selectProductA[order_status='0']").each(function(){
                    tempproduct=$(this).attr("lid");
                    tempproduct=tempproduct+","+$(this).attr("orderid");
                    tempproduct=tempproduct+","+$(this).attr("setid");
                    tempproduct=tempproduct+","+$(this).attr("productid");
                    tempproduct=tempproduct+","+$(this).attr("order_status");
                    tempproduct=tempproduct+","+$(this).find("span[class='badge']").text();
                    tempproduct=tempproduct+","+$(this).find("span[class='selectProductDiscount']").text();
                    tempproduct=tempproduct+","+$(this).find("span[class='selectProductNowPrice']").text();
                    tempproduct=tempproduct+","+$(this).attr("product_status");
                    tempproduct=tempproduct+","+$(this).attr("is_giving");
                    tempproduct=tempproduct+","+$(this).attr("tasteids");
                    tempproduct=tempproduct+","+$(this).attr("tastememo");
                    tempproduct=tempproduct+","+$(this).find("span[class='selectProductPrice']").text();
                    
                    if(productlist!="")
                    {
                        productlist=productlist+";"+tempproduct;
                    }else{
                        productlist=tempproduct;
                    }                    
                });
                //包括单品列表、单品口味列表，口味备注等
                return '&productlist='+productlist+
                        '&ordertasteids='+ordertasteids+
                        '&ordertastememo='+ordertastememo+
                        '&orderlist='+orderlist;                 
            }
            
            
            $('#alltaste_btn').on(event_clicktouchstart,function(){
                    var tids=$("#ordertasteall").attr("tid");
                    $(".selectTaste").removeClass("active");
                    //alert(layer_index3);
                    $.each(tids.split("|"),function(index,data){
                        $(".selectTaste[tasteid="+data+"]").addClass("active");
                    });
                    $("#taste_memo_edit").val($("#ordertastememoall").text());
                    if(layer_index3!=0)
                    {
                        return;
                    }
                    //alert(layer_index3);
                    layer_index3=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['30%', 'auto'],
                     content: $('#tastebox'),//$('#productInfo'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
                         layer_index3=0;
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                 });
                 //alert(layer_index3);
            });
                        
            $('#tempsave_btn').on(event_clicktouchstart,function(){
                   //取得数据
                   var orderid=$(".selectProduct").attr("orderid");
                   //取得orderid
                    //var orderid=$(".selectProduct").attr("orderid");
                   //var orderstatus="1";
                   var sendjson=getallproductinfo();
                   alert(sendjson);return;
                   var url="<?php echo $this->createUrl('defaultOrder/orderPause',array('companyId'=>$this->companyId));?>/orderid/"+orderid+"/orderstatus/1";
                   var index = layer.load(0, {shade: [0.3,'#fff']});
                   $.ajax({
                    url:url,
                    type:'POST',
                    data:sendjson,
                    async:false,
	            dataType: "json",
	            success:function(msg){
                        var data=msg;
	                if(data.status){
                            layer.close(index);
                            $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId));?>/orderId/'+orderid);
                            alert("保存成功！");                            
                        }else{
                            layer.close(index);
                            alert(data.msg);
                        }
                    },
                    error: function(msg){
                        layer.close(index);
                        alert("保存失败2！");
                    }
	     	});
                   
            });
            
            //tempsaveprint_btn
            $('#tempsaveprint_btn').on(event_clicktouchstart,function(){
                var orderid=$(".selectProduct").attr("orderid");
                var orderList=$(".selectProduct").attr("orderlist");
                var padid="0000000046";
                if (typeof Androidwymenuprinter == "undefined") {
                    alert("找不到PAD设备");
                    //return false;
                }else{
                    var padinfo=Androidwymenuprinter.getPadInfo();
                    padid=padinfo.substr(10,10);
                }
                var url="<?php echo $this->createUrl('defaultOrder/pausePrintlist',array('companyId'=>$this->companyId));?>/orderId/"+orderid+"/padId/"+padid;
                $.ajax({
                        url:url,
                        type:'GET',
                        data:"",
                        async:false,
                        dataType: "json",
                        success:function(msg){
                            var data=msg;
                            var printresult=false;
                            if(data.status){
                                var index = layer.load(0, {shade: [0.3,'#fff']});
                                for(var itemp=1;itemp<4;itemp++)
                                {
                                    if(printresult)
                                    {
                                        layer.close(index);
                                        break;
                                    }
                                    var addressdetail=data.address.split(".");
                                    if(addressdetail[0]=="com")
                                    {
                                        var baudrate=parseInt(addressdetail[2]);
                                        printresult=Androidwymenuprinter.printComJob(data.dpid,data.jobid,addressdetail[1],baudrate);
                                    }else{
                                        printresult=Androidwymenuprinter.printNetJob(data.dpid,data.jobid,data.address);
                                    }                                                                        
                                }
                                if(!printresult)
                                {
                                    alert("再试一次！");
                                }
                                layer.close(index);
                            }else{
                                alert(data.msg);                                
                            }                                            
                        },
                        error: function(msg){
                            alert("保存失败2");
                        }
                    });                 
            })
            
            //printlist_btn
            $('#printlistaccount').on(event_clicktouchstart,function(){
                var orderid=$(".selectProduct").attr("orderid");
                var padid="0000000046";
                if (typeof Androidwymenuprinter == "undefined") {
                    alert("找不到PAD设备");
                    //return false;
                }else{
                    var padinfo=Androidwymenuprinter.getPadInfo();
                    padid=padinfo.substr(10,10);
                }
                //重新计算
                var payShouldAccount=$("#payShouldAccount").text();
                //var payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                var cardtotal=$('#payMemberAccount').attr("cardtotal");
                //会员卡的总额
                var url="<?php echo $this->createUrl('defaultOrder/orderPrintlist',array('companyId'=>$this->companyId));?>/orderId/"+orderid+"/padId/"+padid+"/payShouldAccount/"+payShouldAccount+"/cardtotal/"+cardtotal;
                $.ajax({
                        url:url,
                        type:'GET',
                        data:"",
                        async:false,
                        dataType: "json",
                        success:function(msg){
                            var waittime=0;
                            var data=msg;
                            //alert(data.jobid);
                            var printresult=false;
                            if(data.status){
                                var index = layer.load(0, {shade: [0.3,'#fff']});
                               for(var itemp=1;itemp<4;itemp++)
                                {
                                    if(printresult)
                                    {
                                        layer.close(index);
                                        break;
                                    }
                                    var addressdetail=data.address.split(".");
                                    if(addressdetail[0]=="com")
                                    {
                                        var baudrate=parseInt(addressdetail[2]);
                                        //       alert(baudrate);
                                        printresult=Androidwymenuprinter.printComJob(data.dpid,data.jobid,addressdetail[1],baudrate);
                                    }else{
                                        printresult=Androidwymenuprinter.printNetJob(data.dpid,data.jobid,data.address);
                                    }                                                                        
                                }
                                if(!printresult)
                                {
                                    //layer.close(index);
                                    alert("再试一次！");
                                }
                                layer.close(index);
                            }else{
                                alert(data.msg);                                
                            }
                           //以上是打印
                           //刷新orderPartial	                 
                        },
                        error: function(msg){
                            alert("保存失败2");
                        }
                    });                    
            });
            
//            $('#printerKitchen22').on(event_clicktouchstart, function(){
//                var orderid=$(".selectProduct").attr("orderid");
//                //var orderstatus="2";
////                //有新品
//                if($(".selectProductA[order_status='0']").length>0)
//                {
//                        //取得数据
//                        var sendjson=getallproductinfo();
//                        var url="<?php echo $this->createUrl('defaultOrder/orderKitchen',array('companyId'=>$this->companyId,"callId"=>"0"));?>/orderid/"+orderid+"/orderstatus/2";
//                        var statu = confirm("<?php echo yii::t('app','下单，并厨打，确定吗？');?>");
//                         if(!statu){
//                             return false;
//                         }                   
//                        $.ajax({
//                            url:url,
//                            type:'POST',
//                            data:sendjson,
//                            async:false,
//                            dataType: "json",
//                            success:function(msg){
//                                //保存成功，刷新
//                                $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId));?>/orderId/'+orderid);
//                                var data=msg;
//                                alert(data.msg);
//                                if(data.status){
//                                    //取得打印结果,在layer中定时取得
//                                    //alert(data.msg);
//                                    $("#printalljobs").text(data.msg);
//                                    if(layer_index_printresult!=0)
//                                        return;
//                                    layer_index_printresult=layer.open({
//                                         type: 1,
//                                         shade: false,
//                                         title: false, //不显示标题
//                                         area: ['30%', 'auto'],
//                                         content: $('#printRsultList'),//$('#productInfo'), //捕获的元素
//                                         cancel: function(index){
//                                             layer.close(index);
//                                             layer_index_printresult=0;
//                                             $("#minustimes").html(30);
//                                            $("#successnumid").html(0);
//                                            $("#errornumid").html(0);
//                                            $("#notsurenumid").html(0);
//                            //                        this.content.show();
//                            //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
//                                         }
//                                     });
//                                    var waitingsecond=30;
//                                    var interval=setInterval(function(){ 
//                                        $.get('<?php echo $this->createUrl('defaultOrder/printKitchenResultAll',array('companyId'=>$this->companyId));?>/orderId/'+orderid+'/timenum/'+waitingsecond,
//                                            function(data){
//                                                //waitingsecond--
//                                                //alert(data.notsurenum);
//                                                $("#minustimes").html(waitingsecond);
//                                                $("#successnumid").html(data.successnum);
//                                                $("#errornumid").html(data.errornum);
//                                                $("#notsurenumid").html(data.notsurenum);
//                                                if(data.finished && parseInt(data.errornum)==0 && parseInt(data.notsurenum)==0)
//                                                {
//                                                    //all success
//                                                    clearInterval(interval);
//                                                    layer.close(layer_index_printresult);
//                                                    layer_index_printresult=0;
//                                                    $("#minustimes").html(30);
//                                                    $("#successnumid").html(0);
//                                                    $("#errornumid").html(0);
//                                                    $("#notsurenumid").html(0);
//                                                    $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId));?>/orderId/'+orderid);
//                                                }
//                                                if(waitingsecond==0)
//                                                {   
//                                                    $("#notsurenumid").text(0);
//                                                    $("#errornumid").text(parseInt(data.errornum) + parseInt(data.notsurenum));                                                
//                                                    $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId));?>/orderId/'+orderid);
//                                                }
//                                            },'json'); 
//                                            waitingsecond--;
//                                            if(waitingsecond==0)
//                                            {   
//                                                clearInterval(interval);                                                
//                                            }
//                                    },1000);                                    
//                                }else{
//                                    alert(data.msg);
//                                    //alert("下单成功，打印失败");
//                                }
//                               //以上是打印
//                               //刷新orderPartial	                 
//                            },
//                            error: function(msg){
//                                alert("保存失败2");
//                            }
//                        });
//                }else{ //没有新品
//                    //设置总额
//                    var payOriginAccount=parseFloat($("#order_should_pay").text().replace(",",""));
//                    $("#payOriginAccount").text(payOriginAccount);
//                    var payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
//                    var payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
//                    $("#payShouldAccount").text((payOriginAccount*payDiscountAccount/100 - payMinusAccount).toFixed(2));
//                    if(layer_index2!=0)
//                    {
//                        return;
//                    }
//                    //出现收银界面
//                    layer_index2=layer.open({
//                         type: 1,
//                         shade: false,
//                         title: false, //不显示标题
//                         area: ['65%', '60%'],
//                         content: $('#accountbox'),//$('#productInfo'), //捕获的元素
//                         cancel: function(index){
//                             layer.close(index);
//                             layer_index2=0;
//            //                        this.content.show();
//            //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
//                         }
//                     });   
//                 }
//            });
            function gotoaccount(){
                //设置总额
                var payOriginAccount=parseFloat($("#order_reality_pay").text().replace(",",""));
                 var payHasAccount=parseFloat($("#order_has_pay").text().replace(",",""));
                $("#payOriginAccount").text(payOriginAccount);
                var productDisTotal=parseFloat($("#productDisTotal").val().replace(",",""));//参与折扣的总额，还有不参与折扣的
                var payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                var payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                //alert(payOriginAccount);alert(productDisTotal);alert(payDiscountAccount);alert(payMinusAccount);
                $("#payShouldAccount").text(((payOriginAccount-productDisTotal)+ productDisTotal*payDiscountAccount/100 - payMinusAccount-payHasAccount).toFixed(2));
                if(layer_index2!=0)
                {
                    return;
                }
                //出现收银界面
                layer_index2=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['65%', '60%'],
                     content: $('#accountbox'),//$('#productInfo'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
                         layer_index2=0;
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                });
            }
            
            //分开发送打印的方案，暂时备注
//            $('#printerKitchen').on(event_clicktouchstart, function(){
//                var orderid=$(".selectProduct").attr("orderid");
//                if (typeof Androidwymenuprinter == "undefined") {
//                    alert("找不到PAD设备");
//                    //return false;
//                }
//                 //有新品
//                if($(".selectProductA[order_status='0']").length>0)
////                if(true)
//                {                    
//                        //取得数据
//                        var sendjson=getallproductinfo();
//                        var url="<?php echo $this->createUrl('defaultOrder/orderKitchen',array('companyId'=>$this->companyId,"callId"=>"0"));?>/orderid/"+orderid+"/orderstatus/2";
//                        var statu = confirm("<?php echo yii::t('app','下单，并厨打，确定吗？');?>");
//                         if(!statu){
//                             return false;
//                         }                   
//                        $.ajax({
//                            url:url,
//                            type:'POST',
//                            data:sendjson,
//                            async:false,
//                            dataType: "json",
//                            success:function(msg){
//                                var printresultfail=false;
//                                var printresulttemp;
//                                var waittime=0;
//                                //保存成功，刷新
//                                var data=msg;
//                                alert(data.msg);
//                                if(data.status){
//                                    $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId));?>/orderId/'+orderid);
//                                
//                                    //取得打印结果,在layer中定时取得                                    
//                                     //"kitchenjobs_".$order->dpid."_".$order->lid
//                                     //kitchenjobs_0000000012_0000003421
//                                     //jobid_productid,productid,productid
//                                     $.each(data.jobs,function(skey,svalue){ 
//                                        data.jobs[skey]="0_"+svalue;
//                                    }); 
//                                    //alert(data.jobs)
//                                    var layer_flash_index = layer.load(0, {shade: [0.3,'#fff']});
//                                    //var wait=setInterval(function(){ 
//                                    var waitfun=function(){
//                                        waittime++;
//                                        //alert(waittime);
//                                        printresultfail=false;
//                                        $.each(data.jobs,function(skey,svalue){                                        
//                                            detaildata=svalue.split("_");
//                                            if(detaildata[0]=="0")//继续打印
//                                            {
//                                                printresulttemp=Androidwymenuprinter.printNetJob(data.dpid,detaildata[1],detaildata[2]);
//                                                //printresulttemp=true;
//                                                if(printresulttemp)
//                                                {
//                                                    data.jobs[skey]="1_"+svalue.substring(2);
//                                                }else{
//                                                    printresultfail=true;                                                
//                                                }
//                                            }
//                                         }); 
//                                         if(!printresultfail)
//                                         {
//                                            //clearInterval(wait);
//                                            //layer.close(layer_flash_index);
//                                            waittime=10;
//                                         }                               
//        //                                
//                                        if(waittime>3)
//                                        {
//                                             //clearInterval(wait);
//                                             layer.close(layer_flash_index);                                     
//                                            if(printresultfail)
//                                            {
//                                                alert("有打印失败，请去收银台查看2！");
//                                                //如果失败，就把打印任务插入到数据库
//                                                $.each(data.jobs,function(skey,svalue){                                        
//                                                        detaildata=svalue.split("_");
//                                                        if(detaildata[0]=="0")
//                                                        {
//                                                            $.ajax({
//                                                                url:'/wymenuv2/product/saveFailJobs/orderid/'+data.orderid+'/dpid/'+data.dpid+'/jobid/'+detaildata[1]+"/address/"+detaildata[2],
//                                                                type:'GET',
//                                                                //data:formdata,
//                                                                async:false,
//                                                                dataType: "json",
//                                                                success:function(msg){
//
//                                                                },
//                                                                error: function(msg){
//                                                                    alert("网络故障！")
//                                                                }
//                                                            });
//                                                        }
//                                                    });
//                                                    
//                                                    //如果有失败任务就打开对话框
//                                                    if(layer_index_printresult!=0)
//                                                       return;
//                                                    $('#printRsultListdetailsub').load('<?php echo $this->createUrl('defaultOrder/getFailPrintjobs',array('companyId'=>$this->companyId));?>/orderId/'+data.orderid);                                
//                                                    layer_index_printresult=layer.open({
//                                                        type: 1,
//                                                        shade: false,
//                                                        title: false, //不显示标题
//                                                        area: ['30%', '70%'],
//                                                        content: $('#printRsultListdetail'),//$('#productInfo'), //捕获的元素
//                                                        cancel: function(index){
//                                                            layer.close(index);
//                                                            layer_index_printresult=0;                                                                                                     
//                                                        }
//                                                    });
//                                            }
//                                        }else{
//                                            //waitfun();
//                                            setTimeout(waitfun, 2000);
//                                        }
//                                    }
//                                    //},3000);
//                                    waitfun();
//                                }else{
//                                    //alert(data.msg);
//                                    //alert("下单成功，打印失败");
//                                }
//                               //以上是打印
//                               //刷新orderPartial	                 
//                            },
//                            error: function(msg){
//                                alert("保存失败2");
//                            }
//                        });
//                }else{ //没有新品
//                //判断有没有失败的任务。
//                    $('#printRsultListdetailsub').load('<?php echo $this->createUrl('defaultOrder/getFailPrintjobs',array('companyId'=>$this->companyId));?>/orderId/'+orderid
//                    ,function(){                        
//                        if(parseInt($('#failprintjobnum').val())>0)
//                        {
//                            if(layer_index_printresult!=0)
//                                return;
//                             layer_index_printresult=layer.open({
//                                 type: 1,
//                                 shade: false,
//                                 title: false, //不显示标题
//                                 area: ['30%', '70%'],
//                                 content: $('#printRsultListdetail'),//$('#productInfo'), //捕获的元素
//                                 cancel: function(index){
//                                     layer.close(index);
//                                     layer_index_printresult=0;
//                                                                               
//                                 }
//                                });
//                                
//                        }else{
//                           gotoaccount();   
//                         }
//                    // });
//                    });
//                }
//            });

            $('#printerKitchen').on(event_clicktouchstart, function(){
                var orderid=$(".selectProduct").attr("orderid");
                if (typeof Androidwymenuprinter == "undefined") {
                    alert("找不到PAD设备");
                    //return false;
                }
                 //有新品
//                if($(".selectProductA[order_status='0']").length>0)
////                if(true)
//                {                    
                        //取得数据
                        var sendjson=getallproductinfo();
                        //alert(sendjson);return;
                        var url="<?php echo $this->createUrl('defaultOrder/orderKitchen',array('companyId'=>$this->companyId,"callId"=>"0"));?>/orderid/"+orderid+"/orderstatus/2";
                        var statu = confirm("<?php echo yii::t('app','下单，并厨打，确定吗？');?>");
                         if(!statu){
                             return false;
                         }                   
                        $.ajax({
                            url:url,
                            type:'POST',
                            data:sendjson,
                            async:false,
                            dataType: "json",
                            success:function(msg){
                                var printresultfail=false;
                                var printresulttemp=true;
                                var successjobids="0";
                                //保存成功，刷新
                                var data=msg;
                                //alert('1111'+data.msg);
                                if(data.status){
                                    $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId));?>/orderId/'+orderid);   
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-yellow");
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").addClass("bg-blue");
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").attr("status","2");
                                    var layer_flash_index = layer.load(0, {shade: [0.3,'#fff']});
                                    //alert(data.allnum);
                                    $.each(data.jobs,function(skey,svalue){  
                                        //alert(svalue);
                                        detaildata=svalue.split("_");
                                        if(detaildata[0]=="0")//继续打印
                                        {
                                            //alert(data.dpid);alert(detaildata[1]);alert(detaildata[2]);
                                            printresulttemp=Androidwymenuprinter.printNetJob(data.dpid,detaildata[1],detaildata[2]);
                                            ///////////printresulttemp=true;
                                            if(printresulttemp)
                                            {
                                                data.jobs[skey]="1_"+svalue.substring(2);
                                            }else{
                                                printresultfail=true;                                                
                                            }
                                        }
                                     }); 
                                    layer.close(layer_flash_index); 
                                    if(!printresultfail)
                                    {
                                        alert("厨打成功！");
					//修改代码CF
                                        $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-yellow");
                                        $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").addClass("bg-blue");
                                        $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").attr("status","2");
                                                 				
                                        //修改下单后座位颜色代码；
                                    }   
                                    //alert("可能有打印失败，请去打印机处确认，如果失败，请去收银台查看并重打！");
                                    $.each(data.jobs,function(skey,svalue){                                        
                                        detaildata=svalue.split("_");
                                        if(detaildata[0]=="1")
                                        {
                                            successjobids=successjobids+","+detaildata[1];                                                    
                                        }
                                    });
                                    //如果失败，就把打印任务插入到数据库
                                    //如果有失败任务就打开对话框
                                    //alert(successjobids);
                                    if(printresultfail)
                                    {
                                        $('#printRsultListdetailsub').load('<?php echo $this->createUrl('defaultOrder/getFailPrintjobs',array('companyId'=>$this->companyId));?>/orderId/'+data.orderid+"/jobId/"+successjobids);                                
                                        if(layer_index_printresult!=0)
                                        {
                                            layer.close(layer_index_printresult);
                                            layer_index_printresult=0;
                                           //return;
                                        }
                                        layer_index_printresult=layer.open({
                                            type: 1,
                                            shade: false,
                                            title: false, //不显示标题
                                            area: ['30%', '70%'],
                                            content: $('#printRsultListdetail'),//$('#productInfo'), //捕获的元素
                                            cancel: function(index){
                                                layer.close(index);
                                                layer_index_printresult=0;                                                                                                     
                                            }
                                        });
                                    }else{
                                        $.ajax({
                                            url:'<?php echo $this->createUrl('defaultOrder/saveFailPrintjobs',array('companyId'=>$this->companyId));?>/orderId/'+data.orderid+'/jobId/'+successjobids,
                                            type:'GET',
                                            timeout:5000,
                                            cache:false,
                                            async:false,
                                            dataType: "json",
                                            success:function(data){
                                                //alert(msg);防止前台开台，但是后台结单或撤台了，就不能继续下单
                                                //if(!(msg.status == "1" || msg.status == "2" || msg.status == "3"))

                                            },
                                            error: function(msg){

                                            },
                                            complete : function(XMLHttpRequest,status){
                                                if(status=='timeout'){

                                                }
                                            }
                                        });
                                    }
                                }else{
                                    if(data.msg=="noorderproduct")
                                    {
                                        //判断有没有失败的任务。
                                        $('#printRsultListdetailsub').load('<?php echo $this->createUrl('defaultOrder/getFailPrintjobs',array('companyId'=>$this->companyId));?>/orderId/'+orderid
                                        ,function(){                        
                                            if(parseInt($('#failprintjobnum').val())>0)
                                            {
                                                if(layer_index_printresult!=0)
                                                {
                                                    layer.close(layer_index_printresult);
                                                    layer_index_printresult=0;
                                                   //return;
                                                }
                                                 layer_index_printresult=layer.open({
                                                     type: 1,
                                                     shade: false,
                                                     title: false, //不显示标题
                                                     area: ['30%', '70%'],
                                                     content: $('#printRsultListdetail'),//$('#productInfo'), //捕获的元素
                                                     cancel: function(index){
                                                         layer.close(index);
                                                         layer_index_printresult=0;                                                                               
                                                     }
                                                });

                                            }else{
//                                               if($("#accountbeforeorderstatus").val()==3)
//                                               {
//                                                   //已付款，直接去结单
//                                                   bootbox.confirm("<?php echo yii::t('app','已支付完成,确定结单吗？');?>", function(result) {                    
//                                                    if(result){
//                                                        var url="<?php echo $this->createUrl('defaultOrder/orderAccountDirect',array('companyId'=>$this->companyId));?>/orderid/"+orderid+"/orderstatus/4/cardno/"+cardno;                                    
//                                                        $.ajax({
//                                                            url:url,
//                                                            type:'POST',
//                                                            data:"",
//                                                            async:false,
//                                                            dataType: "json",
//                                                            success:function(msg){
//                                                                var data=msg;
//                                                                if(data.status){                                                
//                                                                    //手动改变座位的状态和颜色
//                                                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-yellow");
//                                                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-blue");
//                                                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-green");
//                                                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").attr("status","4");
//                                                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").find("div").hide();                                                
//                                                                    sitevisible();
//                                                                }else{
//                                                                    alert("结单失败1，请重试！");
//                                                                }
//                                                            },
//                                                            error: function(msg){
//                                                                alert("结单失败2，请重试！");                                            
//                                                            }
//                                                        });
//                                                    }
//                                                });
//                                               }else{
                                                   //弹出收银界面
                                                    gotoaccount();  
//                                                }
                                            }
                                        });
                                    }else{
                                        alert(data.msg);
                                    }
                                    //alert("下单成功，打印失败");
                                }
                               //以上是打印
                               //刷新orderPartial	                 
                            },
                            error: function(msg){
                                alert("保存失败2");
                            }
                        });
//                }else{ //没有新品
//                
//                }
            });
            
            $('#print_box_close_failjobs').on(event_clicktouchstart, function(){               
                 layer.close(layer_index_printresult);                
                 layer_index_printresult=0;
            });
            
            $('#print_box_account_direct').on(event_clicktouchstart, function(){               
                 layer.close(layer_index_printresult);                
                 layer_index_printresult=0;
                 gotoaccount();
            });
            
            
            $('#layer2_close').on(event_clicktouchstart, function(){               
                 layer.close(layer_index2);
                 layer_index2=0;
            });
            
            $('#site_list_button').on(event_clicktouchstart,function(){
                //刷新座位页面                                    
                var typeId=$('li[class="tabSite slectliclass"]').attr('typeid');
                //alert(typeId);
//                tabcurrenturl='<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId));?>/typeId/'+typeId;
//                $('#tabsiteindex').load(tabcurrenturl);
                sitevisible();
            });
            
            //member_card_div
            $('.member_card_div').on(event_clicktouchstart,function(){
                //刷新座位页面                                    
                $(".member_card_div").removeClass("edit_span_select_member");
                $(this).addClass("edit_span_select_member");
            });
            
            function productCancelSelect(obj,num)
            {
                var objnum=obj.find('span[class="badge"]');
                var curnum=parseFloat(objnum.text().replace(",",""));
                
                if(curnum-num <=0)
                {
                    obj.remove();
                }else{
                    objnum.text(curnum-parseFloat(num));
                }
            }
            
            $('.selectProductDel').live(event_clicktouchstart, function(){
                var orderstatus=$(this).parent().attr("order_status");
                var curnum = $(this).parent().find('span[class="badge"]').text().replace(",","")
                var setid=$(this).parent().attr("setid");
                var oprole="<?php echo Yii::app()->user->role; ?>";
                if(oprole > '2')
                {
                    alert("没有退菜权限！");
                    return;
                }
                //alert(curnum);
                $("#selectproductnumfordelete").val(curnum);
                if(orderstatus!="0")//退菜是单个的
                {
                    var isretreat=$(this).parent().attr("is_retreat");
                    if(isretreat==1)
                    {
                        alert("已经退菜");
                        return false;
                    }else{
                        var lid=$(this).parent().attr("lid");
                        $('#retreatbox').load("<?php echo $this->createUrl('defaultOrder/addRetreatOne',array('companyId'=>$this->companyId));?>/orderDetailId/"+lid);
                        if(layer_index_retreatbox!=0)
                        {
                            return;
                        }
                        layer_index_retreatbox=layer.open({
                             type: 1,
                             shade: false,
                             title: false, //不显示标题
                             area: ['50%', '70%'],
                             content: $('#retreatbox'), //捕获的元素
                             cancel: function(index){
                                 layer.close(index);
                                 layer_index_retreatbox=0;
                //                        this.content.show();
                //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                            }
                        }); 
                    }
                }else{ //下单前减少是整体的           
                    if(setid=="0000000000")
                    {
                        productCancelSelect($(this).parent(),1);
                    }else{
                        var objnum=0;
                        $.each($(".selectProductA[setid="+setid+"]"),function(skey,sobj){
                            //alert($(sobj));
                            objnum=parseFloat($(sobj).find('span[class="badge"]').text());
                            productCancelSelect($(sobj),objnum);
                        });
                        //$("#productTempOrderNum").val(parseInt($("#productTempOrderNum").val())-1);
                    }                    
                }
            });

            $('.selectNow').click(function(){
                //var groupid=$(this).attr("group");
                //var lit=$('label.selectTaste[group="'+groupid+'"]');
                var chk=$(this).hasClass("active");
                //alert(chk);
                lit.each(function(){
                    $(this).removeClass('active');
                });
                if(chk)
                {
                    return false;
                }
           });//添加cf
            
            $('.selectProductName,.selectProductName,.badge').live('click', function(){
                var lid=$(this).parent().attr('lid');
                var productid=$(this).parent().attr('productid');
                var isgiving=$(this).parent().attr('is_giving');
                var originprice=$(this).parent().find(".selectProductPrice").text();
                var productnumber=$(this).parent().find("span[class='badge']").text();
                var nowprice=$(this).parent().find(".selectProductNowPrice").text();
                var productdiscount=$(this).parent().find(".selectProductDiscount").text();
                var productname=$(this).parent().find(".selectProductName").text();
                var tasteids=$(this).parent().attr("tasteids");
                var tastememo=$(this).parent().attr("tastememo");                
                var isretreat=$(this).parent().attr("is_retreat");
                var orderstatus=$(this).parent().attr("order_status");
                var productstatus=$(this).parent().attr("product_status");
                if(productdiscount.lastIndexOf("%")>=0)
                {//alert(productstatus);exit;
                    $(".selectDiscount").removeClass("active");
                    $(".selectDiscount[id='checkboxDiscount']").addClass("active");
                }else{
                    $(".selectDiscount").removeClass("active");
                    $(".selectDiscount[id='checkboxMinus']").addClass("active");
                }
                if(productstatus=='0')
                {//alert(productstatus);exit;
                    $(".checkboxNow").removeClass("active");
                    $(".checkboxNow[id='checkboxNow']").addClass("active");
                }else if(productstatus=='1'){
                    $(".checkboxNow").removeClass("active");
                    $(".checkboxNow[id='checkboxWait']").addClass("active");
                }else if(productstatus=='2'){
                    $(".checkboxNow").removeClass("active");
                    $(".checkboxNow[id='checkboxHurry']").addClass("active");
                }//添加
                if(isgiving=="1")
                {
                    $(".selectDiscount").removeClass("active");
                    $(".selectDiscount[id='checkboxGiving']").addClass("active");
//                    $("#spanProductDiscount").text(originprice);
//                    $("#spanNowPrice").text("0");
                } 
                $("#spanLid").text(lid);
                $("#spanProductId").text(productid);
                $("#spanProductName").text(productname);
                $("#spanOriginPrice").text(originprice);
                $("#spanNumber").text(productnumber);
                $("#spanNowPrice").text(nowprice);
                $("#spanNowPriceOrig").text(nowprice);
                $("#spanProductDiscount").text(productdiscount);
                $("#spanProductDiscountOrig").text(productdiscount);
                $("#spanTasteIds").text(tasteids);
                $("#spanTasteMemo").text(tastememo);
                $("#spanIsRetreat").text(isretreat);
                $("#spanProductStatus").text(productstatus);
                $("#spanOrderStatus").text(orderstatus);
                $('#productTaste').load('<?php echo $this->createUrl('defaultOrder/productTasteAll',array('companyId'=>$this->companyId,'isall'=>'0'));?>/lid/'+productid);
                if(layer_index1!=0)
                {
                    return;
                }
                layer_index1=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['60%', '65%'],
                     content: $('#productInfo'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
                         layer_index1=0;
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                 });  
            });
            //催菜打印出单
            $('#btn-retreat').on(event_clicktouchstart,function(){
                var lid =$("#spanLid").text();
                var productid=$("#spanProductId").text();
                var isretreat=$("#spanIsRetreat").text();
                var orderstatus=$("#spanOrderStatus").text();
                if(isretreat=="1")
                {
                    alert("已经退菜！");
                    return false
                }
                if(orderstatus=="0")
                {
                    alert("还没有下单、直接删除就行！");
                    return false
                }    
//                alert(lid);//不能刷新orderPartial，手动改变状态
//                var $modal=$('#portlet-config');
//                    $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/retreatProduct',array('companyId'=>$this->companyId));?>/id/'+lid
//                    ,'', function(){
//                      $modal.modal();
//                });
//		$modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/addRetreatOne',array('companyId'=>$this->companyId));?>/orderDetailId/'+lid
//                    ,'', function(){
//                      layer.close(layer_index1);
//                 	  layer_index1=0;
//                      $modal.modal();
//                });
                $('#retreatbox').load("<?php echo $this->createUrl('defaultOrder/addRetreatOne',array('companyId'=>$this->companyId));?>/orderDetailId/"+lid);
                if(layer_index_retreatbox!=0)
                {
                    return;
                }
                layer_index_retreatbox=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['50%', '50%'],
                     content: $('#retreatbox'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
                         layer_index_retreatbox=0;
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                 });  
             });
//             $('#btn-reminder').on(event_clicktouchstart,function(){
//                 var lid =$("#spanLid").text();
//                 var productid=$("#spanProductId").text();
//                 var isretreat=$("#spanIsRetreat").text();
//                 var orderstatus=$("#spanOrderStatus").text();
//                 if(isretreat=="1")
//                 {
//                     alert("已经退菜、无法进行催菜操作！！！");
//                     return false
//                 }
//                 if(orderstatus=="0")
//                 {
//                     alert("还没有下单、无法进行催菜操作！！！");
//                     return false
//                 }
               // var statu = confirm("<?php echo yii::t('app','确定要进行催菜操作吗？');?>");
//                 if(!statu){
//                     return false;
//                 }
               // $('#retreatbox').load("<?php echo $this->createUrl('defaultOrder/addRetreatOne',array('companyId'=>$this->companyId));?>/orderDetailId/"+lid);
//                 if(layer_index_retreatbox!=0)
//                 {
//                     return;
//                 }
//                 layer_index_retreatbox=layer.open({
//                      type: 1,
//                      shade: false,
//                      title: false, //不显示标题
//                      area: ['50%', '50%'],
//                      content: $('#retreatbox'), //捕获的元素
//                      cancel: function(index){
//                          layer.close(index);
//                          layer_index_retreatbox=0;
//         //                        this.content.show();
//         //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
//                      }
//                  });  
//              });
          
             $('#payDiscountAccountDiv').on("click",function(){
                //var lid=$(this).attr("lid");
                $('#alldiscountselect').load("<?php echo $this->createUrl('defaultOrder/selectAllDiscount',array('companyId'=>$this->companyId));?>");
                if(layer_index_selectalldiscount!=0)
                {
                    return;
                }
                layer_index_selectalldiscount=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['60%', '40%'],
                     content: $('#alldiscountselect'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
                         layer_index_selectalldiscount=0;
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                    }
                }); 
             });
             
             $('#btn-reprint').on(event_clicktouchstart,function(){
                var lid =$("#spanLid").text();
                var orderid=$(".selectProduct").attr("orderid");
                var order=lid+"&&"+orderid;
//                 var $modal=$('#portlet-config');
//                 //不能刷新orderPartial，手动改变状态
//                $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/printOneKitchen',array('companyId'=>$this->companyId));?>/orderProductId/'+lid+'/orderId/'+orderid
//                         ,'', function(){
//                                     $modal.modal();
//                             });    
				alert(order);
                //var url='<?php echo $this->createUrl('defaultOrder/printOneKitchen',array('companyId'=>$this->companyId));?>/orderProductId/'+lid+'/orderId/'+orderid;	
//                var statu = confirm("<?php echo yii::t('app','催菜，确定吗？');?>");
//                 if(!statu){
//                     return false;
//                 } 
				var orderstatus = $(this).parent().attr("order_status");
                var curnum = $(this).parent().find('span[class="badge"]').text().replace(",","")
                var setid = $(this).parent().attr("setid");
                var oprole ="<?php echo Yii::app()->user->role; ?>";
                if(oprole > '2')
                {
                    alert("没有退菜权限！");
                    return;
                }
                //alert(curnum);
                //$("#selectproductnumfordelete").val(curnum);
                if(orderstatus!="0")//退菜是单个的
                {
                    var isretreat=$(this).parent().attr("is_retreat");
                    if(isretreat==1)
                    {
                        alert("已经退菜");
                        return false;
                    }else{
                        var lid=$(this).parent().attr("lid");
                        var url='<?php echo $this->createUrl('defaultOrder/hurryProduct',array('companyId'=>$this->companyId));?>/orderDetailId/'+lid;
                       alert("111"); 
                    }
                }else{ //下单前减少是整体的           
                    alert("222");                   
                }
            });
            
            $('#cancel_zero').on(event_clicktouchstart,function(){
                var payRealityAccount=$("#payRealityAccount").text();
                var payHasAccount=parseFloat($("#order_has_pay").text().replace(",",""));
                var payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                var productDisTotal=parseFloat($("#productDisTotal").val().replace(",",""));
                var payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                var payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                var payShouldAccount=$("#payShouldAccount").text();                        
                //var payChangeAccount=$("#payChangeAccount").text();
                if($(this).hasClass("edit_span_select_zero"))
                {
                    $(this).removeClass("edit_span_select_zero");
                    $("#payShouldAccount").text(((payOriginAccount-productDisTotal)+productDisTotal*payDiscountAccount/100 - payMinusAccount-payHasAccount).toFixed(2));
                    $("#payCancelZero").text("0.00");
                }else{
                    $(this).addClass("edit_span_select_zero");
                    $("#payCancelZero").text("0"+payShouldAccount.substr(payShouldAccount.indexOf("."),3));
                    payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                    $("#payShouldAccount").text(payShouldAccount);
                    
                }
                var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                if(changeaccount>0)
                {
                    $("#payChangeAccount").text(changeaccount.toFixed(2));
                }else{
                    $("#payChangeAccount").text("0.00");
                }
            });
            
            $('.edit_span').on(event_clicktouchstart,function(){
                $('.edit_span').removeClass("edit_span_select");
                $(this).addClass("edit_span_select");
                var payOriginAccount=$("#payOriginAccount").text();
                var selectid=$(this).attr("selectid");
                if(selectid=="pay_member_card")
                {
                    fn_member_card_pay();
                }
            });            
            
            $('.edit_span_show').on(event_clicktouchstart,function(){
                $('.edit_span_normal').toggleClass("edit_span_hide");
                $('.edit_span_other').toggleClass("edit_span_hide");
                $(".edit_span_normal").removeClass("edit_span_select");
                $(".edit_span_other").removeClass("edit_span_select");
            });
            
            $('.alphabet').on(event_clicktouchstart,'li',function(){
                var alpha=$(this).text();
                var deal=$(this).attr("deal");
                var alphalist=$("#alphabetlist").text();
                if(deal=="A")
                {
                    alphalist=alphalist+alpha;
                    $("#alphabetlist").text(alphalist);
                }else if(deal=="del")
                {
                    alphalist=alphalist.substr(0,alphalist.length-1);
                    $("#alphabetlist").text(alphalist);
                }else if(deal=="none")
                {
                    return;
                }
                if(alphalist.length>0)
                {
                    $("li[search='search']").hide();
                    $("li[search='search'][simplecode^='"+alphalist+"']").show();
                }else{
                    $("li[search='search']").show();
                }
            });
            
            $('.calc_num').on(event_clicktouchstart,'li',function(){
                var nowval=$(this).text();
                var selectid=$(".edit_span_select").attr("selectid");
                var payOriginAccount=$("#payOriginAccount").text();
                var payHasAccount=parseFloat($("#order_has_pay").text().replace(",",""));
                var payDiscountAccount=$("#payDiscountAccount").text();
                var productDisTotal=$("#productDisTotal").val();
                var payMinusAccount=$("#payMinusAccount").text();
                var cancel_zero=$("#cancel_zero").hasClass("edit_span_select_zero");
                var payShouldAccount=$("#payShouldAccount").text();
                var payRealityAccount=$("#payRealityAccount").text();
                var payCashAccount=$("#payCashAccount").text();
                var payMemberAccount=$("#payMemberAccount").text();
                var payUnionAccount=$("#payUnionAccount").text();
                var payOthers=$("#payOthers").text();
                if(selectid=="discount")
                {   
                    return;
                    if(nowval!="." && nowval!="00" && nowval!="10" && nowval!="20" && nowval!="50" && nowval!="100")
                    {
                        if(parseFloat(payDiscountAccount.replace(",",""))*10>100)
                        {
                            payDiscountAccount=nowval;
                        }else{
                            payDiscountAccount=parseInt(payDiscountAccount)*10 + parseInt(nowval);
                        }
                        $("#payDiscountAccount").text(payDiscountAccount+"%");
                        $("#payMinusAccount").text("0.00");
                        payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                        productDisTotal=parseFloat($("#productDisTotal").val().replace(",",""));
                        payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                        payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                        //payOriginAccount*payDiscountAccount/100 productDisTotal*payDiscountAccount/100
                        $("#payShouldAccount").text(((payOriginAccount-productDisTotal)+productDisTotal*payDiscountAccount/100 - payMinusAccount-payHasAccount).toFixed(2));
                        if(cancel_zero)
                        {
                            payShouldAccount=$("#payShouldAccount").text();
                            $("#payCancelZero").text("0"+payShouldAccount.substr(payShouldAccount.indexOf("."),3));
                            payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                            $("#payShouldAccount").text(payShouldAccount);
                        }
                        var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                        if(changeaccount>0)
                        {
                            $("#payChangeAccount").text(changeaccount.toFixed(2));
                        }else{
                            $("#payChangeAccount").text("0.00");
                        }
                    }
                }else if(selectid=="minus")
                {
                    if(nowval=="10" || nowval=="20"|| nowval=="50"|| nowval=="100")
                    {
                        return;
                    }
                    //alert(payMinusAccount);alert(nowval);
                    if(payMinusAccount=="0.00" || payMinusAccount=="0" || payMinusAccount=="00")
                    {
                        if(nowval!=".")
                        {
                            $("#payMinusAccount").text(nowval);
                        }
                    }else{
                        if(payMinusAccount.indexOf(".")>0 && nowval==".")
                        {

                        }else{
                            $("#payMinusAccount").html(payMinusAccount+nowval);
                        }
                    }                    
                    //$("#payDiscountAccount").text("100%");
                    payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                    payHasAccount=parseFloat($("#order_has_pay").text().replace(",",""));
                    payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                    payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                    //payOthers=parseFloat($("#payOthers").text().replace(",",""));
                    var shouldpaytemp=(payOriginAccount-productDisTotal)+productDisTotal*payDiscountAccount/100 - payMinusAccount-payHasAccount;
                    if(shouldpaytemp>0)
                    {
                        $("#payShouldAccount").text(shouldpaytemp.toFixed(2));
                    }else{
                        $("#payShouldAccount").text("0.00");
                    }
                    if(cancel_zero)
                    {
                        payShouldAccount=$("#payShouldAccount").text();
                        $("#payCancelZero").text("0"+payShouldAccount.substr(payShouldAccount.indexOf("."),3));
                        payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                        $("#payShouldAccount").text(payShouldAccount);
                    }
                    var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_cash")
                {
                    if(nowval=="10" || nowval=="20"|| nowval=="50"|| nowval=="100")
                    {
                        return;
                    }
                    //alert(payMinusAccount);alert(nowval);
                    if(payCashAccount=="0.00" || payCashAccount=="0" || payCashAccount=="00")
                    {
                        if(nowval!=".")
                        {
                            $("#payCashAccount").text(nowval);
                        }
                    }else{
                        if(payCashAccount.indexOf(".")>0 && nowval==".")
                        {

                        }else{
                            $("#payCashAccount").html(payCashAccount+nowval);
                        }
                    }                    
                    $("#payRealityAccount").html((parseFloat($("#payCashAccount").text().replace(",",""))+parseFloat(payOthers.replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payUnionAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_member_card")
                {
                    return false;
                }else if(selectid=="pay_union_card")
                {
                    if(nowval=="10" || nowval=="20"|| nowval=="50"|| nowval=="100")
                    {
                        return;
                    }
                    //alert(payMinusAccount);alert(nowval);
                    if(payUnionAccount=="0.00" || payUnionAccount=="0" || payUnionAccount=="00")
                    {
                        if(nowval!=".")
                        {
                            $("#payUnionAccount").text(nowval);
                        }
                    }else{
                        if(payUnionAccount.indexOf(".")>0 && nowval==".")
                        {

                        }else{
                            $("#payUnionAccount").html(payUnionAccount+nowval);
                        }
                    }                    
                    $("#payRealityAccount").html((parseFloat($("#payUnionAccount").text().replace(",",""))+parseFloat(payOthers.replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
//                }else if(selectid=="pay_others")
//                {
//                    if(nowval=="10" || nowval=="20"|| nowval=="50"|| nowval=="100")
//                    {
//                        return;
//                    }
//                    //alert(payMinusAccount);alert(nowval);
//                    if(payOthers=="0.00" || payOthers=="0" || payOthers=="00")
//                    {
//                        if(nowval!=".")
//                        {
//                            $("#payOthers").text(nowval);
//                        }
//                    }else{
//                        if(payOthers.indexOf(".")>0 && nowval==".")
//                        {
//
//                        }else{
//                            $("#payOthers").html(payOthers+nowval);
//                        }
//                    }                    
//                    $("#payRealityAccount").html((parseFloat(payUnionAccount.replace(",",""))+parseFloat($("#payOthers").text().replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
//                    
//                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
//                    if(changeaccount>0)
//                    {
//                        $("#payChangeAccount").text(changeaccount.toFixed(2));
//                    }else{
//                        $("#payChangeAccount").text("0.00");
//                    }
//                }
                 }else if(selectid=="pay_others_detail")
                {
                    if(nowval=="10" || nowval=="20"|| nowval=="50"|| nowval=="100")
                    {
                        return;
                    }
                    //alert(payMinusAccount);alert(nowval);
                    var spanid=$(".edit_span_select").attr("spanid");
                    var spanvalue=$("#"+spanid).text();
                    //alert(spanid);alert(spanvalue);
                    if(spanvalue=="0.00" || spanvalue=="0" || spanvalue=="00")
                    {
                        if(nowval!=".")
                        {
                            $("#"+spanid).text(nowval);
                        }
                    }else{
                        if(spanvalue.indexOf(".")>0 && nowval==".")
                        {

                        }else{
                            $("#"+spanid).html(spanvalue+nowval);
                        }
                    }                    
                    $("#payOthers").html((parseFloat($("#payOthers").text().replace(",",""))+parseFloat($("#"+spanid).text().replace(",",""))-parseFloat(spanvalue.replace(",",""))).toFixed(2));
                    
                    var otherdetail=$("#payOthers").attr("detail");
                    spanvalue=$("#"+spanid).text();
                    var spanlid=$("#"+spanid).attr("lid");
                    var reg="[0-9]{10},([0-9.])*";
                    strdetail = otherdetail.replace(new RegExp(reg,"g"),function(word){
                        var newword=word.split(",");
                        if(newword[0]==spanlid)
                        {
                            var retval=newword[0]+","+spanvalue;
                            //alert(retval);
                            return retval;
                        }else{
                            return word;
                        }                       
                    });
                    $("#payOthers").attr("detail",strdetail);
                    
                    $("#payRealityAccount").html((parseFloat(payUnionAccount.replace(",",""))+parseFloat($("#payOthers").text().replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }
            });
            
            $('.calc_dan').on(event_clicktouchstart,'li',function(){
                if($(".selectDiscount[id='checkboxGiving']").hasClass("active"))
                {
                    return false;
                }
//                 if($(".selectNow[id='checkboxNow']").hasClass("active"))
//                 {
//                     return false;
//                 }
//                 if($(".selectNow[id='checkboxWait']").hasClass("active"))
//                 {
//                     return false;
//                 }
//                 if($(".selectNow[id='checkboxHurry']").hasClass("active"))
//                 {
//                     return false;
//                 }//添加
                if($("#checkboxDiscount").hasClass("active"))
                {
                    var discount=parseFloat($("#spanProductDiscount").text().replace(",",""));
                    var originprice=$("#spanOriginPrice").text();
                    var nowval=$(this).html();
                    if(nowval!="." && nowval!="00")
                    {
                        if(discount*10>99)
                        {
                            discount=nowval;
                        }else{
                            discount=parseInt(discount*10) + parseInt(nowval);
                        }
                        $("#spanProductDiscount").text(discount+"%");
                        $("#spanNowPrice").text((originprice*discount/100).toFixed(2));
                    }
                   return false;
                }
                if($("#checkboxMinus").hasClass("active"))
                {
                    var discount=$("#spanProductDiscount").text();
                    var originprice=parseFloat($("#spanOriginPrice").text().replace(",",""));
                    var nowval=$(this).html();
                    if(discount=="0.00" && nowval!=".")
                    {
                        $("#spanProductDiscount").html(nowval);
                    }
                    else if(discount=="0" || discount=="00")
                    {
                        if(nowval!=".")
                        {
                            $("#spanProductDiscount").text(nowval);
                        }
                    }else{
                        if(discount.indexOf(".")>0 && nowval==".")
                        {

                        }else{
                            $("#spanProductDiscount").html(discount+nowval);
                        }
                    }
                    var cashinf=parseFloat($("#spanProductDiscount").text().replace(",",""));
                    if(originprice-cashinf>0)
                    {
                        $("#spanNowPrice").html(Math.round((originprice-cashinf)*100)/100);//little than 0 not show
                    }else{
                        $("#spanNowPrice").html("0");
                    }
                }
            });

            $('#cl_one').on(event_clicktouchstart,function(){
                if($("#checkboxMinus").hasClass("active"))
                {
                    var discount=$("#spanProductDiscount").text();
                    var originprice=parseFloat($("#spanOriginPrice").text().replace(",",""));
                    if(discount.length==1)
                    {
                        $("#spanProductDiscount").html("0");
                        $("#spanNowPrice").html($("#spanOriginPrice").text());
                    }else{
                        $("#spanProductDiscount").html(discount.substr(0,discount.length-1));                    
                        var cashinf=parseFloat($("#spanProductDiscount").text().replace(",",""));
                        if(originprice-cashinf>0)
                        {
                            $("#spanNowPrice").html(Math.round((originprice-cashinf)*100)/100);//little than 0 not show
                        }else{
                            $("#spanNowPrice").html("0");
                        }
                    }
                }
            });
            
            $('#cl_all').on(event_clicktouchstart,function(){
                var discountOrig=$("#spanProductDiscountOrig").text();
                var spanOriginPrice=$("#spanOriginPrice").text();
                var nowpriceOrig=$("#spanNowPriceOrig").text();
                //alert(discountOrig);
                
                if(discountOrig.lastIndexOf("%")>=0)
                {
                    $("#spanProductDiscount").text(discountOrig);
                    $("#spanNowPrice").text(nowpriceOrig);
                    $(".selectDiscount").removeClass("active");
                    $(".selectDiscount[id='checkboxDiscount']").addClass("active");
                }else{                    
                    $("#spanProductDiscount").text("0.00");
                    $("#spanNowPrice").text(spanOriginPrice);
                    $(".selectDiscount").removeClass("active");
                    $(".selectDiscount[id='checkboxMinus']").addClass("active");
                }
            });
            
            $('#product_close').on(event_clicktouchstart,function(){
                layer.close(layer_index1);
                layer_index1=0;
            });
            
            $('#product_yes').on(event_clicktouchstart,function(){
                var orderstatus=$("#spanOrderStatus").text();
                if(orderstatus=="1")
                {
                    alert("已下单，不能再修改！");
                    return false;
                }
                lid=$("#spanLid").text();
                productid=$("#spanProductId").text();
                var obj=$(".selectProduct").find("li[lid='"+lid+"'][productid='"+productid+"']");
                //alert(lid);alert(productid);alert(obj.attr("is_giving"));
                var isgiving="0";
                var special="";
                var tasteids="";
                var tastememo="";
                var productstatus="0";
                
                tastememo=$("#Order_remark_taste").val();
                $("#productTaste").find("label[class='selectTaste btn btn-default active']").each(function(){
                    tasteids=tasteids+$(this).attr("tasteid")+"|";
                });
                //alert(tasteids);//return;
                obj.attr("tasteids",tasteids);
                obj.attr("tastememo",tastememo);
                //(tasteids);alert(tastememo);
                if($(".selectDiscount[id='checkboxGiving']").hasClass("active"))
                {
                    isgiving="1";
                    special=special+"赠";
                }
                //alert(special);
                obj.attr("is_giving",isgiving);
                if($(".checkboxNow[id='checkboxWait']").hasClass("active"))
                {
                    productstatus="1";
                    special=special+"等";
                }
                if($(".checkboxNow[id='checkboxHurry']").hasClass("active"))
                {
                	productstatus="2";
                    special=special+"急";
                }
                obj.attr("product_status",productstatus);
                if(obj.attr("is_retreat")=="1")
                {
                    special=special+"退";
                }
                if(obj.attr("is_print")=="1")
                {
                    special=special+"印";
                }
                if(tasteids!="" || tastememo!="")
                {
                    special=special+"味";
                }
                //alert(special);
                obj.find("span[class='special badge']").text(special);
                var nowprice=$("#spanNowPrice").text();
                obj.find("span[class='selectProductNowPrice']").text(nowprice);
                var productdiscount=$("#spanProductDiscount").text();
                obj.find("span[class='selectProductDiscount']").text(productdiscount);
                layer.close(layer_index1);
                layer_index1=0;
            });

            $('#pay_clearone').on(event_clicktouchstart,function(){
                var selectid=$(".edit_span_select").attr("selectid");
                var payOriginAccount=$("#payOriginAccount").text();
                var payHasAccount=parseFloat($("#order_has_pay").text().replace(",",""));
                var productDisTotal=$("#productDisTotal").val();
                var payDiscountAccount=$("#payDiscountAccount").text();
                var payMinusAccount=$("#payMinusAccount").text();
                var cancel_zero=$("#cancel_zero").hasClass("edit_span_select_zero");
                var payShouldAccount=$("#payShouldAccount").text();
                var payRealityAccount=$("#payRealityAccount").text();
                var payCashAccount=$("#payCashAccount").text();
                var payMemberAccount=$("#payMemberAccount").text();
                var payUnionAccount=$("#payUnionAccount").text();
                var payOthers=$("#payOthers").text();
                if(selectid=="minus")
                {
                    if(payMinusAccount=="0.00" || payMinusAccount=="0" || payMinusAccount=="00")
                    {
                        return false;
                    }
                    if(payMinusAccount.length==1)
                    {
                        $("#payMinusAccount").text("0.00");
                    }else{
                        $("#payMinusAccount").text(payMinusAccount.substr(0,payMinusAccount.length-1));
                    }
                    //$("#payDiscountAccount").text("100%");
                    payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                    productDisTotal=parseFloat($("#productDisTotal").val().replace(",",""));
                    payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                    payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                    var shouldpaytemp=(payOriginAccount-productDisTotal)+productDisTotal*payDiscountAccount/100 - payMinusAccount-payHasAccount;
                    if(shouldpaytemp>0)
                    {
                        $("#payShouldAccount").text(shouldpaytemp.toFixed(2));
                    }else{
                        $("#payShouldAccount").text("0.00");
                    }
                    if(cancel_zero)
                    {
                        payShouldAccount=$("#payShouldAccount").text();
                        $("#payCancelZero").text("0"+payShouldAccount.substr(payShouldAccount.indexOf("."),3));
                        payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                        $("#payShouldAccount").text(payShouldAccount);
                    }
                    var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_cash")
                {
                    if(payCashAccount=="0.00" || payCashAccount=="0" || payCashAccount=="00")
                    {
                        return false;
                    }
                    if(payCashAccount.length==1)
                    {
                        $("#payCashAccount").text("0.00");
                    }else{
                        $("#payCashAccount").text(payCashAccount.substr(0,payCashAccount.length-1));
                    }
                    $("#payRealityAccount").html((parseFloat($("#payCashAccount").text().replace(",",""))+parseFloat(payOthers.replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payUnionAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_member_card")
                {
                    return false;
                }else if(selectid=="pay_union_card")
                {
                    if(payUnionAccount=="0.00" || payUnionAccount=="0" || payUnionAccount=="00")
                    {
                        return false;
                    }
                    if(payUnionAccount.length==1)
                    {
                        $("#payUnionAccount").text("0.00");
                    }else{
                        $("#payUnionAccount").text(payUnionAccount.substr(0,payUnionAccount.length-1));
                    }
                    $("#payRealityAccount").html((parseFloat($("#payUnionAccount").text().replace(",",""))+parseFloat(payOthers.replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
//                }else if(selectid=="pay_others")
//                {
//                    if(payOthers=="0.00" || payOthers=="0" || payOthers=="00")
//                    {
//                        return false;
//                    }
//                    if(payOthers.length==1)
//                    {
//                        $("#payOthers").text("0.00");
//                    }else{
//                        $("#payOthers").text(payOthers.substr(0,payOthers.length-1));
//                    }
//                    $("#payRealityAccount").html((parseFloat($("#payOthers").text().replace(",",""))+parseFloat(payUnionAccount.replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
//                    
//                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
//                    if(changeaccount>0)
//                    {
//                        $("#payChangeAccount").text(changeaccount.toFixed(2));
//                    }else{
//                        $("#payChangeAccount").text("0.00");
//                    }
//                }
                }else if(selectid=="pay_others_detail")
                {                    
                    var spanid=$(".edit_span_select").attr("spanid");
                    var spanvalue=$("#"+spanid).text();
                    //alert(spanid);alert(spanvalue);
                    if(spanvalue=="0.00" || spanvalue=="0" || spanvalue=="00")
                    {
                        return false;
                    }
                    if(spanvalue.length==1)
                    {
                        $("#"+spanid).text("0.00");
                    }else{
                        $("#"+spanid).text(spanvalue.substr(0,spanvalue.length-1));
                    }
                    
                    $("#payOthers").html((parseFloat($("#payOthers").text().replace(",",""))+parseFloat($("#"+spanid).text().replace(",",""))-parseFloat(spanvalue.replace(",",""))).toFixed(2));
                    
                    var otherdetail=$("#payOthers").attr("detail");
                    spanvalue=$("#"+spanid).text();
                    var spanlid=$("#"+spanid).attr("lid");
                    var reg="[0-9]{10},([0-9.])*";
                    strdetail = otherdetail.replace(new RegExp(reg,"g"),function(word){
                        var newword=word.split(",");
                        if(newword[0]==spanlid)
                        {
                            var retval=newword[0]+","+spanvalue;
                            //alert(retval);
                            return retval;
                        }else{
                            return word;
                        }                       
                    });
                    $("#payOthers").attr("detail",strdetail);
                    
                    $("#payRealityAccount").html((parseFloat(payUnionAccount.replace(",",""))+parseFloat($("#payOthers").text().replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }
            });

            $('#pay_clearall').on(event_clicktouchstart,function(){
                var selectid=$(".edit_span_select").attr("selectid");
                var payOriginAccount=$("#payOriginAccount").text();
                var payHasAccount=parseFloat($("#order_has_pay").text().replace(",",""));
                var productDisTotal=$("#productDisTotal").val();
                var payDiscountAccount=$("#payDiscountAccount").text();
                var payMinusAccount=$("#payMinusAccount").text();
                var cancel_zero=$("#cancel_zero").hasClass("edit_span_select_zero");
                var payShouldAccount=$("#payShouldAccount").text();
                var payRealityAccount=$("#payRealityAccount").text();
                var payCashAccount=$("#payCashAccount").text();
                var payMemberAccount=$("#payMemberAccount").text();
                var payUnionAccount=$("#payUnionAccount").text();
                var payOthers=$("#payOthers").text();
                
                if(selectid=="discount")
                {   
                        
                        $("#payDiscountAccount").text("100%");
                        $("#payDiscountAccount").attr("disid","0000000000");
                        $("#payDiscountAccount").attr("disnum",1);
                        $("#payDiscountAccount").attr("dismoney","0.00");
                        payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                        productDisTotal=parseFloat($("#productDisTotal").val().replace(",",""));
                        payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                        payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                        $("#payShouldAccount").text(((payOriginAccount-productDisTotal)+productDisTotal*payDiscountAccount/100 - payMinusAccount-payHasAccount).toFixed(2));
                        if(cancel_zero)
                        {
                            payShouldAccount=$("#payShouldAccount").text();
                            $("#payCancelZero").text("0"+payShouldAccount.substr(payShouldAccount.indexOf("."),3));
                            payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                            $("#payShouldAccount").text(payShouldAccount);
                        }
                        var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                        if(changeaccount>0)
                        {
                            $("#payChangeAccount").text(changeaccount.toFixed(2));
                        }else{
                            $("#payChangeAccount").text("0.00");
                        }
                  
                }else if(selectid=="minus")
                {
                    $("#payMinusAccount").text("0.00");
                    //$("#payDiscountAccount").text("100%");
                    payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                    productDisTotal=parseFloat($("#productDisTotal").val().replace(",",""));
                    payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                    payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                    var shouldpaytemp=(payOriginAccount-productDisTotal)+productDisTotal*payDiscountAccount/100 - payMinusAccount-payHasAccount;
                    if(shouldpaytemp>0)
                    {
                        $("#payShouldAccount").text(shouldpaytemp.toFixed(2));
                    }else{
                        $("#payShouldAccount").text("0.00");
                    }
                    if(cancel_zero)
                    {
                        payShouldAccount=$("#payShouldAccount").text();
                        $("#payCancelZero").text("0"+payShouldAccount.substr(payShouldAccount.indexOf("."),3));
                        payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                        $("#payShouldAccount").text(payShouldAccount);
                    }
                    var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_cash")
                {
                    var offvalue=parseFloat(payShouldAccount.replace(",","")) - parseFloat(payRealityAccount.replace(",",""))
                    if(parseFloat($("#payCashAccount").text().replace(",",""))==0)
                    {
                        if(offvalue>0)
                        {
                            $("#payCashAccount").text(offvalue.toFixed(2));
                        }
                    }else{
                        $("#payCashAccount").text("0.00");
                    }
                    $("#payRealityAccount").html((parseFloat($("#payCashAccount").text().replace(",",""))+parseFloat(payOthers.replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payUnionAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }else if(selectid=="pay_member_card")
                {
                    return false;
                }else if(selectid=="pay_union_card")
                {
                    var offvalue=parseFloat(payShouldAccount.replace(",","")) - parseFloat(payRealityAccount.replace(",",""))
                    if(parseFloat($("#payUnionAccount").text().replace(",",""))==0)
                    {
                        if(offvalue>0)
                        {
                            $("#payUnionAccount").text(offvalue.toFixed(2));
                        }
                    }else{
                        $("#payUnionAccount").text("0.00");
                    }
                    
                    $("#payRealityAccount").html((parseFloat($("#payUnionAccount").text().replace(",",""))+parseFloat(payOthers.replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
                    
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
//                }else if(selectid=="pay_others")
//                {
//                    var offvalue=parseFloat(payShouldAccount.replace(",","")) - parseFloat(payRealityAccount.replace(",",""))
//                    if(parseFloat($("#payOthers").text().replace(",",""))==0)
//                    {
//                        if(offvalue>0)
//                        {
//                            $("#payOthers").text(offvalue.toFixed(2));
//                        }
//                    }else{
//                        $("#payOthers").text("0.00");
//                    }
//                    
//                    $("#payRealityAccount").html((parseFloat($("#payOthers").text().replace(",",""))+parseFloat(payUnionAccount.replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
//                    
//                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
//                    if(changeaccount>0)
//                    {
//                        $("#payChangeAccount").text(changeaccount.toFixed(2));
//                    }else{
//                        $("#payChangeAccount").text("0.00");
//                    }
//                }
                }else if(selectid=="pay_others_detail")
                {                    
                    var spanid=$(".edit_span_select").attr("spanid");
                    var spanvalue=$("#"+spanid).text();
                    var offvalue=parseFloat(payShouldAccount.replace(",","")) - parseFloat(payRealityAccount.replace(",",""))
                    if(parseFloat(spanvalue.replace(",",""))==0)
                    {
                        if(offvalue>0)
                        {
                            $("#"+spanid).text(offvalue.toFixed(2));
                        }
                    }else{
                        $("#"+spanid).text("0.00");
                    }
                                        
                    $("#payOthers").html((parseFloat($("#payOthers").text().replace(",",""))+parseFloat($("#"+spanid).text().replace(",",""))-parseFloat(spanvalue.replace(",",""))).toFixed(2));
                    
                    var otherdetail=$("#payOthers").attr("detail");
                    spanvalue=$("#"+spanid).text();
                    var spanlid=$("#"+spanid).attr("lid");
                    var reg="[0-9]{10},([0-9.])*";
                    strdetail = otherdetail.replace(new RegExp(reg,"g"),function(word){
                        var newword=word.split(",");
                        if(newword[0]==spanlid)
                        {
                            var retval=newword[0]+","+spanvalue;
                            //alert(retval);
                            return retval;
                        }else{
                            return word;
                        }                       
                    });
                    $("#payOthers").attr("detail",strdetail);
                    
                    $("#payRealityAccount").html((parseFloat(payUnionAccount.replace(",",""))+parseFloat($("#payOthers").text().replace(",",""))+parseFloat(payMemberAccount.replace(",",""))+parseFloat(payCashAccount.replace(",",""))).toFixed(2));
                    var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                    if(changeaccount>0)
                    {
                        $("#payChangeAccount").text(changeaccount.toFixed(2));
                    }else{
                        $("#payChangeAccount").text("0.00");
                    }
                }
            });
            
            function fn_member_card_pay(){
                var nowcard=parseFloat($('#payMemberAccount').text().replace(",",""));
                var payShouldAccount=parseFloat($('#payShouldAccount').text().replace(",",""));
                var payRealityAccount=parseFloat($('#payRealityAccount').text().replace(",",""));
                $('#card_pay_span_money').text((payShouldAccount-payRealityAccount).toFixed(2));
                if(nowcard>0)
                {
                    //$('#card_pay_input_money').val(nowcard);
                    $('#card_pay_span_should').text(nowcard);
                }else{
                    if(payShouldAccount-payRealityAccount>0)
                    {
                        //$('#card_pay_input_money').val((payShouldAccount-payRealityAccount).toFixed(2));
                        $('#card_pay_span_should').text((payShouldAccount-payRealityAccount).toFixed(2));
                    }else{
                        //$('#card_pay_input_money').val("0.00");
                        $('#card_pay_span_should').text("0.00");
                    }
                }
                //member_card_pop_flag=1;
                if(layer_index_membercard!=0)
                    return;
                layer_index_membercard=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['30%', 'auto'],
                     content: $('#membercardInfo'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
                         layer_index_membercard=0;
        //                        this.content.show();
        //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                     }
                 });
                 
            };
            
            $(document).on('keydown',function(event){
                //alert(event.which);
                
                if(layer_index_membercard==0)
                    return;
                event.preventDefault();
                var obj=$(".edit_span_select_member");
                //var selectinput=obj.find("input");
                var selectspan=obj.find("span");
                var keycode=parseInt(event.which)-48;
                if(keycode==142 ||( keycode>=0 && keycode <10))
                {
                    var addkeycode=keycode;
                    if(keycode==142)
                    {
                        keycode=".";
                    }
                    var selectactual=selectspan.attr("actual");
                    if(selectspan.attr("id")=="card_pay_span_password")
                    {
                        selectspan.text(selectspan.text()+"*");
                    }else{
                        selectspan.text(selectactual+keycode);
                    }
                    selectspan.attr("actual",selectactual+keycode);                    
                }
                if(event.which==8)
                {
                    //alert(888)
                    var selectspantext=selectspan.text();
                    var selectspanactual=selectspan.attr("actual");
                    selectspan.text(selectspantext.substring(0,selectspantext.length-1));
                    selectspan.attr("actual",selectspanactual.substring(0,selectspanactual.length-1));
                    //event.preventDefault();
                }
            });
            
            $('#member_card_pay').on(event_clicktouchstart,function(){
                var cardmoney=$('#card_pay_span_should').text();
                var cardno=$('#card_pay_span_card').text();
                var cardpassword=$('#card_pay_span_password').attr("actual");
                //alert(cardpassword);
                //确认密码
                //如果密码正确，带回数据，并关闭
                $.get('<?php echo $this->createUrl(
                    'defaultOrder/memberCardPassword',array('companyId'=>$this->companyId));?>/passWord/'+cardpassword+"/cardno/"+cardno,
                    function(data){
                        //alert(data.msg);
                        if(data.status)
                        {
                            $('#payMemberAccount').text(cardmoney);
                            $('#payMemberAccount').attr("cardno",cardno);
                            $('#payMemberAccount').attr("cardtotal",data.msg);
                            $("#payRealityAccount").text((parseFloat($("#payCashAccount").text().replace(",",""))+parseFloat($("#payMemberAccount").text().replace(",",""))+parseFloat($("#payUnionAccount").text().replace(",",""))).toFixed(2));
                            var changeaccount=parseFloat($("#payRealityAccount").text().replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                            if(changeaccount>0)
                            {
                                $("#payChangeAccount").text(changeaccount.toFixed(2));
                            }else{
                                $("#payChangeAccount").text("0.00");
                            }
                            layer.close(layer_index_membercard);
                            layer_index_membercard=0;
                            //member_card_pop_flag=0;
                        }else{
                            alert("密码错误");
                            //$('#card_pay_input_password').clear();
                        }
                    },'json');               
            });
            
            $('#member_card_pay_close').on(event_clicktouchstart,function(){
                layer.close(layer_index_membercard);
                layer_index_membercard=0;
                //member_card_pop_flag=0;
            });
            
            $('#pay_btn').on(event_clicktouchstart,function(){
                var notpaydetail="";
                var payCashAccount= parseFloat($("#payCashAccount").text().replace(",","")) - parseFloat($("#payChangeAccount").text().replace(",",""));
                if(payCashAccount<0)
                {
                    alert("金额有误");
                    //ispaybuttonclicked=false;
                    return false;
                }
                 //改变order实收，打折等注释
                var ordermemo="";
                notpaydetail=$("#payDiscountAccount").attr("disid")+"|"+
                                $("#payDiscountAccount").attr("disnum")+"|"+
                                $("#payDiscountAccount").attr("dismoney")+"|";
                var payDiscountAccount=$("#payDiscountAccount").text()
                if(payDiscountAccount!="100%")
                {
                    ordermemo=ordermemo+" 折扣"+payDiscountAccount;
                }
                var payMinusAccount=$("#payMinusAccount").text()
                if(payMinusAccount!="0.00")
                {
                    ordermemo=ordermemo+" 优惠"+payMinusAccount;
                }
                notpaydetail=notpaydetail+payMinusAccount+"|";
                if($("#cancel_zero").hasClass("edit_span_select_zero"))
                {
                    ordermemo=ordermemo+" 抹零";
                }
                notpaydetail=notpaydetail+$("#payCancelZero").text();
                //alert(notpaydetail);return;
                 //存数order order_pay 0现金，4会员卡，5银联                         
                 //写入会员卡消费记录，会员卡总额减少
                var orderid=$(".selectProduct").attr("orderid");
                var padid="0000000046";
                if (typeof Androidwymenuprinter == "undefined") {
                    alert("找不到PAD设备");
                    //return false;
                }else{
                    var padinfo=Androidwymenuprinter.getPadInfo();
                    padid=padinfo.substr(10,10);
                }
                //var payCashAccount=$("#payCashAccount").text();
                //var payChangeAccount=$("#payChangeAccount").text();
                var payShouldAccount=$("#payShouldAccount").text();
                var payOriginAccount=$("#payOriginAccount").text();
                var payHasAccount=parseFloat($("#order_has_pay").text().replace(",",""));
                var payRealityAccount=$("#payRealityAccount").text();
                var payMemberAccount=$("#payMemberAccount").text();
                var cardno=$("#payMemberAccount").attr("cardno");
                var cardtotal=$('#payMemberAccount').attr("cardtotal");
                var payUnionAccount=$("#payUnionAccount").text();
                var payOthers=$("#payOthers").text();
                var otherdetail=$("#payOthers").attr("detail");
                if(parseFloat(payRealityAccount.replace(",","")) < parseFloat(payShouldAccount.replace(",","")))
                {
                    alert("收款不够");
                    ispaybuttonclicked=false;
                    return false;
                }
                var typeId=$('li[class="tabSite slectliclass"]').attr('typeid');
                var isaccount=false;
                layer.close(layer_index2);
                layer_index2=0;
                //var url="<?php echo $this->createUrl('defaultOrder/orderAccount',array('companyId'=>$this->companyId));?>/orderid/"+orderid+"/orderstatus/4/cardno/"+cardno;
                public_account_sendjson='paycashaccount='+payCashAccount+
                            '&paymemberaccount='+payMemberAccount+
                            '&payunionaccount='+payUnionAccount+
                            '&ordermemo='+ordermemo+
                            '&payshouldaccount='+payShouldAccount+
                            '&payothers='+payOthers+
                            '&payoriginaccount='+payOriginAccount+
                            '&payotherdetail='+otherdetail+
                            '&notpaydetail='+notpaydetail+
                            '&cardtotal='+cardtotal; 
               var loadsendjson={'paycashaccount':payCashAccount,
                                'paymemberaccount':payMemberAccount,
                                'payunionaccount':payUnionAccount,
                                'ordermemo':ordermemo,
                                'payshouldaccount':payShouldAccount,
                                'payothers':payOthers,
                                'payoriginaccount':payOriginAccount,
                                'payotherdetail':otherdetail,
                                'notpaydetail':notpaydetail,
                                'cardtotal':cardtotal};
                var urlsure="<?php echo $this->createUrl('defaultOrder/orderAccountSure',array('companyId'=>$this->companyId));?>/orderId/"+orderid+"/padId/"+padid+"/orderstatus/4/cardno/"+cardno;
                
                $('#orderaccountsure').load(urlsure,loadsendjson);
                if(layer_index_orderaccountsure!=0)
                {
                    return;
                }
                layer_index_orderaccountsure=layer.open({
                     type: 1,
                     shade: false,
                     title: false, //不显示标题
                     area: ['40%', '30%'],
                     content: $('#orderaccountsure'), //捕获的元素
                     cancel: function(index){
                         layer.close(index);
                         layer_index_orderaccountsure=0;
                   }
                }); 
            });
            
            $('#other-btn').on(event_clicktouchstart,function(){
                 bootbox.confirm("<?php echo yii::t('app','你确定切换到其他支付方式吗？');?>", function(result) {
                        if(result){
                                openaccount('0');
                        }
                 });
            });          
            
            $('.selectDiscount').on(event_clicktouchstart,function(){
                var spanOriginPrice=$("#spanOriginPrice").text();
                var id=$(this).attr('id');
                var discountOrig=$("#spanProductDiscountOrig").text();
                var nowpriceOrig=$("#spanNowPriceOrig").text();
                //alert(spanOriginPrice);alert(discountOrig);alert(nowpriceOrig);
                $(".selectDiscount").removeClass("active");
                //$(".selectDiscount[id='checkboxGiving']").addClass("active");
                if(id=="checkboxDiscount")
                {
                    if(discountOrig.lastIndexOf("%")>="0")
                    {
                        $("#spanProductDiscount").text(discountOrig);
                        $("#spanNowPrice").text(nowpriceOrig);
                    }else{
                        $("#spanProductDiscount").text("100%");
                        $("#spanNowPrice").text(spanOriginPrice);
                    }
                }else if(id=="checkboxMinus"){
                    //alert(discountOrig.lastIndexOf("%"));
                    if(discountOrig.lastIndexOf("%")>="0")
                    {
                        $("#spanProductDiscount").text("0.00");
                        $("#spanNowPrice").text(spanOriginPrice);
                    }else{
                        $("#spanProductDiscount").text(discountOrig);
                        $("#spanNowPrice").text(nowpriceOrig);
                    }                    
                }else{                    
                    $("#spanProductDiscount").text(spanOriginPrice);
                    $("#spanNowPrice").text("0.00");
                }
            });
            
            $('#btnclosesite').on(event_clicktouchstart,function(){
                var statu = confirm("<?php echo yii::t('app','确定撤台吗？');?>");
                if(!statu){
                    return false;
                } 
               //var sid = $(this).attr('sid');
               $.ajax({
                    'type':'POST',
                    'dataType':'json',
                    'data':{"sid":gsid,"companyId":'<?php echo $this->companyId; ?>',"istemp":gistemp},
                    'url':'<?php echo $this->createUrl('defaultSite/closesite',array());?>',
                    'success':function(data){
                            if(data.status == 0) {
                                    alert(data.message);
                                    return false;
                            } else {
                                    alert(data.message);
                                    //$('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId));?>/typeId/'+gtypeid);
                                    //更改状态
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-yellow");
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-blue");
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-green");
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").attr("status","7"); 
                                    $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").find("div").css("display","");
                                    sitevisible();
                                    //$('#portlet-button').modal('hide');
                                    //$("#tab_sitelist").hide();
                            }
                    },
                        'error':function(e){
                            return false;
                        }
                });
                //return false;                               
           });

           $('#btnswitchsite').on(event_clicktouchstart,function(){
               //var sid = $(this).attr('sid');
               var statu = confirm("<?php echo yii::t('app','确定换台吗？');?>");
                if(!statu){
                    return false;
                }  
//                $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'op'=>'switch'));?>/typeId/'+gtypeid+"/sistemp/"+gistemp+"/ssid/"+gsid+"/stypeId/"+gtypeid);
                gop='switch';
                gsistemp=gistemp;
                gssid=gsid;
                gstypeid=gtypeid;
                sitevisible();
           });                           

           $('#btnunionsite').on(event_clicktouchstart,function(){
               //var sid = $(this).attr('sid');
               var statu = confirm("<?php echo yii::t('app','确定并台吗？');?>");
                if(!statu){
                    return false;
                }  
//                $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'op'=>'union'));?>/typeId/'+gtypeid+"/sistemp/"+gistemp+"/ssid/"+gsid+"/stypeId/"+gtypeid);
                //$('#portlet-button').modal('hide');
                gop='union';
                gsistemp=gistemp;
                gssid=gsid;
                gstypeid=gtypeid;
                sitevisible();
           });
           
           
        //库存提示
        function sell_off(do_data) {
            //alert(do_data);
//            var data = eval('(' + do_data + ')');
//            	//for(var item in data.do_data){
//            	for(var item in data){
//                    $('div.blockCategory[product-id="'+data[item].product_id+'"]').attr('store',data[item].num);
//                    if(parseInt(data[item].num)==0){
//                    	$('div.blockCategory[product-id="'+data[item].product_id+'"]').find('.sellOff').remove();
//                    	var str = '<div class="sellOff sellOut"><?php echo yii::t('app',"已售完");?></div>';
//                    	$('div.blockCategory[product-id="'+data[item].product_id+'"]').find('a').append(str);
//                    }else if(parseInt(data[item].num) > 0){
//                    	$('div.blockCategory[product-id="'+data[item].product_id+'"]').find('.sellOff').remove();
//                    	var str = '<div class="sellOff">仅剩<br/>'+data[item].num+'份</div>';
//                    	$('div.blockCategory[product-id="'+data[item].product_id+'"]').find('a').append(str);
//                    }else{
//                    	$('div.blockCategory[product-id="'+data[item].product_id+'"]').find('.sellOff').remove();
//                    }
//            	}             
       }                
	</script>

