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
		height: 370px;
		overflow-y: auto;
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
		font-size: 0.8em;
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

/*买*/
	.bommaterial .div2{
		width:100%;
		float: left;
		margin-left: 10px;
	}
	.bommaterial .div2 input{
		width: 10%;
		border-radius:3px;
		text-align: center;
	}
	.bommaterial .div2 .pdname{
		display:inline-block;
		width: 100px !important;
		height: 18px;
		padding-top:2px;
		overflow: hidden;
		border-radius:3px;
	}
/*	.width40{
		width:8em;
		height: 30px;
		line-height: 30px;
		float:left;
		display: inline-block;
		overflow: hidden;
	}*/
/*送*/
	.bommaterial .div3{
		width: 45%;
		float: left;
		margin-left: 10px;
	}
	.bommaterial .div3 input{
		width: 10%;
		border-radius:3px;
	}
	.bommaterial .div3 .red{
		width: 30%;
		border-radius:3px;
	}
	.bommaterial .div3 span{
		line-height: 30px;
		text-align: center;
	}


/*移除*/
	.bommaterial .div4{
		width: 15%;
		float: left;
		margin-left: 10px;
	}
	.bommaterial .div4 input{
		width: 100%;
		margin-left: 10px;
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
	.bommatdet{
		padding: 2px 4px !important;
	}
	#add_material{
		margin-right:10px;
	}
	#add_matersubmit{
		margin-right:10px;
	}
	.selectproduct{
		width: 150px;
		border:1px solid silver;
		padding:0;
		margin:0 3px;
		border-radius: 3px;
	}
	.portlet.box > .portlet-body {
		min-height: 450px;
	}

</style>


<div class="row">
	<div class="col-md-12">
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-reorder"></i><?php echo $prodname.yii::t('app','详情添加');?></div>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="pbom">
					<div class="pbomhead">
						<div class="pbomheadtitle mataction" tasteid="0000000000">单品</div>
						<?php if($prodTastes):?>
						<div id="prodtaste" value="1" class="uhide"></div>
						<?php foreach ($prodTastes as $prodTaste):?>
							<div class="pbomheadtitle " tasteid="<?php echo $prodTaste['lid'];?>"><?php echo $prodTaste['name'];?></div>
						<?php endforeach;?>
						<?php else:?>
						<div id="prodtaste" value="0" class="uhide"></div>
						<?php endif;?>
						<!-- <div class="pbomheadtitle">西米</div>
						<div class="pbomheadtitle">珍珠</div>
						<div class="pbomheadtitle">大</div>
						 -->
						<div class="clear"></div>
					</div>
					<div class="pbombody">
						<div class="matcat">
							<div class="matcathead">产品分类</div>
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
							<div class="materialhead">产品列表</div>
							<div class="materialbody">
								<?php if($products): ?>
								<?php $a = 1;?>
								<?php foreach ($products as $product):?>
								<div id="<?php echo $product->lid;?>" catid="<?php echo $product->category_id;?>" class="bodymaterial">
									<div class="div1"><span><?php echo $a;?></span></div><div class="div2"><input id="check<?php echo $product->lid;?>" type="checkbox" stockname="" matecode="<?php echo $product->phs_code;?>" matename="<?php echo $product->product_name;?>" class="checkboxes" value="<?php echo $product->lid;?>"  name="ids[]" /></div>
									<div class="matename ">
									<label for="check<?php echo $product->lid; ?>"><?php echo $product->product_name;?></label>
									</div>
								</div>
								<?php $a++;?>
								<?php endforeach;?>
								<?php endif;?>
							</div>
							<div class="materialend">
								<div class="matersubmit"><button id="add_material" class="btn green">添加 >></button></div>
							</div>
						</div>
						<div class="bom">
							<div class="bomhead"><?php echo $prodname;?>参与产品列表</div>
							<div class="bombody">

							</div>
							<div class="bomend">
								<div class="matersubmit"><button id="add_matersubmit" class="btn blue">确认保存</button></div>
							</div>
						</div>
						<div class="clear"></div>

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
	var prodcode = '<?php echo $phscode;?>';
	var prodtaste = $('#prodtaste').attr('value');
	//alert(prodtaste);
    $('.pbomheadtitle').on('click',function(){
        $('.pbomheadtitle').removeClass('mataction');
        $(this).addClass('mataction');
        $('.checkboxes').removeAttr('checked');
        $('.bombody > div').remove();
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
                var matecode = aa[i].getAttribute("matecode");
                var stockname = aa[i].getAttribute("stockname");
                codep += materialid +','+ matename + ';';
                var bommatif = $('#bommat'+materialid).attr('bommatif');
                if(bommatif == undefined){
                	bommatif = 0;
                    }
                if(bommatif==0){
                var bombodyhead = '<div id="bommat'+materialid+'" class="bommaterial" bommatif="1" bommatid="'+materialid+'" matename="'+matename+'" matecode="'+matecode+'">'
								+'<div class="div1 uhide"><span>'+i+'</span></div>'
								
								+'<div class="div2"><span><b >买 </b></span><input type="text" onkeypress="return event.keyCode>=48&&event.keyCode<=57" id="buynum'+materialid+'" placeholder="多少" value="1"/>'
								+'<span class="pdname"> '
								+matename
								+' </span><span>'+stockname+'</span>'
								+'<span><b>送 </b></span><input type="text" onkeypress="return event.keyCode>=48&&event.keyCode<=57" id="sentnum'+materialid+'" placeholder="多少" value="1"/><span>'+stockname+'</span>'
								+'<select name="selectproduct" id="selectproduct'+materialid+'" class="btn gray selectproduct">'
								+' <option value="">-请选择-</option>'
								<?php foreach ($products as $product):?>
								+' <option value="<?php echo $product->lid;?>" smatecode="<?php echo $product->phs_code;?>"><?php echo $product->product_name;?></option>'
								<?php endforeach; ?>
								+'</select>'
								+'<button  class="bommatdet btn red" materialid="'+materialid+'" >移除</button></div>'
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
        <?php if(Yii::app()->user->role > User::SHOPKEEPER):?>
        alert("您没有权限！");
        return false;
        <?php endif;?>
    	var matids = '';
    	var matenames = '';
    	var tasteid = $(".pbomhead").find(".mataction").attr("tasteid");

    	$(".bommaterial").each(function(){
    		var bommatid = $(this).attr("bommatid");
    		var matename = $(this).attr("matename");
    		var matecode = $(this).attr("matecode");
    		var buynum = $("#buynum"+bommatid).val();
    		var sentnum = $("#sentnum"+bommatid).val();

    		// var sentmatecode = $("#selectproduct"+bommatid + " option:selected").attr("smatecode");
    		// var sentmatid = $("#selectproduct"+bommatid + " option:selected").val();

    		var sentmatecode = $("#selectproduct"+bommatid ).find('option:selected').attr("smatecode");
    		// alert(sentmatecode);
    		var sentmatid = $("#selectproduct"+bommatid ).find('option:selected').val();
    		var sentname = $("#selectproduct"+bommatid ).find('option:selected').text();
    		//alert(sentmatecode);
    		// console.log(sentmatecode);
    		// if(sentmatecode == undefined){
    		// 	alert(111);
    		// };
    		// alert(22);
    		if(buynum == '' || sentnum == '' || sentmatecode == undefined){
    			matenames = matenames + matename+',';
    		}
    		matids = matids + bommatid +','+ matecode +','+ buynum +','+ sentmatid +','+ sentmatecode +','+ sentnum +';';
    		});

		if(matenames){
			alert('下列产品数量规则填写不全，请填写完整后再保存：'+matenames);
		}else{
			if(matids == ''){
				alert("请至少添加一项活动产品，再保存！");
				//return false;
			}else{
				matids = matids.substr(0,matids.length-1);//除去最后一个“；”
				//alert(matids);alert(prodid);alert(prodcode);alert(tasteid);
				var url = "<?php echo $this->createUrl('buysentpromotion/storbuysent',array('companyId'=>$this->companyId));?>/matids/"+matids+"/prodid/"+prodid+"/prodcode/"+prodcode+"/tasteid/"+tasteid;
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
		                       if(prodtaste == 0){
			                       //alert(prodtaste);
		                       		document.getElementById("close_modal").click();
		                       }else{
			                       layer.msg("请添加口味配方；或者点击右下角关闭页面！");
			                       }
							//alert(data.matids);
							//alert(data.prodid);
	                       }else{
	                           alert("保存失败");
	                       }
	                   },
	                   error: function(msg){
		                   var data=msg;
	                       // alert(1111);
	                       alert(data.msg);
	                   }
	               });
				}
			}
     });

});
</script>