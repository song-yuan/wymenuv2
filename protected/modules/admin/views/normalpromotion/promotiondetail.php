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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营销活动'),'url'=>$this->createUrl('entityMarket/list' , array('companyId' => $this->companyId,'type'=>0,))),array('word'=>yii::t('app','普通优惠'),'url'=>$this->createUrl('normalpromotion/index' , array('companyId' => $this->companyId,))),array('word'=>yii::t('app','查看已添加菜品'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('normalpromotion/index' , array('companyId' => $this->companyId,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'normalpromotiondetail-form',
				'action' => $this->createUrl('normalpromotion/promotiondetail', array('companyId' => $this->companyId,'typeId'=>"product",'promotionID'=>$promotionID,'source'=>$source)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">

        <div class="tabbable tabbable-custom">
            <ul class="nav nav-tabs">
               <li class="<?php if($typeId=='product') echo 'active';?>"><a href="#tab_1" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('normalpromotion/promotiondetail' , array( 'companyId'=>$this->companyId,'promotionID'=>$promotionID,'typeId'=>'product','code'=>$code, 'source'=>$source));?>'"><?php echo yii::t('app','单品');?></a></li>

               <li class="<?php if($typeId=='set') echo 'active';?>"><a href="#tab_1_" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('normalpromotion/promotiondetail' , array( 'companyId'=>$this->companyId,'promotionID'=>$promotionID,'typeId'=>'set','code'=>$code, 'source'=>$source));?>'"><?php echo yii::t('app','套餐');?></a></li>


        </ul>
<div class="tab-content">
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<div class="portlet box purple">
	<div class="portlet-title">

		<div class="caption"><i class="fa fa-globe"></i><?php if($typeId=='product') echo yii::t('app','查看已添加菜品并设置');else echo yii::t('app','查看已添加套餐并设置');?></div>
		<div class="actions">
			<?php if($typeId=='product'):?>
            <div style="margin-top:-5px !important;" class="btn-group">
				<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
			</div>
			<?php endif;?>
			<div class="btn-group">
				<button type="button" id="yichu"  class="btn red" style="padding:6px 10px;margin-top:2px;" ><i class="fa fa-ban"></i> <?php echo yii::t('app','勾选批量移除');?></button>
			</div>

		</div>
		<?php if($typeId == 'product'):?>
        <div class="col-md-3 pull-right">
			<div class="input-group">
                <input type="text" name="csinquery" class="form-control" placeholder="<?php echo yii::t('app','输入助记符查询');?>">
                <span class="input-group-btn">
                	<button class="btn blue" type="submit"><?php echo yii::t('app','查询!');?></button>

                </span>
            </div>
        </div>
        <?php endif;?>
	</div>
	<div class="portlet-body" id="table-manage">
		<table class="table table-striped table-bordered table-hover" id="sample_1">
			<thead>
				<tr>
					<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
					<th style="width:10%"><?php echo yii::t('app','名称');?></th>
					<th ><?php echo yii::t('app','图片');?></th>
					<th><?php if($typeId=='product') echo yii::t('app','原价');else echo yii::t('app','套餐默认价格');?></th>
					<th><?php echo yii::t('app','状态');?></th>
				</tr>
			</thead>
			<tbody>
			<?php if($models) :?>
			<?php foreach ($models as $model):?>
				<tr class="odd gradeX">
					<td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="idchk" /></td>
					<td style="width:10%"><?php if($typeId=='product') echo $model['product_name'];else echo $model['set_name'];?></td>

					<td ><img width="100" src="<?php echo $model['main_picture'];?>" /></td>

					<?php if($typeId=='product') :?>
					<td ><?php echo $model['original_price'];?></td>
					<?php elseif($typeId=="set") :?>
					<td style="width:7%;"><?php echo sprintf("%.2f",$this->getProductSetPrice($model['lid'],$model['dpid']));?></td>
					<?php endif;?>


                    <td>
						<div class="form-group">
							<div class="col-md-12">
								<div class="radio-list">
									<label class="radio-inline">
									<input type="radio" name="optionsRadios<?php echo $model['lid'];?>" id="optionsRadios<?php echo $model['lid'];?>1" value="0" checked="checked" <?php if($model['promotion_money']>'0.00') echo "checked";?> > <?php echo yii::t('app','优惠：');?>
									<input type="text" style="width:60px;" name="leftnum<?php echo $model['lid'];?>" id="idleftnum0<?php echo $model['lid'];?>" value="<?php if(!empty($model['promotion_money'])) echo $model['promotion_money']; else echo '0.00'; ?>" onfocus=" if (value =='0.00'){value = ''}" onblur="if (value ==''){value='0.00'}" >
									</label>
									<label class="radio-inline">
									<input type="radio" name="optionsRadios<?php echo $model['lid'];?>" id="optionsRadios<?php echo $model['lid'];?>2" value="1" <?php if($model['promotion_discount']>'0.00'&& $model['promotion_discount']<'1.00') echo "checked";?>> <?php echo yii::t('app','折扣');?>
									<input type="text" style="width:60px;" name="leftnum<?php echo $model['lid'];?>" id="idleftnum1<?php echo $model['lid'];?>" value="<?php if(!empty($model['promotion_discount'])) echo $model['promotion_discount']; else echo '1.00'; ?>" onfocus=" if (value =='1.00'){value = ''}" onblur="if (value ==''){value='1.00'}" >
									<a style="color: red;"><?php echo yii::t('app','例：88折填写为0.88');?></a>
									</label>
                                    <label class="radio-inline">
                                    <!--  <input type="checkbox" name="optionsCheck<?php echo $model['lid'];?>" id="optionsCheck<?php echo $model['lid'];?>" value="0" <?php if(!empty($model['order_num'])) echo "checked";?>> <?php echo yii::t('app','数量限制');?>
                                    <input type="text" style="width:60px;" name="leftnum<?php echo $model['lid'];?>" id="checknum<?php echo $model['lid'];?>" value="<?php if(!empty($model['order_num'])) echo $model['order_num']; else echo yii::t('app','无限制'); ?>" onfocus=" if (value =='无限制'){value = ''}" onblur="if (value ==''){value='无限制'}" >
                                    -->
                                    <input type="button" <?php if($source)echo 'disabled';?> name="leftbutton<?php echo $model['lid'];?>" id="idleftbutton<?php echo $model['lid'];?>" code="<?php if($typeId=='product') echo $model['phs_code'];elseif($typeId=='set') echo $model['pshs_code'];?>" class="btn green clear_btn" value=<?php echo yii::t('app','保存');?> >
                                    <input type="button" <?php if($source)echo 'disabled';?> name="delete<?php echo $model['lid'];?>" id="delete<?php echo $model['lid'];?>" class="btn red clear_red" value=<?php echo yii::t('app','移除');?> >
                                    </label>
								</div>
							</div>
						</div>
					</td>

				</tr>
			<?php endforeach;?>
			<?php endif;?>
			</tbody>
		</table>
			<?php if($pages->getItemCount()):?>
			<div class="row">
				<div class="col-md-5 col-sm-12">
					<div class="dataTables_info">
						<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
					</div>
				</div>
				<div class="col-md-7 col-sm-12">
					<div class="dataTables_paginate paging_bootstrap">
					<?php $this->widget('CLinkPager', array(
						'pages' => $pages,
						'header'=>'',
						'firstPageLabel' => '<<',
						'lastPageLabel' => '>>',
						'firstPageCssClass' => '',
						'lastPageCssClass' => '',
						'maxButtonCount' => 8,
						'nextPageCssClass' => '',
						'previousPageCssClass' => '',
						'prevPageLabel' => '<',
						'nextPageLabel' => '>',
						'selectedPageCssClass' => 'active',
						'internalPageCssClass' => '',
						'hiddenPageCssClass' => 'disabled',
						'htmlOptions'=>array('class'=>'pagination pull-right')
					));
					?>
					</div>
				</div>
			</div>
			<?php endif;?>

	</div>
</div>
			<!-- END EXAMPLE TABLE PORTLET-->
</div>
</div>

		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){

		$('.s-btn').on('switch-change', function () {
                    var inp = $(this).find('input');
                        var id=inp.attr('pid');
                        var source = '<?php echo $source;?>';
                        //var typeid=inp.attr('typeid');
                        var url='<?php echo $this->createUrl('normalpromotion/status',array('companyId'=>$this->companyId));?>/id/'+id+'/source/'+source;
                        //alert(url);
                        $.get(url);
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			var promotionID='<?php echo $promotionID;?>';
			var source = '<?php echo $source;?>';
			location.href="<?php echo $this->createUrl('normalpromotion/promotiondetail' , array('companyId'=>$this->companyId));?>/cid/"+cid+"/promotionID/"+promotionID+"/typeId/product"+"/source/"+source;
		});
	});

    $(".clear_btn").on("click",function(){
        <?php if(Yii::app()->user->role > User::SHOPKEEPER):?>
        alert("您没有权限！");
        return false;
        <?php endif;?>
        var fa_code = '<?php echo $code;?>';
        var prod_code = $(this).attr('code');
        var vid=$(this).attr("id").substr(12,10);
        var arr=document.getElementsByName("optionsRadios"+vid);
       // var chx=document.getElementById("optionsCheck"+vid);
        var optid;
        var optvalue;
       // var checkvalue = '0';
        var cid = $(this).val();
        // alert(fa_code);
        //return false;
		var promotionID='<?php echo $promotionID;?>';
        for(var i=0;i<arr.length;i++)
        {
            if(arr[i].checked)
            {
               optid=arr[i].value;
            }
        }
        if(optid=="0")
        	{
            optvalue= $("#idleftnum0"+vid).val();
            if(optvalue<'0'){
            	alert("<?php echo yii::t('app','优惠数值应大于0！！！'); ?>")
            	return false;
                }
        }else if(optid=="1")
            {
        	optvalue= $("#idleftnum1"+vid).val();

        	if(optvalue>'1'||optvalue<'0'){
              	alert("<?php echo yii::t('app','折扣数值应小于1大于0！！！'); ?>")
              	return false;
                  }
            }
        //var url="<?php echo $this->createUrl('normalpromotion/store',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>/id/"+vid+"/promotionID/"+promotionID+"/proNum/"+optvalue+"/proID/"+optid+"/cid/"+cid+"/page/fa_code/"+fa_code+"/prod_code/"+prod_code;
		//alert(url);
			//return false;
        $.ajax({
        type:'GET',
			url:"<?php echo $this->createUrl('normalpromotion/store',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>/id/"+vid+"/promotionID/"+promotionID+"/proNum/"+optvalue+"/proID/"+optid+"/cid/"+cid+"/fa_code/"+fa_code+"/prod_code/"+prod_code,
			async: false,
			//data:"companyId="+company_id+'&padId='+pad_id,
        cache:false,
        dataType:'json',
			success:function(msg){
                        //alert(msg.status);
                        if(msg.status=="success")
                        {
                            //alert("<?php echo $promotionID;?>")
                            alert("<?php echo yii::t('app','成功'); ?>");

                            location.reload();
                        }else{
                            alert("<?php echo yii::t('app','失败'); ?>"+"1")
                            location.reload();
                        }
			},
                    error:function(){
				alert("<?php echo yii::t('app','失败'); ?>"+"2");
			},
		});
    });


        $(".clear_red").on("click",function(){
        	<?php if(Yii::app()->user->role > User::SHOPKEEPER):?>
            alert("您没有权限！");
            return false;
            <?php endif;?>
            var vid=$(this).attr("id").substr(12,10);

            $.ajax({
            type:'GET',
 			url:"<?php echo $this->createUrl('normalpromotion/detaildelete',array('companyId'=>$this->companyId,'pid'=>$promotionID));?>/id/"+vid+"/page/",
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
                                alert("<?php echo yii::t('app','失败'); ?>"+"1")
                                location.reload();
                            }
 			},
                        error:function(){
 				alert("<?php echo yii::t('app','失败'); ?>"+"2");
 			},
 		});
        });

        $("#yichu").on("click",function(){
        	<?php if(Yii::app()->user->role > User::SHOPKEEPER):?>
            alert("您没有权限！");return false;
            <?php endif;?>
            <?php if($source):?>
            layer.msg('该活动来自总部，无法修改！！！',{icon: 5});return false;
            <?php endif;?>
            //alert(111);
            var aa = document.getElementsByName("idchk");
            var str=new Array();
            for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked){
                str += aa[i].value +',';
                //alert(str);
            }
            }
            if(str!=''){
                str = str.substr(0,str.length-1);//除去最后一个“，”
                }else{
               	 alert("<?php echo yii::t('app','请勾选相应的菜品再进行一键移除！！！');?>");
               	 return false;
               	 }

            $.ajax({
            type:'GET',
 			url:"<?php echo $this->createUrl('normalpromotion/detaildelete',array('companyId'=>$this->companyId,'pid'=>$promotionID));?>/id/"+str+"/page/",
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
                                alert("<?php echo yii::t('app','失败'); ?>"+"1")
                                location.reload();
                            }
 			},
                        error:function(){
 				alert("<?php echo yii::t('app','失败'); ?>"+"2");
 			},
 		});
        });
	</script>