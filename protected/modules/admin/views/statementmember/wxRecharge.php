<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
  
  <style>
  .modal-dialog {
	    right: auto;
	    left: 50%;
	    width: 700px;
	    padding-top: 30px;
	    padding-bottom: 30px;
    }
    </style> 
<div class="page-content">

    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
    <div class="modal fade" id="portlet-consume" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">无数据</h4>
                    </div>
                    <div class="modal-body">
                           <?php echo "未查询到数据";?>
                    </div>
                    <div class="modal-footer">
                            
                            <button type="button" class="btn default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <!-- BEGIN PAGE HEADER-->
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','会员数据'),'url'=>$this->createUrl('statementmember/list' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','充值记录'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statementmember/list' , array('companyId' => $this->companyId,'type'=>2)))));?>

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
                	<div class="btn-group">
						<input type="text" class="form-control" name="codename" id="cardnumber" placeholder="<?php echo yii::t('app','会员卡号/手机号');?>" value="<?php echo $cardnumber;?>" > 
					</div>
					<div class="btn-group">
						<input type="text" class="form-control" name="codename" id="memdpid" placeholder="<?php echo yii::t('app','会员来源');?>" value="<?php echo $memdpid;?>" > 
					</div>
                    <select id="text" class="btn yellow" >
                        <option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信会员卡');?></option>
                    </select>
                    <div class="btn-group">           
                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                            <input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>" onfocus=this.blur()>  
                            <span class="input-group-addon">~</span>
                            <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>" onfocus=this.blur()>           
                        </div>  
                    </div>					   
                    <div class="btn-group">
                              <button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
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
                                <th><?php echo yii::t('app','会员卡号');?></th>
                                <th><?php echo yii::t('app','姓名|昵称');?></th>
                                <th><?php echo yii::t('app','联系方式');?></th>
                                <th><?php echo yii::t('app','来源');?></th>
                                <th><?php echo yii::t('app','总充值金额');?></th>                                                                
                                <th><?php echo yii::t('app','总返现');?></th>
                                <th><?php echo yii::t('app','总消费')?></th>
                                <?php if(Yii::app()->user->role <5):?>
                                <th><?php echo yii::t('app','余额')?></th>
                                <?php endif;?>
                                <th><?php echo yii::t('app','备注');?></th>
                            </tr>
                        </thead>
                        <tbody>					
                            <?php $a=1;$sumall=0;?>
                                <?php if($models) :?>
                                    <?php foreach ($models as $model):?> 
                                        <tr class="odd gradeX">
                                            <td><?php echo $model['card_id'];?></td>
                                            <td><?php echo $model['user_name']."|".$model['nickname'];?></td>
                                            <td><?php echo $model['mobile_num'];?></td>
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
                                            <td><?php echo $model['recharge_all'];?></td>
                                            <td><?php echo $model['cashback_all'];?></td>
                                            <td><?php echo $model['pay_all']?><a class="btn default btn-xs blue consumelist" title="消费详情" data-id="<?php echo $model['card_id'];?>" dpid="<?php echo $model['dpid'];?>" membername="<?php echo $model['user_name'];?>"  href="javascript:;" style="float: right;"><i class="fa fa-edit"></i></a></td>
                                            <?php if(Yii::app()->user->role):?>
                                            <td><?php echo $this->getMoney($model['card_id']);?></td>
                                            <?php endif;?>
                                            <td><a class="btn default btn-xs blue branduserdetail" title="充值详情" href="javascript:;"
                                                dpid="<?php echo $model['dpid'];?>" membername="<?php echo $model['user_name'];?>" cardlid="<?php echo $model['brand_user_lid'];?>"
                                                cardid="<?php echo $model['card_id'];?>"><i class="fa fa-search"></i>充值详情</a>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>	  
                                <?php endif;?>
                        </tbody>
                    </table>
                <?php endif?>
				
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
			   var cardnumber = $('#cardnumber').val();
			   var memdpid = $('#memdpid').val();
			   location.href="<?php echo $this->createUrl('statementmember/wxRecharge' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/cardnumber/"+cardnumber+"/memdpid/"+memdpid+"/page/"    
			  
	        });
		  });
          var totalurl='';
          var modalconsumetotal;
          $(".consumelist").on("click",function(){
              var cardid = $(this).attr("data-id");
              var dpid = $(this).attr("dpid");
              var name = $(this).attr("membername");
              //layer.msg(cardid+'@'+dpid);
              modalconsumetotal=$('#portlet-consume');
                  totalurl='<?php echo $this->createUrl('statementmember/consumelist',array('companyId'=>$this->companyId));?>/dpid/'+dpid+'/cardid/'+cardid+'/name/'+name;
                  modalconsumetotal.find('.modal-content').load(totalurl
                  ,'', function(){
                    modalconsumetotal.modal();
              });
          });
          $(".branduserdetail").on("click",function(){
              var cardid = $(this).attr("cardid");
              var cardlid = $(this).attr("cardlid");
              var dpid = $(this).attr("dpid");
              var name = $(this).attr("membername");
              //layer.msg(cardid+'@'+dpid);
              modalconsumetotal=$('#portlet-consume');
                  totalurl='<?php echo $this->createUrl('statementmember/rechargelist',array('companyId'=>$this->companyId));?>/dpid/'+dpid+'/cardid/'+cardid+'/cardlid/'+cardlid+'/name/'+name;
                  modalconsumetotal.find('.modal-content').load(totalurl
                  ,'', function(){
                    modalconsumetotal.modal();
              });
          })
                
		 $('#excel').click(function excel(){
			 	var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var text = $('#text').val();
			   var cardnumber = $('#cardnumber').val();
			   var memdpid = $('#memdpid').val();
		       if(confirm('确认导出并且下载Excel文件吗？')){
		    	   location.href="<?php echo $this->createUrl('statementmember/rechargeReportExport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/cardnumber/"+cardnumber+"/memdpid/"+memdpid;
		       }
		      
		   });
                
		
</script> 