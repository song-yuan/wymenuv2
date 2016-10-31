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
		width: 20%;
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
		width: 35%;
		height: 100%;
		float: left;
		border-right: 1px solid silver;
	}
	.pbombody .material{
		width: 35%;
		float: left;
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
	.matersubmit button{
		padding: 4px 6px;
	}
	.pbombody .bom{
		width: 45%;
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
		width: 94%;
		height: 70%;
		font-size: 16px;
		margin-left: 3%;
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
		width: 55%;
		height: 30px;
		line-height: 30px;
		float: left;	
		margin-left: 10px;		
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
	.bommaterial .div3{
		width: 25%;
		float: left;
		margin-left: 10px;
	}
	.bommaterial .div4{
		width: 15%;
		float: left;
		margin-left: 10px;
	}
	.bommaterial .div4 input{
		width: 100%;
		float: left;
		margin-left: 10px;
	}
	.bommaterial .div3 input{
		width: 75%;
		float: left;
	}
	.bommaterial .div3 span{
		width: 23%;
		float: left;
		font-size: 18px;
		line-height: 30px;
		text-align: center;		
	}
	.width40{
		width: 40% !important;
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
							<div class="caption"><i class="fa fa-reorder"></i><?php echo $prodname.yii::t('app','配方设置');?></div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="pbom">
								<div class="pbomhead">
									<div class="pbomheadtitle mataction" tasteid="0000000000">基础配方</div>
									<!-- <div class="pbomheadtitle">西米</div>
									<div class="pbomheadtitle">珍珠</div>
									<div class="pbomheadtitle">大</div>
									 -->
									<div class="clear"></div>
								</div>
								<div class="pbombody">
									<div class="matcat">
										<div class="matcathead">原料分类</div>
										<div class="matcatbody">
										 	<div id="mater0" catid="-1" class="pbommaterial mataction "><span>--所有分类--</span></div>
											<?php if($models): ?>
											<?php foreach ($models as $model):?>
											<div id="<?php echo 'mater'.$model->lid;?>" catid="<?php echo $model->lid;?>" class="pbommaterial "><span>
											<?php echo $model->category_name;?>
											</span></div>
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
												<div class="div1"><span><?php echo $a;?></span></div><div class="div2"><input id="check<?php echo $material->lid;?>" type="checkbox" stockname="<?php echo Common::getStockName($material->sales_unit_id);?>" matename="<?php echo $material->material_name;?>" class="checkboxes" value="<?php echo $material->lid;?>"  name="ids[]" /></div>
												<div class="matename ">
												<label for="check<?php echo $material->lid; ?>"><?php echo $material->material_name;?></label>
												</div>
											</div>
											<?php $a++;?>
											<?php endforeach;?>
											<?php endif;?>
										</div>
										<div class="materialend">
											<div class="matersubmit"><button id="add_material">添加>></button></div>
										</div>
									</div>
									<div class="bom"> 
										<div class="bomhead"><?php echo $prodname;?>配方列表</div>
										<div class="bombody"> 
											
										</div>
										<div class="bomend">
											<div class="matersubmit"><button id="add_matersubmit">确认保存配方</button></div>
										</div>
									</div>
									<div class="clear"></div>
									
								</div>
							</div>
							
						</div>
						</div><button id="close_modal" type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
				
					</div>
							
			</div>
		
			<!-- END PAGE CONTENT-->
<script type="text/javascript">
$(document).ready(function(){
	var prodid = '<?php echo $pid;?>';
	var prodcode = '<?php echo $phscode;?>';
    $('.pbomheadtitle').on('click',function(){
        $('.pbomheadtitle').removeClass('mataction');
        $(this).addClass('mataction');
     });
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
        //var settype = $(this).attr("settype");
        //alert(catid);
       // product_cate_select(catid,settype); 
              
    });
    $('#add_material').on('click',function(){
    	var aa = document.getElementsByName("ids[]");
        var codep=new Array();
        var bombodydiv = '';
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                //alert(aa[i].getAttribute("value"));
                var materialid = aa[i].getAttribute("value");
                var matename = aa[i].getAttribute("matename");
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
								+'<div class="div3"><input type="text" id="bommatnum'+materialid+'" placeholder="消耗数量"/><span>'+stockname+'</span></div>'
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
        //alert(codep);
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
    	var tasteid = $(".pbomhead").find(".mataction").attr("tasteid");
    	
    	$(".bommaterial").each(function(){
    		var bommatid = $(this).attr("bommatid");
    		var matename = $(this).attr("matename");
    		var bommatnum = $("#bommatnum"+bommatid).val();
    		if(bommatnum == ''){
    			matenames = matenames + matename +',';
    		}
    		matids = matids + bommatid +','+ bommatnum +';';
    		
    		});
		
		if(matenames){
			alert('下列原料消耗数量未填写，请填写完整后再保存：'+matenames);
			}else{
				if(matids == ''){
					alert("请至少添加一项配方，再保存！");
					//return false;
				}else{
					matids = matids.substr(0,matids.length-1);//除去最后一个“；”
					//alert(matids);alert(prodid);alert(prodcode);alert(tasteid);
					var url = "<?php echo $this->createUrl('productBom/storProductBom',array('companyId'=>$this->companyId));?>/matids/"+matids+"/prodid/"+prodid+"/prodcode/"+prodcode+"/tasteid/"+tasteid;
		               $.ajax({
		                   url:url,
		                   type:'POST',
		                   data:matids,//CF
		                   //async:false,
		                   dataType: "json",
		                   success:function(msg){
		                       var data=msg;
		                       if(data.status){
			                       alert("保存成功");
			                       //$("#close_modal").trigger(click);
			                       document.getElementById("close_modal").click(); 
								//alert(data.matids);
								//alert(data.prodid);  
		                       }else{
		                           alert("保存失败");
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