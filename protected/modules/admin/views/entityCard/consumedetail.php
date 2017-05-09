<style>
ul {
    padding:0;
    margin:0
}
li{
   
   list-style-type :none;
}
.person_info{
        font-size: 16px;
        padding-left: 8px;
        margin-bottom: 30px;
        color:blue;
    }
.person_info li{
    
    margin-right: 40px;
}
</style>
<div class="page-content">
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','会员流水'),'url'=>$this->createUrl('entityCard/detail' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','消费流水'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityCard/detail' , array('companyId' => $this->companyId,'type'=>0)))));?>
    <div class="row">   
        <div class="col-md-12">
            <div class="portlet purple box">
                <div class="portlet-body" >
            <div class="person_info">
                <ul>
                    <li class="pull-left">
                        <span>
                            卡号：<?php echo $model->selfcode ;?>
                        </span>
                    </li>
                    <li class="pull-left">
                            会员等级：<?php echo $model->brandUserLevel?$model->brandUserLevel->level_name:'';?>
                    </li>
                    <li class="pull-left">
                        <span> 
                           姓名：<?php echo $model->name ;?>
                        </span>
                    </li>                       
                    <li class="pull-left">                               
                        <span>
                            会员折扣：<?php echo sprintf("%.2f",$model->brandUserLevel?$model->brandUserLevel->level_discount:'1');?>
                        </span>     
                    </li>
                    <li class="pull-left">
                        <span>
                            生日折扣：<?php echo sprintf("%.2f",$model->brandUserLevel?$model->brandUserLevel->birthday_discount:'1');?>
                        </span>                                
                    </li>
                </ul>
                <div style="clear:both;"></div>                                          
            </div> 
                          
              
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                             
                            </tr>
                        </thead>
                        <tbody>
                            
                    </table>
              </div>
           </div>
        </div>   
    </div>
</div>
