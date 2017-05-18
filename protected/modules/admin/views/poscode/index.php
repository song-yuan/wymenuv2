<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
    <div class="modal fade" id="portlet-pad-bind" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','收银机设置'),'url'=>$this->createUrl('company/list' , array('companyId'=>$this->companyId,))),array('word'=>yii::t('app','POS列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('company/list' , array('companyId' => $this->companyId,)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <?php $form=$this->beginWidget('CActiveForm', array(
                            'id' => 'pad-form',
                            'action' => $this->createUrl('poscode/delete' , array('companyId' => $this->companyId)),
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
                        <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','POS列表');?></div>
                        <div class="actions">
                            <!--<a href="#" class="btn green" id="bindPadId"><i class="fa fa-android"></i> <?php echo yii::t('app','绑定设备识别');?></a>-->
                            <a href="<?php echo $this->createUrl('poscode/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>

                            <?php if(Yii::app()->user->role <User::ADMIN):?>
                                <div class="btn-group">
                                    <button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="portlet-body" id="table-manage">
                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <?php if($models):?>
                                <thead>
                                    <tr>
                                        <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                        <th><?php echo yii::t('app','序号');?></th>
                                        <th><?php echo yii::t('app','POS序列号');?></th>
                                        <th><?php echo yii::t('app','是否使用');?></th>
                                        <th><?php echo yii::t('app','模式');?></th>
                                        <th><?php echo yii::t('app','线上支付');?></th>
                                        <th><?php echo yii::t('app','收银机mac地址');?></th>
                                        <?php if(Yii::app()->user->role <=5):?>
                                        <th><?php echo yii::t('app','激活');?></th>
                                        <?php endif;?>
                                        <th><?php echo yii::t('app','操作');?></th>
                                    </tr>
                                </thead>
                                <tbody>						
                                    <?php foreach ($models as $model):?>
                                        <tr class="odd gradeX">
                                            <td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="ids[]" /></td>
                                            <td><?php echo $model['lid'];?></td>
                                            <td><?php echo $model['pad_code'];?></td>
                                            <td><?php if($model->detail) echo '已使用';else echo '未使用';?></td>
                                            <td><?php if($model['pad_sales_type']==0)echo '单屏模式';else echo '双屏模式';?></td>
                                            <td>
                                                <?php switch($model['pay_activate']){
                                                        case 0: echo '未开通线上支付';break;
                                                        case 1: echo '未激活线上支付';break;
                                                        case 2: echo '已激活线上支付';break;
                                                        default: echo '未知状态';break;
                                                }?>
                                            </td>
                                            <td><?php if($model->detail) echo $model->detail[0]->content;?></td>
                                            <td class="center">
                                                <div class="actions">
                                                    <?php if($model['pay_activate']==0):?>
                                                        <button type="button" class="btn payonline" id="stocktaking<?php echo $model->lid;?>" device_id="<?php echo $model->pad_code;?>">未开通</button> 
                                                            <?php if(yii::app()->user->role <=5):?>
                                                                <button type="button" class="btn blue starteonline" id="stocktaking<?php echo $model->lid;?>" device_id="<?php echo $model->pad_code;?>">开通</button>
                                                            <?php endif;?>
                                                    <?php elseif($model['pay_activate']==1):?>
                                                        <button type="button" class="btn green stocktaking" id="stocktaking<?php echo $model->lid;?>" device_id="<?php echo $model->pad_code;?>">激活</button> 
                                                    <?php elseif($model['pay_activate']==2):?>
                                                        <button type="button" class="btn " id="stocktaking<?php echo $model->lid;?>" device_id="<?php echo $model->pad_code;?>">已激活</button> 
                                                    <?php endif;?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($model->detail):?>
                                                <button type="button" class="btn yellow reset" id="reset" value="<?php echo $model['lid'] ;?>">重置</button>  
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            <?php else:?>
                                <tr><td><?php echo yii::t('app','还没有添加PAD');?></td></tr>
                            <?php endif;?>
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
        <?php $this->endWidget(); ?>
    </div>
	<!-- END PAGE CONTENT-->
<script>
    $('#bindPadId').click(function(){
        var companyId=<?php echo $this->companyId;?>;            
        var padInfo=Androidwymenuprinter.getPadInfo();
        alert(padInfo);
        if(padId=="00000000000000000000")
        {
            alert("<?php echo yii::t('app','设备未绑定！');?>");
        }else{
            $('#portlet-pad-bind').find('.modal-content').load('<?php echo $this->createUrl('pad/bind',array('companyId'=>$this->companyId));?>/padId/'+padId);
            $('#portlet-pad-bind').modal();
        }
    });
    $(".stocktaking").on('click',function(){       	
        var device_id = $(this).attr('device_id');
        $.ajax({
                type:'POST',
    			url:"<?php echo $this->createUrl('poscode/sqbactivate',array('companyId'=>$this->companyId,));?>",
    			async: false,
                data: {
                	device_id: device_id,
                },
                cache:false,
                dataType:'json',
                    success:function(msg){
    	            //alert(msg.status);
                        if(msg.status=="success")
                        {            
                                    layer.msg("激活成功！");
                                location.reload();
                        }else{
                                layer.msg("不能重复激活！！！");
                                location.reload();
                        }
                    },
                error:function(){
    				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
    			},
        });            
    });
    $(".starteonline").on('click',function(){       	
        var device_id = $(this).attr('device_id');
        //return false;
        $.ajax({
                type:'POST',
    			url:"<?php echo $this->createUrl('poscode/sqbstartonline',array('companyId'=>$this->companyId,));?>",
    			async: false,
                data: {
                	device_id: device_id,
                },
                cache:false,
                dataType:'json',
                    success:function(msg){
    	            //alert(msg.status);
                        if(msg.status=="success")
                        {            
                                    layer.msg("成功！");
                                location.reload();
                        }else{
                                layer.msg("失败！！！");
                                location.reload();
                        }
                    },
                error:function(){
    				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
    			},
        });           
    });

    $('.reset').on('click', function(){
    	var reset= $(this).val(); 
        if(window.confirm("确认要重置?")){
              
            $.ajax({
                    url:'<?php echo $this->createUrl('Poscode/reset',array('companyId'=>$this->companyId));?>',
                    data:{reset:reset},
                    async: false,
                    success:function(msg){
                            if(msg){
                                layer.msg('重置成功！！！');                           
                                location.reload();
                            }else{
                                layer.msg('重置失败！！！');
                            }
                    },
                    error: function(msg){
                            layer.msg('网络错误！！！');
                    }
            });
        }else{
                        return false;
            }
    });
</script>