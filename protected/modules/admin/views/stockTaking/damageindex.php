<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/keyboard1.js');?>

<!--<style>
    .keyboard{
       width:485px; 
       padding-bottom: 15px;
       position: fixed;
       top:200px;
       left:450px;
       display:none;
      background-color:#FFFFFF;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
      border-radius: 6px; 
      -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
       -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
       box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2); 
      
    }
    .keyboard-active{
        display:block;
    }
    button{
        width:100px;
        height:60px;
        margin:15px 0 0px 15px;
        background-color: #AAAAAA;
        border:0px;
        font-size:25px;
        font-weight:bold;
        -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
      border-radius: 6px; 
    }
    
</style>-->

<style>
  
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
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','盘损'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('bom/bom' , array('companyId' => $this->companyId,'type' =>'2',)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('productMaterial/delete' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','盘损');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','品项编号');?></th>
								<th ><?php echo yii::t('app','品项名称');?></th>
								<th ><?php echo yii::t('app','类型');?></th>
								<th><?php echo yii::t('app','库存单位');?></th>
								<!-- <th><?php echo yii::t('app','实时库存');?></th> -->
								<th><?php echo yii::t('app','盘损库存');?></th>
								<!--<th><php echo yii::t('app','库存成本');?></th>-->
								<th><?php echo yii::t('app','选择盘损原因');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
                                                 
						<?php if($models) :?>
                                                 
						<?php foreach ($models as $model):?>

							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td><?php echo $model->material_identifier;?></td>
								<td ><?php echo $model->material_name;?></td>
								<td><?php if(!empty($model->category->category_name)) echo $model->category->category_name;?></td>
								<td ><?php echo Common::getStockName($model->stock_unit_id);?></td>
								<!-- <td ><php echo isset($model->material_stock)?$model->material_stock->stock:0;?></td>  -->
								<!-- <td ><?php echo ProductMaterial::getJitStock($model->lid,$model->dpid);?></td>  -->
								                               
                                <td><input style="display: none;" type="text" class="checkboxes" id="originalnum<?php echo $model->lid;?>" value="<?php  echo ProductMaterial::getJitStock($model->lid,$model->dpid);?>" name="idss[]" />
								<input class="kucundiv form-control" type="text"   style="width:100px;" name="leftnum<?php echo $model->lid;?>" id="idleftnum0<?php echo $model->lid;?>" value="" onfocus=" if (value =='0.00'){value = '0.00'}" onblur="if (value ==''){value=''}"  onkeyup="if(isNaN(value))execCommand('undo')" onafterpaste="if(isNaN(value))execCommand('undo')" >
								<!-- <input type="button"   onclick ="demo(this)" name="leftbutton<?php echo $model->lid;?>" id="idleftbutton<?php echo $model->lid;?>" class="clear_btn" value="<?php echo yii::t('app','保存');?>">
								 --></td>
								<!--<td ><php echo $model->stock_cost;?></td>-->
								<td class="center">
									<?php if($reasons):?>
									<select class="form-control" id="selreadam<?php echo $model->lid;?>">
										<option value="0" tip="<?php echo '未填写原因';?>"><?php echo '--请选择--';?></option>
									<?php foreach ($reasons as $reason):?>
										<option value="<?php echo $reason->name;?>" tip="<?php echo $reason->tip;?>"><?php echo $reason->name;?></option>
									<?php endforeach;?>
									</select>
									<?php else:?>
									<a style="color: grey;" href="<?php echo $this->createUrl('stockTaking/damagereason',array('id' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','请前去添加盘损原因');?></a>
									<?php endif;?>
								<!-- <a href="<?php echo $this->createUrl('productMaterial/update',array('id' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								 --></td>
								<td class="center">
								<!-- <a href="<?php echo $this->createUrl('productMaterial/detailindex',array('id' => $model->lid , 'companyId' => $model->dpid,));?>"><?php echo yii::t('app','查看库存详情');?></a>
								 --></td>
							</tr>
                                             <?php   ;?>
                                                      
                                                   
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
                       
					<div class="form-actions fluid">
						<div class="col-md-offset-9 col-md-3">
				<!--        <button type="submit" class="btn blue">确定</button>     -->   
							<button type="button" class="btn yellow" id="stocktaking">一键盘损</button>                             
						</div>
					</div>			
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
         
	$(document).ready(function(){
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('stockTaking/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
          
                    
	$("#stocktaking").on("click",function(){
		//alert("123");
		//var vid=$(this).attr("id").substr(12,10);
        var arr=document.getElementsByName("idss[]");
        //var chx=document.getElementById("optionsCheck"+vid);
        var optid;
        var optval = '';
        //var checkvalue = '0';
        //var cid = $(this).val();
        //alert(cid);
        for(var i=0;i<arr.length;i++)
        {
            var vid = $(arr[i]).attr("id").substr(11,10);  
            var nownum = $("#idleftnum0"+vid).val(); 
            //alert(nownum);return false;
            var originalnum = $("#originalnum"+vid).val();
            var difference = parseFloat(nownum) - parseFloat(originalnum);
				difference = difference.toFixed(2);
            if(nownum != ''){
            	var reaname = $("#selreadam"+vid).find("option:selected").text();
                var reatip = $("#selreadam"+vid).find("option:selected").attr('tip');
                if(reaname&&reatip){
                	var reason = reaname +'['+ reatip +']';
                }else{
                	var reason = '盘损原因 '+'[尚未填写原因]';
                	}
                optval = vid +','+ difference +','+ nownum +','+ originalnum +','+ reason +';'+ optval;
                //layer.msg(reatip);
                } 
        }
        if(optval.length >0){
        	optval = optval.substr(0,optval.length-1);//除去最后一个“，”
        	//alert(optval);
        }else{
            alert('请至少盘损一项');
            return false;
            }
        //return false;
		var categoryId = '<?php echo $categoryId;?>';
        $.ajax({
            type:'GET',
			url:"<?php echo $this->createUrl('stockTaking/damageStore',array('companyId'=>$this->companyId,));?>/optval/"+optval+"/cid/"+categoryId,
			async: false,
			//data:"companyId="+company_id+'&padId='+pad_id,
            cache:false,
            dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg.status=="success")
	            {            
		            if(msg.msg !=''){
		            	alert("下列产品尚未入库，无非进行盘损【"+msg.msg+"】；其他产品盘损正常。点击确认跳转至盘损日志查看");
		            }else{
			            layer.msg("盘损成功！");
			        }  
		            location.href="<?php echo $this->createUrl('stocktakinglog/detailindex' , array('companyId'=>$this->companyId,'status'=>1));?>/id/"+msg.logid
		            //location.reload();
	            }else{
		            alert("<?php echo yii::t('app','失败'); ?>"+"1");
		            location.reload();
	            }
			},
            error:function(){
				alert("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	});
    
    $(".clear_btn").on("click",function(){
        var vid = $(this).attr("id").substr(12,10);
        var nownum = $("#idleftnum0"+vid).val();
        var originalnum = $("#originalnum"+vid).val();
       // var chx=document.getElementById("optionsCheck"+vid);
        var optid;
        var optvalue;
        //alert(nownum);alert(originalnum);
		var difference = parseFloat(nownum) - parseFloat(originalnum);
			difference = difference.toFixed(2);
		var categoryId = '<?php echo $categoryId;?>';
        $.ajax({
            type:'GET',
			url:"<?php echo $this->createUrl('stockTaking/store',array('companyId'=>$this->companyId,));?>/id/"+vid+"/cid/"+categoryId+"/nowNum/"+nownum+"/originalNum/"+originalnum+"/difference/"+difference,
			async: false,
			//data:"companyId="+company_id+'&padId='+pad_id,
            cache:false,
            dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg.status=="success")
	            {            
		            alert("<?php echo yii::t('app','成功'); ?>");               
		            location.reload();
	            }else{
		            alert("<?php echo yii::t('app','失败'); ?>"+"1");
		            location.reload();
	            }
			},
            error:function(){
				alert("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
    });
</script>	