<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
<style>
    form{
        margin-bottom: 20px;
    }
    .btn-group{
        margin-right: 10px;
    }
</style>
<div class="page-content">
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','实体卡'),'url'=>$this->createUrl('entityCard/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','活跃会员'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityCard/list' , array('companyId' => $this->companyId)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <form action="" method="post">  
                <div class="btn-group">
                    <div class="input-group input-large " >                   
                        <span  id ='more_less' class="input-group-addon">消费次数大于</span>
                        <input type="text" class="form-control" name='number' id="number"  value="<?php echo $number;?>" >
                        <span class="input-group-addon">次</span>
                    </div>
                </div>   
                <div class="btn-group">
                    <div class="input-group input-large date-picker input-daterange">
                        <span class="input-group-addon">时间</span>
                        <input type="text" class="form-control" name="begin_time" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">
                        <span class="input-group-addon">至</span>
                        <input type="text" class="form-control" name="end_time" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">           
                    </div>  
                </div>                    
                <div class="btn-group">
                    <button type="submit" id="query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
                </div>
            </form>
            <div class="portlet-body" id="table-manage">
                <table class="table table-striped table-bordered table-hover" id="sample_1">
                    <thead>
                        <tr> 
                            <th><?php echo yii::t('app','卡号');?></th>
                            <th><?php echo yii::t('app','姓名');?></th>
                            <th><?php echo yii::t('app','性别');?></th>
                            <th><?php echo yii::t('app','生日');?></th>
                            <th><?php echo yii::t('app','消费次数');?></th>                      
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($models):?>
                        <?php foreach ($models as $model):?>
                        <tr>
                            <td><?php echo $model['selfcode'];?></td>
                            <td><?php echo $model['name'];?></td> 
                            <td><?php  if($model['sex']=='m'){ 
                                            echo '男';                                            
                                        }else {
                                            echo '女';
                                            
                                        }?>
                            </td>
                            <td><?php echo $model['birthday'];?></td>
                            <td><?php   foreach($arr as $k => $v){
                                if($k==$model['rfid'])echo $v;
                            }?></td>                               
                        </tr>
                        <?php endforeach;?>
                       
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>   
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
    }      
});
</script> 