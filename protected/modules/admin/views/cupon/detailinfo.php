	<link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>" rel="stylesheet" />
    <link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>" rel="stylesheet" />
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>"></script>
<script type="text/javascript">
	function toVaild(){
	    var role = $('input[name="product_id[]"]:checked').val();
	    if(role==null || role=='0'){
	        layer.msg('请至少选择一项！',{icon: 6});
	        return false;
	         
	    }else{
	     	return true;
	    }
	}
</script>
<style>
	.modal-dialog{
		width: 80%;
		height: 70%;
	}

	.clear{
		clear: both;
	}
	.button-style{
		margin-left: 60%;
	}
	.prod-cupon{
		padding: 4px 5px;
		float: left;
		margin: 2px 5px 5px 5px;
	}
	.prod-span{
		padding: 2px 2px;
		border: 1px solid red;
		border-radius:10px;
	}
	.span-delete-button{margin:0 -10px 0 10px;color: red;font-weight: 600;border-radius: 10px;background-color: #fff;}
</style>
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
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信赠券'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','系统券'),'url'=>$this->createUrl('cupon/index' , array('companyId' => $this->companyId,'type'=>0))),array('word'=>yii::t('app','券限定'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('cupon/index' , array('companyId' => $this->companyId,'type'=>0)))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
		<div class="row">
		<div class="col-md-12">
		<div class="tabbable tabbable-custom">
			
		<div class="tab-content">
			<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','现金券限定');?></div>
				</div>
				<?php if(Yii::app()->user->role<11):?>		
				<div id="printRsultListdetail" style="margin:0;padding:0;width:96%;height:96%;">		                
			         <div class="modal-header">
			         	<h4 class="modal-title">选择能够使用该代金券的店铺</h4>
			         	<span style="color: red;">注意：若不选择店铺，则表示该代金券所有店铺可用。</span>
			         </div>
			         <div class="modal-body">
					         <div class="portlet-body" id="table-manage">  
					         <div style="width: 40%;min-height: 100px;float: left;">
							<?php if($cupondpids):?>
							<?php foreach ($cupondpids as $cupondpid):?>
							<div class="prod-cupon">
								<span class="prod-span"><?php echo $cupondpid['company_name'];?>
								<button type="button" class="span-delete-button delete-dpid" cupon_dpid = '<?php echo $cupondpid['cupon_dpid'];?>'>X</button>
								</span>
							</div>
							<?php endforeach;?>
							<?php endif;?>
							<div class="clear"></div>
							</div> 
					         <div id="reportlistdiv" style="display:inline-block;width:60%;font-size:1.5em;">
						         <ul style="margin:0;padding:0;list-style:none;"><?php $a=1;?>
						         <?php if($dpids):?>
						         <?php foreach($dpids as $dpid):?>
							         <li style="width:50%;float:left;">
								         <div style="width:10%;float:left;"><?php echo $a++;?></div>
								         <div style="width:70%;float:left;"><?php echo $dpid['company_name'];?></div>
								         <div style="width:10%;float:left;">
								         	<input style="height:20px;" type="checkbox" class="checkdpids" value="<?php echo $dpid['dpid'];?>" name="reportlist[]" />
								         </div>
							         </li>
							     <?php endforeach;?>
							     <?php endif;?>
							         <li style="width:100%;">
								         <div style="width:10%;float:left;"></div>
								         <div style="width:60%;float:left;"></div>
								         <div style="width:14%;float:right;">
								         	<input style="height:20px;" type="checkbox" class="group-checkable" data-set="#reportlistdiv .checkdpids" />
								         	全选
								         </div>
							         </li>
						         </ul>
					         </div>
				         </div>
				         <div class="modal-footer">
					         <button id="adddpid" type="button" class="btn blue">确认</button>
				         </div>
					 </div>
							                	
				</div>
				<div style="width: 100%;border-top: 1px dashed silver;"></div>
			<?php endif;?>
				<div id="printRsultListdetail" style="margin:0;padding:0;width:96%;height:96%;">		                
		         <div class="modal-header">
		         	<h4 class="modal-title">选择该现金券对应的单品</h4>
		         	<span style="color: red;">注意：若不选择对应单品，则表示该代金券所有单品可用。</span>
		         </div>
		         <div class="modal-body">
			         <div class="portlet-body" id="table-manage">
						<div style="width: 50%;min-height: 100px;float: left;">
						<?php if($cuponprods):?>
						<?php foreach ($cuponprods as $cuponprod):?>
						<div class="prod-cupon">
							<span class="prod-span"><?php echo $cuponprod['product_name'];?>
							<button type="button" class="span-delete-button delete-prod" prod_code = '<?php echo $cuponprod['prod_code'];?>'>X</button>
							</span>
						</div>
						<?php endforeach;?>
						<?php endif;?>
						<div class="clear"></div>
						
						</div>
				         <div id="reportlistdiv" style="display:inline-block;width:50%;font-size:1.5em;float: left;">
				         <form action="<?php echo $this->createUrl('cupon/addprod',array('companyId'=>$this->companyId));?>/cuid/<?php echo $cuponid;?>/cucode/<?php echo $cuponcode;?>" method = 'post' onsubmit="return toVaild()">
					         <div class="form-group">
					        	<span class="col-md-5 control-label">选择二级分类</span>
								<div class="col-md-4">
									<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control',));?>
		                       	</div>
		                      </div> 
		                      	<div class="form-group">
									<span style="margin-left: 100px;" class="col-md-5 control-label">选择单品</span>
									<div style="display:inline-block;width:80%;">
						         
						         <?php if($products):?>
						         <?php foreach($products as $key=>$product):?>
						         	<ul id="<?php echo $key;?>" style="margin:0;padding:0;list-style:none;">
						         	<?php foreach($product as $key=>$product_name):?>
								         <li style="width:50%;float:left;">
									         <div style="width:85%;float:left;"><?php echo $product_name;?></div>
									         <div style="width:10%;float:left;">
									         	<input style="height:20px;" type="checkbox" class="check" value="<?php echo $key;?>" name="product_id[]"/>
									         </div>
								         </li>
								         <?php endforeach;?>
								         
							        </ul>
							     <?php endforeach;?>
							     <?php endif;?>   
						        <ul style="margin:0;padding:0;list-style:none;">
						        	 <li style="width:100%;">
								         <div style="width:10%;float:left;"></div>
								         <div style="width:60%;float:left;"></div>
								         <div style="width:20%;float:right;">
								         	<input style="height:20px;" type="checkbox" class="group-checkable" />
								         	全选
								         </div>
								         
							         </li>        
						        </ul>
					         </div>
								</div>
								<div class="button-style">
							         <button id="addprod" type="submit" class="btn blue">确认</button>
						         </div>
						         </form>
				         </div>
				         <div class="clear"></div>
			         </div>
				 </div>
				</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		</div>
        </div>
		
		<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'SentwxcardPromotion_promotion_memo',	//Textarea id
								'language'=>'zh_CN',
								// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
								'items' => array(
									'height'=>'200px',
									'width'=>'100%',
									'themeType'=>'simple',
									'resizeType'=>1,
									'allowImageUpload'=>true,
									'allowFileManager'=>true,
								),
							)); ?>
		</div>
		
</div>					<!-- END EXAMPLE TABLE PORTLET-->
				
 <script type="text/javascript">
	$(document).ready(function(){
		$(".group-checkable").click(function () {
            if($(this).attr("checked")) { // 全选 
                $("input[name='product_id[]']").each(function () {
                    $(this).attr("checked", true);
                });
            }else { // 取消全选 
                $("input[name='product_id[]']").each(function () {
                    $(this).attr("checked", false);
                });
            }
        });
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			if(cid!=0){
				<?php foreach ($products as $key => $product):?>
				if(cid=='<?php echo $key;?>'){
					$('#'+cid).show();
					$('#<?php echo $key;?> .check').attr('name','product_id[]');
				}else{
					if(cid!='<?php echo $key;?>'){
						$('#<?php echo $key;?>').hide();
						$('#<?php echo $key;?> .check').attr('name','product');
					}
					
				}
				<?php endforeach;?>
			}else{
				<?php foreach ($products as $key => $product):?>
				$('#<?php echo $key;?>').show();
				$('#<?php echo $key;?> .check').attr('name','product_id[]');
				<?php endforeach;?>
			}
			
			
		});
		$('.delete-prod').on('click',function(){
			
			var prodcode = $(this).attr('prod_code');
			//layer.msg(prodcode);
			//return false;
			if(prodcode == '0' || prodcode == null){
				layer.msg('请选择一项单品！',{icon: 6});
				}
			var cuid = '<?php echo $cuponid;?>';
			var cucode = '<?php echo $cuponcode;?>';
			//return false;
			$.ajax({
				url:'<?php echo $this->createUrl('cupon/delprod',array('companyId'=>$this->companyId,));?>/prodcode/'+prodcode+"/cuid/"+cuid+"/cucode/"+cucode,
				type:'GET',
				dataType:'json',
				success:function(msg){
					if(msg.status){
						 layer.msg('成功！',{icon: 6});
						 location.reload();
					}else{
						 layer.msg('失败！',{icon: 5});
					}
				}
			});
		});

		$("#adddpid").on("click",function(){
            var cuid = '<?php echo $cuponid;?>';
			var cucode = '<?php echo $cuponcode;?>';
            var dpids =new Array();
            var dpids="";
            $('.checkdpids:checked').each(function(){
                dpids += $(this).val()+',';
            });
            if(dpids!=''){
            	dpids = dpids.substr(0,dpids.length-1);//除去最后一个“，”
            	$.ajax({
    				url:'<?php echo $this->createUrl('cupon/adddpid',array('companyId'=>$this->companyId,));?>/dpids/'+dpids+"/cuid/"+cuid+"/cucode/"+cucode,
    				type:'GET',
    				dataType:'json',
    				success:function(msg){
    					if(msg.status){
    						 layer.msg('成功！',{icon: 6});
    						 location.reload();
    					}else{
    						 layer.msg('失败！',{icon: 5});
    					}
    				}
    			});
    	        
	            }else{
					alert("请选择店铺。。。");return;
		            }
		});

		$('.delete-dpid').on('click',function(){
			
			var cudpid = $(this).attr('cupon_dpid');
			//layer.msg(prodcode);
			//return false;
			if(cudpid == '0' || cudpid == null){
				layer.msg('请选择一项！',{icon: 6});
				}
			var cuid = '<?php echo $cuponid;?>';
			var cucode = '<?php echo $cuponcode;?>';
			//return false;
			$.ajax({
				url:'<?php echo $this->createUrl('cupon/deldpid',array('companyId'=>$this->companyId,));?>/cudpid/'+cudpid+"/cuid/"+cuid+"/cucode/"+cucode,
				type:'GET',
				dataType:'json',
				success:function(msg){
					if(msg.status){
						 layer.msg('成功！',{icon: 6});
						 location.reload();
					}else{
						 layer.msg('失败！',{icon: 5});
					}
				}
			});
		});
		
	});

</script>