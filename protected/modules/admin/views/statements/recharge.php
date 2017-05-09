<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
   
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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','充值记录'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>
                        <?php echo yii::t('app','充值记录报表');?>
                </div>
                <div class="actions">
                    <select id="text" class="btn yellow" >
                        <option value="1" <?php if ($text==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','传统卡');?></option>
                        <option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信会员卡');?></option>
                        <option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','统计');?></option>
                    </select>
                    <div class="btn-group">           
                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                            <input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>" onfocus=this.blur()>  
                            <span class="input-group-addon">~</span>
                            <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>" onfocus=this.blur()>           
                        </div>  
                    </div>					   
                    <div class="btn-group">
                              <button type="submit" id="btn_time_query" class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
                             <!-- <button type="button" style="margin-left: 40px;" class="btn green" id="btn-closeaccount-print" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出excel');?></button> --> 
                             <button type="submit" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>				
                                                                                      <!-- <button type="submit" id="btn_submit" class="btn red" style="margin-left:10px;"><i class="fa fa-pencial"></i><?php echo yii::t('app','日 结');?></button>-->
                    </div>
                </div>
            </div>
            <div class="portlet-body" id="table-manage">
                <?php if($text == 1) :?>
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                              
                                <th width=100px;><?php echo yii::t('app','传统卡号');?></th>						        
                                <th><?php echo yii::t('app','名称');?></th>
                                <th><?php echo yii::t('app','充值金额');?></th>                                                                
                                <th><?php echo yii::t('app','返现');?></th>
                                <th><?php echo yii::t('app','备注');?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <!--foreach-->
                        <?php $a=1;$sumall=0;?>
                            <?php if($models) :?>
                                <?php foreach ($models as $model):?> 
                                    <tr class="odd gradeX">                                                           
                                        <td><?php echo $model['selfcode'];?></td>
                                        <td><?php echo $model['name'];?></td>
                                        <td><?php echo $model['reality_money'];?></td>
                                        <td><?php echo $model['give_money'];?></td>
                                        <td></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>	
                        </tbody>
                    </table>
					
                <?php elseif($text == 2):?>
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                                  <th><?php echo yii::t('app','来源');?></th>
                                <th><?php echo yii::t('app','会员卡号');?></th>
						        
                                <th><?php echo yii::t('app','姓名|昵称');?></th>
                                <th><?php echo yii::t('app','充值金额');?></th>                                                                
                                <th><?php echo yii::t('app','返现');?></th>
                                <th><?php echo yii::t('app','备注');?></th>
                            </tr>
                        </thead>
                        <tbody>					
                            <?php $a=1;$sumall=0;?>
                                <?php if($models) :?>
                                    <?php foreach ($models as $model):?> 
                                        <tr class="odd gradeX">
                                            <td>
                                                <?php
                                                    if($com['type']==0){
                                                         if($model['weixin_group']==0){
                                                            echo $com['company_name'];
                                                         }else{
                                                             foreach ($branch as $key => $val) {
                                                                 if($model['weixin_group']==$val['dpid']){
                                                                     echo $val['company_name']; 
                                                                 }
                                                             }
                                                             
                                                         }
                                                    }else{
                                                        echo $model['company_name'];
                                                        
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo $model['card_id'];?></td>
                                            <td><?php echo $model['user_name']."|".$model['nickname'];?></td>
                                            <td><?php echo $model['recharge_money'];?></td>
                                            <td><?php echo $model['cashback_num'];?></td>
                                            <td></td>
                                        </tr>
                                    <?php endforeach;?>	  
                                <?php endif;?>
                        </tbody>
                    </table>
                <?php elseif ($text == 3) :?>
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
				<th width=100px;><?php echo yii::t('app','充值卡类型');?></th>
                                <th><?php echo yii::t('app','充值金额');?></th>                                                                
                                <th><?php echo yii::t('app','返现');?></th>
				<th><?php echo yii::t('app','备注');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd gradeX">
                                    <td><?php echo yii::t('app','传统卡');?></td>
                                    <td><?php if(!empty($moneys["all_money"])) echo $moneys["all_money"];else echo "0.00";?></td>
                                    <td><?php if(!empty($moneys['all_give'])) echo $moneys['all_give'];else echo "0.00";?></td>
                                    <td></td>
                            </tr>
                            <tr class="odd gradeX">
                                    <td><?php echo yii::t('app','会员卡');?></td>
                                    <td><?php if(!empty($recharge["all_recharge"])) echo $recharge["all_recharge"];else echo "0.00";?></td>
                                    <td><?php if(!empty($recharge["all_cashback"])) echo $recharge["all_cashback"];else echo "0.00";?></td>
                                    <td></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif?>
				
                <?php if($pages->getItemCount()):?>
                <div class="row">
                        <div class="col-md-5 col-sm-12">
                                <div class="dataTables_info">
                                        <?php echo yii::t('app','共 ');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> ,  <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
                                </div>
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
	<!-- END PAGE CONTENT-->

</div>
<script>
		jQuery(document).ready(function(){
		    if (jQuery().datepicker) {
	            $('.date-picker').datepicker({
	            	format: 'yyyy-mm-dd',
	            	language: 'zh-CN',
	                rtl: App.isRTL(),
	                autoclose: true
	            });
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	            
           }
          $('#btn_time_query').click(function() {  
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var text = $('#text').val();
			   location.href="<?php echo $this->createUrl('statements/recharge' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/page/"    
			  
	        });
	         $('#btn_submit').click(function() {
	         	var begin_time = $('#begin_time').val();
			    var end_time = $('#end_time').val();
	         	$.get("<?php echo $this->createUrl('statements/dailyclose',array('companyId'=>$this->companyId ));?>",{begin_time:begin_time,end_time:end_time},function(msg){
	         		if(parseInt(msg)){
	         			alert('日结成功!');
	         			history.go(0);
	         		}else{
	         			alert('日结失败,请重新日结!');
	         		}
	         	});
	         });
		});
                
		 $('#excel').click(function excel(){


	    	   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var text = $('#text').val();
			  
			   //alert(str);
		       if(confirm('确认导出并且下载Excel文件吗？')){
		    	   location.href="<?php echo $this->createUrl('statements/rechargeReportExport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
		       }
		       else{
		    	  // location.href="<?php echo $this->createUrl('statements/export' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
		       }
		      
		   });
                
		
</script> 