<style>
	.pbom{
		width: 98%;
		height: auto;
		margin-left: 1%;
		border: 1px solid pink;
	}
	.pbomhead{
		width: 98%;
		margin-left: 1%;
		border-bottom: 1px solid silver;
	}
	.pbomheadtitle{
		padding: 2px 8px;
		float: left;
		font-size: 18px;
	}
	.pbombody{
		width:98%;
		height: 400px;
		margin-left: 1%;
		border: 1px solid red;
		border-top: none;
	}
	.pbombody .matcat{
		width: 15%;
		height: 100%;
		float: left;
		border-right: 1px solid silver;
	}
	.pbombody .matcat .matcathead{
		font-size: 18px;
		font-weight: 600;
		text-align: center;
		padding: 2px 4px;
	}
	.pbombody .matcat .matcatbody{
		font-size: 16px;
		text-align: center;
	}
	.pbombody .matcat .matcatbody div{
		width: 100%;
		font-size: 15px;
		text-align: center;
		border-top: 1px solid silver;
	}
	.pbombody .matcat .matcatbody span{
		width: 100%;
	}
	.pbombody .material{
		width: 32%;
		height: 100%;
		float: left;
		border-right: 1px solid silver;
	}

	.pbombody .material .materialhead{
		font-size: 18px;
		text-align: center;
		padding: 2px 4px;
		font-weight: 600;
		border-bottom: 1px solid silver;
	}
	.pbombody .material .materialbody{
		width: 80%;
		height: 70%;
		font-size: 16px;
		margin-left: 10%;
		overflow: auto;		
	}
	.pbombody .material .materialend{
		width: 100%;
		font-size: 16px;
		border-top: 1px solid silver;
	}
	.matersubmit{
		margin-top: 5%;
		float: right;
	}
         .matersubmit_all{
		margin-top: 5%;
		float: right;
                margin-left: 7px;
	}
        .matersubmit_all_no{
		margin-top: 5%;
		float: right;
                margin-left: 7px;
	}
	.matersubmit button{
		padding: 4px 6px;
	}
        .matersubmit_all button{
		padding: 4px 6px;
	}
        .matersubmit_all_no button{
		padding: 4px 6px;
	}
        
	.pbombody .bom{
		width: 53%;
		height: 100%;
		float: left;
		border-right: 1px solid silver;
	}
	.pbombody .bom .bomhead{
		font-size: 18px;
		text-align: center;
		padding: 2px 4px;
		font-weight: 600;
		border-bottom: 1px solid silver;
	}
	.pbombody .bom .bombody{
		width: 96%;
		height: 70%;
		font-size: 14px;
		margin-left: 2%;
		overflow: auto;		
	}
	.pbombody .bom .bomend{
		width: 100%;
		font-size: 16px;
		border-top: 1px solid silver;
	}
	.mataction{
		background-color: rgba(54, 232, 247, 0.5);
	}
	.bodymaterial{
		height: 30px;
	}
	.bommaterial{
		height: 30px;
	}
	.bodymaterial .matename{
		width: 60%;
		height: 30px;
		line-height: 30px;
		float: left;	
		margin-left: 10px;		
	}
	.bodymaterial .matename label{
		width: 100%;
		height: 30px;
		line-height: 30px;
		margin-left: 10px;		
	}
	.bodymaterial .div1{
		width: 30px;
		float: left;
		font-size: 18px;
		line-height: 30px;
		text-align: center;
	}
	.bodymaterial .div2{
		float: left;
		margin-left: 10px;
	}
	.bommaterial .matename {
		width: 25%;
		height: 30px;
		font-size: 12px;
		line-height: 30px;
		float: left;	
		margin-left: 0px;		
	}
	.bommaterial .div1{
		width: 30px;
		float: left;
		font-size: 18px;
		line-height: 30px;
		text-align: center;
	}
	.bommaterial .div2{
		float: left;
		margin-left: 10px;
	}
	
	
	.bommaterial .div4{
		width: 10%;
		float: left;
		
	}
	.bommaterial .div4 input{
		width: 100%;
		float: left;
		
	}
	.bommaterial  input{
		width: 15%;
		float: left;
	}
        .amoount{
             margin:0px;
             padding:0px; 
        }
	.bommaterial .amount span{
		width: 27%;
		float: left;
		height: 30px;
		font-size: 12px;
		line-height: 30px;
		text-align: center;
              
	}
       .bommaterial .price span{
		width: 9%;
		float: left;
		font-size: 14px;
		line-height: 30px;
		text-align: center;		
	} 
	.pageend {
		margin-bottom: 10px;
	}
	.pageend .closediv {
		float: right;
		margin-right: 10px;
	}
	.pageend .closediv button{
		border: 1px solid silver;
	}
	.width40{
		width: 23% !important;
	}
	.clear{
		clear: both;
	}
	.uhide{
		display: none;
	}
	input[type="checkbox"]{
		width: 20px;
		height: 20px;
	}
</style>


<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','入库单');?></div>
                <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="pbom">

                    <div class="pbombody">
                            <div class="matcat">
                                    <div class="matcathead">原料分类</div>
                                    <div class="matcatbody">
                                            <div id="mater0" catid="-1" class="pbommaterial mataction "><span>--所有分类--</span></div>
                                            <?php if($models): ?>
                                            <?php foreach ($models as $model):?>
                                            <div id="<?php echo 'mater'.$model->lid;?>" catid="<?php echo $model->lid;?>" class="pbommaterial ">
                                                <span>
                                                <?php echo $model->category_name;?>
                                                </span>
                                            </div>
                                            <?php endforeach;?>
                                            <?php endif;?>
                                    </div> 
                            </div>
                    <div class="material"> 
                        <div class="materialhead">原料列表</div>
                        <div class="materialbody"> 
                                <?php if($materials): ?>
                                <?php $a = 1;?>
                                <?php foreach ($materials as $material):?>
                                <div id="<?php echo $material->lid;?>" catid="<?php echo $material->category_id;?>" class="bodymaterial">
                                        <div class="div1 num"><span><?php echo $a;?></span></div>
                                        <div class="div2">
                                                 <input id="check<?php echo $material->lid;?>" type="checkbox" stockname="<?php echo Common::getStockName($material->stock_unit_id);?>" matename="<?php echo $material->material_name;?>" mateprice="<?php echo isset($material->material_price->price)?$material->material_price->price:'';?>" class="check" value="<?php echo $material->lid;?>"  name="ids[]" />
                                        </div>
                                        <div class="matename ">
                                        <label for="check<?php echo $material->lid; ?>"><?php echo $material->material_name;?></label>
                                        </div>
                                </div>
                                <?php $a++;?>
                                <?php endforeach;?>
                                <?php endif;?>
                        </div>
                        <div class="materialend">
                            <div class="matersubmit_all_no"><button id="all_no_material">全不选>></button></div>
                            <div class="matersubmit_all"><button id="all_material">全选>></button></div>
                             <div class="matersubmit"><button id="add_material">添加>></button></div>

                        </div>
                    </div>
                        <div class="bom"> 
                                <div class="bomhead">入库单列表</div>
                                <div class="bombody"> 

                                </div>
                                <div class="bomend">
                                        <div class="matersubmit"><button id="add_matersubmit">确认并保存</button></div>
                                </div>
                        </div>
                        <div class="clear">
                            
                        </div>

                    </div>
                </div>
							
            </div>
        </div>
        <div class="pageend">
                <div class="closediv">
                        <button id="close_modal" type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','关 闭');?></button>
                </div>
                <div class="clear"></div>
        </div>
    </div>
							
</div>
		
			<!-- END PAGE CONTENT-->
<script type="text/javascript">
$(document).ready(function(){
    var prodid = '<?php echo $pid;?>';
    $(".matcatbody").on("click",".pbommaterial",function(){
        $(".pbommaterial").removeClass("mataction");
        $(this).addClass("mataction");
        var catid=$(this).attr("catid");
        if(catid >=0){
        	$(".bodymaterial").addClass("uhide");
        	$(".bodymaterial[catid='"+catid+"']").removeClass("uhide");
        }else{
        	$(".bodymaterial").removeClass("uhide");
        }
              
    });
    $('#add_material').on('click',function(){
    	var aa = document.getElementsByName("ids[]");
        var codep = new Array();
        var bombodydiv = '';
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                var materialid = aa[i].getAttribute("value");
                var matename = aa[i].getAttribute("matename");
                var mateprice = aa[i].getAttribute("mateprice");
                var stockname = aa[i].getAttribute("stockname");
                codep += materialid +','+ matename + ';';
                var bommatif = $('#bommat'+materialid).attr('bommatif');
                if(bommatif == undefined){
                	bommatif = 0;
                }
                if(bommatif==0){
                var bombodyhead = '<div id="bommat'+materialid+'" class="bommaterial" bommatif="1" bommatid="'+materialid+'" matename="'+matename+'">'
								+'<div class="div1 uhide"><span>'+i+'</span></div>'
								+'<div class="matename width40">'
								+matename
								+'</div>'
								+'<div class="amount"><input type="text" id="bommatnum'+materialid+'" placeholder="数量"/><span>'+stockname+'</span></div>'
								+'<div class="price"><input type="text" id="bommatprice'+materialid+'" placeholder="价格" value="'+mateprice+'"/><span> 元</span></div>'
								+'<div class="div4"><input type="button" class="bommatdet" materialid="'+materialid+'" value="移除"/></input></div>'
								+'</div>';
                bombodydiv = bombodydiv + bombodyhead;
                }
            }
            
        }
        $(".bombody").append(bombodydiv);
        $('.bommatdet').on('click',function(){
        	var catid=$(this).attr("materialid");
        	$("#bommat"+catid).remove();
                
         });
     });
     
     //全选 全不选
     $('#all_material').on('click',function(){
    	var aa = document.getElementsByName("ids[]");
        var codep=new Array();
        var bombodydiv = '';
        
        for (var i = 0; i <$(".num").length; i++) {
            
                //alert(aa[i].getAttribute("value"));
              
                var materialid = aa[i].getAttribute("value");
                var matename = aa[i].getAttribute("matename");
                //
                var stockname = aa[i].getAttribute("stockname");
                codep += materialid +','+ matename + ';';
                var bommatif = $('#bommat'+materialid).attr('bommatif');
                if(bommatif == undefined){
                	bommatif = 0;
                    }
                if(bommatif==0){
                var bombodyhead = '<div id="bommat'+materialid+'"  class="bommaterial" bommatif="1" bommatid="'+materialid+'" matename="'+matename+'"  >'
								+'<div class="div1 uhide"><span>'+i+'</span></div>'
								+'<div class="matename width40">'
								+matename
								+'</div>'
								+'<div class="amount"><input type="text" id="bommatnum'+materialid+'" placeholder="数量"/><span>'+stockname+'</span></div>'
								+'<div class="price"><input type="text" id="bommatprice'+materialid+'" placeholder="价格"/><span> 元</span></div>'
								+'<div class="div4"><input type="button" class="bommatdet" materialid="'+materialid+'" value="移除"/></input></div>'
								+'</div>';
                bombodydiv = bombodydiv + bombodyhead;
                }
     

            
        }
         //给input框加上对号
        $(".check").attr("checked",true);
        
        $(".bombody").append(bombodydiv);
        
        //移除
        $('.bommatdet').on('click',function(){
        	var catid=$(this).attr("materialid");
        	$("#bommat"+catid).remove(); 
                //取消input框对号
                $("#check"+catid).attr("checked",false); 
         });
        //alert(codep);
        
        //全不选    
         $('#all_no_material').on('click',function(){ 
                $(".bommaterial").remove();
                //取消input框的对号
                $(".check").attr("checked",false);
         });    
     });
     

    //移除操作
    $('.bommatdet').on('click',function(){
        alert(111);
    	var catid=$(this).attr("materialid");
    	$("#bommat"+catid).remove();
    	alert(catid);
     });
    $('#add_matersubmit').on('click',function(){
    	var matids = '';
    	var matenames = '';
        var mateprice = '';
    	var tasteid = $(".pbomhead").find(".mataction").attr("tasteid");
    	
    	$(".bommaterial").each(function(){
    		var bommatid = $(this).attr("bommatid");
    		var matename = $(this).attr("matename");
    		var bommatnum = $("#bommatnum"+bommatid).val();
                //id="bommatprice'+materialid+'" placeholder="价格" 
                //即bommatprice是获取价格input框中的值
    		var bommatprice = $("#bommatprice"+bommatid).val();
    		if(bommatnum == ''){
    			matenames = matenames + matename +',';
    		}
    		if(bommatprice == ''){
    			mateprice = mateprice + matename +',';
    		}
                
    		matids = matids + bommatid +','+ bommatnum +','+ bommatprice +';';
    		
    		});
		if(matenames!=''){
			alert('下列原料数量未填写，请填写完整后再保存：'+matenames);
		}else{
			if(matids == ''){
				alert("请至少添加一项配方，再保存！");
			}else{
				matids = matids.substr(0,matids.length-1);//除去最后一个“；”
				var url = "<?php echo $this->createUrl('storageOrder/batchsave',array('companyId'=>$this->companyId));?>/matids/"+matids+"/lid/"+prodid;
    			$.ajax({
		                   url:url,
		                   type:'POST',
		                   data:matids,//CF
		                   //async:false,
		                   dataType: "json",
		                   success:function(msg){
		                       var data=msg;
		                       if(data.status){
			                       alert("添加成功!");
			                       $("#close_modal").click();
                                   window.location.reload();
		                       }else{
		                           alert("添加失败");
		                       }
		                   },
		                   error: function(msg){
			                   var data=msg;
		                       alert(data.msg);
		                   }
		             });
				}
			}
               
     });

});       
</script>	
