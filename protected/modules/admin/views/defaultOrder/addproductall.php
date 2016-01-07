
<style>

.navigation {
    width:200px;
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




                       			                                              
                                                
                                                            <div class="modal-body" style="position:absolute;height: 97%;width:100%;">
                                                                <!--<div style="position:absolute; width: 97%;height: 98%; background: #fff;color: #555555;;border-radius: 0 !important;box-sizing: border-box;">-->
                                                                    <div style="width:100%;height:100%;">
                                                                        
                                                                        <div class="navigation" style="width:18%;margin-right:1%;">
                                                                            <span style="color:#000088;font-size: 1.5em;">一级分类</span>
                                                                            <ul class="firstcategory">
                                                                                <li><a lid="all" href="#">全部</a></li>
                                                                                <li><a lid="special" href="#">固定分类</a></li>
                                                                                <?php foreach ($categories as $categorie): 
                                                                                    if($categorie->pid=="0000000000"):?>
                                                                                    <li><a lid="<?php echo $categorie->lid; ?>" href="#"><?php echo $categorie->category_name; ?></a></li>
                                                                                <?php 
                                                                                    endif;
                                                                                endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                        
                                                                        <div class="navigation" style="width:18%;margin-right:1%;">
                                                                            <span style="color:#000088;font-size: 1.5em;">二级分类</span>
                                                                            <ul class="secondcategory">
                                                                                <li><a lid="set" pid="special" href="#">套餐</a></li>
                                                                                <li><a lid="specialprice" pid="special" href="#">特价菜</a></li>
                                                                                <?php foreach ($categories as $categorie): 
                                                                                    if($categorie->pid!="0000000000"):?>
                                                                                    <li><a lid="<?php echo $categorie->lid; ?>" pid="<?php echo $categorie->pid; ?>" href="#"><?php echo $categorie->category_name; ?></a></li>
                                                                                <?php 
                                                                                    endif;
                                                                                endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                        
                                                                        <div class="navigation" style="width:18%;margin-right:1%;">
                                                                            <span style="color:#000088;font-size: 1.5em;">菜品名称</span>
                                                                            <ul class="productstyle">
                                                                                <?php foreach ($productSets as $productSet): 
                                                                                    $setdetail="";
                                                                                        foreach ($productSet->productsetdetail as $psd): 
                                                                                            $tempdetail="gp".$psd->group_no.",".$psd->product_id.",".$psd->is_select.",".$psd->number.",".$psd->price.",".$pn[$psd->product_id];
                                                                                            if(empty($setdetail))
                                                                                                $setdetail=$tempdetail;
                                                                                            else
                                                                                                $setdetail.=";".$tempdetail;                                                                                            
                                                                                        endforeach;
                                                                                    ?>
                                                                                    <li><a lid="<?php echo $productSet->lid; ?>" setselect="<?php echo $setdetail; ?>"  cid="set" price="0" href="#"><?php echo $productSet->set_name; ?></a></li>
                                                                                <?php endforeach;?>
                                                                                <?php foreach ($products as $product): 
                                                                                    if($product->is_show=="1"):
                                                                                    ?>
                                                                                    <li><a lid="<?php echo $product->lid; ?>" setselect="0" cid="<?php echo $product->category_id; ?>" price="<?php echo $product->original_price; ?>" href="#"><?php echo $product->product_name; ?></a></li>
                                                                                <?php 
                                                                                   endif;
                                                                                endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="navigation" style="width:4%;margin-right:1%;margin-top: 15%;">
                                                                            <img id="plusproducticon" style="width:60%;" src="<?php echo Yii::app()->request->baseUrl;?>/img/product/icon_cart.png">
                                                                            <br><br>
                                                                            <img id="minusproducticon" style="width:60%;" src="<?php echo Yii::app()->request->baseUrl;?>/img/product/icon_cart_m.png">
                                                                            
                                                                        </div>
                                                                        <div class="navigation" style="width:18%;margin-right:1%;">
                                                                            <span style="color:#000088;font-size: 1.5em;">已选择菜品</span>
                                                                            <ul class="selectedproduct">
                                                                                
                                                                            </ul>
                                                                        </div>
                                                                        <div class="navigation" style="width:18%;height:80%;">
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
                                                                        <div style="position:absolute;width: 19%;height: 15%;bottom:2%;right:2%;">
                                                                            <button style="float:right;margin-top: 5%;" type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
                                                                            <input style="float:right;margin-right: 5%;margin-top: 5%;height:" type="button" class="btn blue" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
                                                                            
                                                                        </div>
                                                                    </div>                                                                                                                                       
                                                                <!--</div>-->                                                        
                                                </div>
                                                <?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'orderProductForm',
                                                        'action' => $this->createUrl('defaultOrder/addProductAll',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$orderId)),
                                                        'enableAjaxValidation'=>true,
                                                        'enableClientValidation'=>true,
                                                        'clientOptions'=>array(
                                                                'validateOnSubmit'=>false,
                                                        ),
                                                        'htmlOptions'=>array(
                                                                'class'=>'form-horizontal'
                                                        ),
                                                )); ?>  
                                                <input class="form-control" name="selectproductlist" id="selectproductlistid" type="hidden" value="">                                     

                                                <?php $this->endWidget(); ?>                
                    <script type="text/javascript">
                        $('.firstcategory').on('click','li',function(){
                            //alert($(this).find('a').attr("lid"));
                            $(this).parent().find('li').removeClass('slectliclass');
                            $(this).addClass('slectliclass');
                            if($(this).find('a').attr("lid")=="all")
                            {
                                $('.secondcategory').find('li').removeClass('slectliclass');
                                $('.productstyle').find('li').removeClass('slectliclass');
                                $('.secondcategory').find('li').show();
                                $('.productstyle').find('li').show();
                            }else{
                                var pid=$(this).find('a').attr("lid")
                                $('.secondcategory').find('li').hide();
                                $('.productstyle').find('li').hide();
                                $('.secondcategory').find('a[pid='+pid+']').parent().each(
                                    function(){
                                     $(this).show();
                                     $(this).removeClass('slectliclass');
                                     var cid=$(this).find('a').attr("lid");
                                     //alert(cid);
                                     $('.productstyle').find('a[cid='+cid+']').parent().show();
                                     $('.productstyle').find('li').removeClass('slectliclass');
                                    });
                                
                            }
                        });
                        
                        $('.secondcategory').on('click','li',function(){
                            //alert($(this).find('a').attr("pid"));
                            $(this).parent().find('li').removeClass('slectliclass');
                            $(this).addClass('slectliclass');
                            $('.productstyle').find('li').hide();
                            var cid=$(this).find('a').attr("lid");
                            //alert(cid);
                            $('.productstyle').find('a[cid='+cid+']').parent().show();
                            $('.productstyle').find('li').removeClass('slectliclass');
                        });
                        
                        $('.productstyle').on('click','li',function(){
                            //alert($(this).find('a').attr("pid"));
                            $(this).parent().find('li').removeClass('slectliclass');
                            $(this).addClass('slectliclass');
                        });
                        
                        $('.selectedproduct').on('click','li',function(){
                            //alert($(this).find('a').attr("pid"));
                            $(this).parent().find('li').removeClass('slectliclass');
                            $(this).addClass('slectliclass');
                            var obja=$(this).find('a');
                            var amount=obja.attr('amount');
                            var zhiamount=obja.attr('zhiamount');
                            var price=obja.attr('price');
                            var setselect=obja.attr("setselect");
                            var isgiving=obja.attr("isgiving");
                            var productstatus=obja.attr("productstatus");//添加cf
                            if(setselect=="0")
                            {
                                $("#product-set-detail").hide();
                                $("#product-detail").show();
                                $("#product-detail-price").val(price);
                                $("#product-detail-isgiving").val(isgiving);
                                $("#product-detail-amount").text(amount);
                                $("#product-detail-zhiamount").text(zhiamount);
                                $("#product-detail-productstatus").val(productstatus);//添加cf
                            }else{
                                $("#product-detail").hide();
                                $("#product-set-detail").show();
                                $("#product-set-detail").find("div").remove();
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
                                        instr='<div class="btn-group" groupid='+setdetail[0]+ ' data-toggle="buttons" style="width:95%;margin-top:2px;margin-right:10px;border: 2px solid red;background: rgb(245,230,230);"> '                                                                                       
                                                    +'<label style="width:95%;margin-right: 2px;margin-left:2px;" productid='+setdetail[1]+ ' class="selectTaste btn btn-default '+active+'">'
                                                       +' <input type="checkbox" class="toggle">' +setdetail[5]+"  "+setdetail[3]+" X "+setdetail[4]
                                                    + '</label>'                                                                                    
                                                + '</div>';
                                        $("#product-set-detail").append(instr);
                                    }else{
                                        instr='<label style="width:95%;margin-right: 2px;margin-left:2px;" productid='+setdetail[1]+ ' class="selectTaste btn btn-default '+active+'">'
                                                       +' <input type="checkbox" class="toggle">' +setdetail[5]+"  "+setdetail[3]+" X "+setdetail[4]
                                                    + '</label>';
                                        btngroup.append(instr);
                                    }
                                });
                            }
                        });
                        
                        $('#plusproducticon').on(event_clicktouchend,function(){
                            var obj=$('.productstyle').find('li[class="slectliclass"]');
                            var obja=obj.find('a');
                            var lid=obja.attr('lid');
                            //alert(lid);
                            if(typeof lid=="undefined")
                            {
                                return false;
                            }
                            var issel=$('.selectedproduct').find('a[lid='+lid+']').attr("lid");
                            //alert(issel);
                            if(typeof issel!="undefined")
                            {
                                alert("菜品已经添加！");
                                return false;
                            }
                            var cid=obja.attr('cid');
                            var price=obja.attr('price');
//                            var amount=obja.attr('amount');
//                            var zhiamount=obja.attr('zhiamount');                            
//                            var isgiving=obja.attr("isgiving");
                            var pname=obja.text();
                            var setselect=obja.attr("setselect");
                            
                            $('.selectedproduct').find("li").removeClass("slectliclass");
                            var strli="<li class='slectliclass'><a lid="+lid+" cid="+cid+" price="+price+" setselect="+setselect+" amount=1 zhiamount=0 isgiving=0 href='#'>"+pname+"</a></li>";
                            //alert(strli);
                            $('.selectedproduct').append(strli);
                            if(setselect=="0")
                            {
                                $("#product-set-detail").hide();
                                $("#product-detail").show();
                                $("#product-detail-price").val(price);
                                $("#product-detail-isgiving").val('0');
                                $("#product-detail-amount").text('1');
                                $("#product-detail-zhiamount").text('0');
                                $("#product-detail-productstatus").val('0');//添加cf
                            }else if(setselect!=""){
                                $("#product-detail").hide();
                                $("#product-set-detail").show();
                                $("#product-set-detail").find("div").remove();
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
                                        instr='<div class="btn-group" groupid='+setdetail[0]+ ' data-toggle="buttons" style="width:95%;margin-top:2px;margin-right:10px;border: 2px solid red;background: rgb(245,230,230);"> '                                                                                       
                                                    +'<label style="width:95%;margin-right: 2px;margin-left:2px;" productid='+setdetail[1]+ ' class="selectTaste btn btn-default '+active+'">'
                                                       +' <input type="checkbox" class="toggle">' +setdetail[5]+"  "+setdetail[3]+" X "+setdetail[4]
                                                    + '</label>'                                                                                    
                                                + '</div>';
                                        $("#product-set-detail").append(instr);
                                    }else{
                                        instr='<label style="width:95%;margin-right: 2px;margin-left:2px;" productid='+setdetail[1]+ ' class="selectTaste btn btn-default '+active+'">'
                                                       +' <input type="checkbox" class="toggle">' +setdetail[5]+"  "+setdetail[3]+" X "+setdetail[4]
                                                    + '</label>';
                                        btngroup.append(instr);
                                    }
                                });
                            }
                            obj.removeClass("slectliclass");
                        });
                        
                        $('#minusproducticon').on(event_clicktouchend,function(){
                            $('.selectedproduct').find('li[class="slectliclass"]').remove();
                            $("#product-set-detail").hide();
                            $("#product-detail").hide();
                        });
                        
                        function change1(word)
                        {
                            return word.substr(0,word.length-2)+"1,";
                        }
                        
                        function change0(word)
                        {
                            return word.substr(0,word.length-2)+"0,";
                        }
                        
                        $('.selectTaste').live('click',"label",function(){
                            $(this).parent().find("label").removeClass("active");
                            var groupno=$(this).parent().attr("groupid");
                            var productid=$(this).attr("productid");
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            var setselect=obja.attr("setselect");
                            var reg=groupno+",[0-9]{10},1,";
                            str = setselect.replace(new RegExp(reg,"g"),change0);
                            var reg2=groupno+","+productid+",0,";
                            str = str.replace(new RegExp(reg2,"g"),change1);
                            obja.attr("setselect",str);
                        });                        
                        
                        $('#product-detail-isgiving').on("change",function(){
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            obja.attr("isgiving",$(this).val());
                        }); 
                        
                        $('#product-detail-price').on("change",function(){
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            obja.attr("price",$(this).val());
                        });
                        
                        $('#product-detail-amount').on("change",function(){
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            obja.attr("amount",$(this).text());
                        });
                        
                        $('#product-detail-zhiamount').on("change",function(){
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            obja.attr("zhiamount",$(this).text());
                        });
                        
                        $('#create_btn').on(event_clicktouchend,function(){
                            var sendlist="";
                            var setselect="";
                            var productselect="";
                            var setlid="";
                            $('.selectedproduct').find('a').each(function(){
                                setselect=$(this).attr("setselect");
                                //alert(setselect);
                                if(setselect=="0")
                                {
                                    productselect="0000000000,"+$(this).attr("lid")+","+$(this).attr("amount")
                                        +","+$(this).attr("zhiamount")+","+$(this).attr("price")
                                        +","+$(this).attr("isgiving"); 
                                    if(sendlist=="")
                                    {
                                        sendlist=productselect;
                                    }else{
                                        sendlist+=";"+productselect;
                                    }
                                }else{
                                    setlid=$(this).attr("lid");
                                    var reg="gp"+"[0-9]{1,},[0-9]{10},1,[0-9]{1,},[0-9\.]{1,}";
                                    setdarr = setselect.match(new RegExp(reg,"g"));
                                    $.each(setdarr,function(akey,avalue){
                                        //alert(avalue);
                                        var aav=avalue.split(",");
                                        productselect=setlid+","+aav[1]+","+aav[3]
                                        +",0"+","+aav[4]
                                        +",0";
                                        if(sendlist=="")
                                        {
                                            sendlist=productselect;
                                        }else{
                                            sendlist+=";"+productselect;
                                        }
                                    });                                    
                                }                                
                            });
                            //alert(sendlist);
                            $('#selectproductlistid').val(sendlist);
                            //return false;
//                            $.ajax({
//                                url:$('#orderProductForm').attr('action'),
//                                type:'POST',
//                                data:"selectproductlist="+sendlist,
//                                async:false,
//                                dataType: "json",
//                                success:function(msg){
//                                     
//                                },
//                                error: function(msg){
//                                    alert("error");
//                                }
//                            });
                            $('#orderProductForm').submit();
                           
                        });
                        
                        $('#product-detail-amount-m1').on(event_clicktouchstart,function(){
                            var num = parseFloat($("#product-detail-amount").text());
                            if(num >= 1){
                                    num = num - 1;
                            }
                            $("#product-detail-amount").text(num);
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            obja.attr("amount",num);
                        });
                        
                        $('#product-detail-amount-a1').on(event_clicktouchstart,function(){
                            var num = parseFloat($("#product-detail-amount").text());
                            num = num + 1;
                            $("#product-detail-amount").text(num);
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            obja.attr("amount",num);
                        });
                        
                        $('#product-detail-amount-ah').on(event_clicktouchstart,function(){
                            var num = parseFloat($("#product-detail-amount").text());
                            num = num + 0.5;
                            $("#product-detail-amount").text(num);
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            obja.attr("amount",num);
                        });
                        
                        $('#product-detail-zhiamount-m1').on(event_clicktouchstart,function(){
                            var num = parseFloat($("#product-detail-zhiamount").text());
                            if(num >= 1){
                                    num = num - 1;
                            }
                            $("#product-detail-zhiamount").text(num);
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            obja.attr("zhiamount",num);
                        });
                        
                        $('#product-detail-zhiamount-a1').on(event_clicktouchstart,function(){
                            var num = parseFloat($("#product-detail-zhiamount").text());
                            num = num + 1;
                            $("#product-detail-zhiamount").text(num);
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            obja.attr("zhiamount",num);
                        });
                        
                        $('#product-detail-zhiamount-ah').on(event_clicktouchstart,function(){
                            var num = parseFloat($("#product-detail-zhiamount").text());
                            num = num + 0.5;
                            $("#product-detail-zhiamount").text(num);
                            var obja=$('.selectedproduct').find('li[class="slectliclass"]').find('a');
                            obja.attr("zhiamount",num);
                        });
                    </script>