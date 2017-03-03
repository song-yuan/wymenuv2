<style>



.portlet-body>.row{
    margin:15px 0 30px 0;
}
.item-header{
    text-align: right;
    padding:0px;
}


input[type='button']{
   
  
}
@media (max-width: 768px) {
    .item-header{
        text-align: left;
        font-size:15px;
        margin-bottom: 10px;
        background-color:#f9f9f9;
        padding:10px;
        
    }
    .form-group{
        width:66.666%!important;
}
}
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
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','会员管理'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">   
    <div class="col-md-12">

    <div class="portlet purple box">

        <div class="portlet-body" >
             <div class="row">
                    <div class="col-xs-12 col-sm-1 item-header">卡号/手机号 ：</div>
                    <div class=" col-xs-12 col-sm-3">

                        <div class="form-group ">                                    
                            <input type="text" class="form-control" id="number" placeholder="卡号/手机号">
                          </div>

                    </div>
                    <div class="col-xs-12 col-sm-2 ">
                        <button type="button"   class=' btn  btn-primary  '>查 询</button>            
                    </div>
            </div>
            <div>历史账单明细</div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#">储值</a></li>
                <li role="presentation"><a href="#">积分</a></li>
                <li role="presentation"><a href="#">优惠券</a></li>
            </ul>
        
            
        </div> 
    </div>
        
</div>
</div>
</div>

