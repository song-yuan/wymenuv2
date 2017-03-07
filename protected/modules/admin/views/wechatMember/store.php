<style>
.page-content .page-breadcrumb.breadcrumb {
   margin-top: 0px;
   margin-bottom: 20px;
}
.portlet.box.purple {
    border: 1px solid #CFCFCF;
}
.portlet-body{
   min-height: 550px;
}
.portlet-body>.row{
    margin:15px 0 30px 0;
}
.item-header{
    text-align: right;
}
.rule{
    margin:0 0 30px 0;
    padding-top:15px;
    padding-bottom: 15px;
    padding-left:15px;
  /*  border:.1rem solid #CFCFCF;*/
    background-color:#eee;
    border-radius: .4rem;
    
}
.rule.row .col-sm-2,.rule.row .col-sm-10{
    padding-left: 0!important;
    padding-right: 0!important;
}
.spacing-letter{
    display: inline-block;
    width:.3rem;
}
@media (max-width: 768px) {
    .item-header{
        text-align: left;
        font-size:15px;
        margin-bottom: 10px;
        background-color:#f9f9f9;
        padding:10px;
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
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','储值'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>


    <div class="portlet purple box">
        <div class="portlet-body" >
            <div class="row ">
                <div class="col-xs-12 col-sm-2 item-header">功能状态 ：</div>
                <div class="col-xs-12 col-sm-10">
                    <label class="radio-inline">
                         <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> 开启
                    </label>
                    <label class="radio-inline">
                         <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> 关闭
                    </label>
                </div>
            </div>
            <div class="row ">
                    <div class="col-xs-12 col-sm-2 item-header">储值规则 ：</div>
                    <div class="col-xs-12 col-sm-10">
                        <div class="rule row" id="rule1">
                            <div class="col-xs-12 col-sm-2"> 充 <input class="" type="text" value="" style="width:70px;">元，</div>
                            <div class="col-xs-12 col-sm-10"> 
                                <div class="song"><input type="checkbox" value=""><span class="spacing-letter"></span>送<input type="text" value="">元，</div>
                                <div class="song"><input type="checkbox" value=""><span class="spacing-letter"></span>送  </div>
                            </div>
                        </div>
                        <div class="rule row" id="rule2">
                            <div class="col-xs-12 col-sm-2"> 充 <input class="" type="text" value="" style="width:80px;">元，</div>
                            <div class="col-xs-12 col-sm-10"> 
                                <div class="song"><input type="checkbox" value="">送<input type="text" value="">元，</div>
                                <div class="song"><input type="checkbox" value="">送  </div>
                            </div>
                        </div>
                         <div class="rule row" id="rule3">
                            <div class="col-xs-12 col-sm-2"> 充 <input class="" type="text" value="" style="width:70px;">元，</div>
                            <div class="col-xs-12 col-sm-10"> 
                                <div class="song"><input type="checkbox" value="">送<input type="text" value="">元，</div>
                                <div class="song"><input type="checkbox" value="">送  </div>
                            </div>
                        </div>
                    </div>
            </div>
             <div class="row ">
                    <div class="col-xs-12 col-sm-2 item-header">积分有效期 ：</div>
                    <div class="col-xs-12 col-sm-10">
                        <div class="radios">
                            <label>
                               <input type="radio" name="optionskaimaian" id="" value="option1" >
                               当年发放的所有积分，在过期
                              </label>
                       </div>
                       <div class="radios">
                            <label>
                                <input type="radio" name="optionskaimaian" id="" value="option2" >
                                永久有效
                            </label>
                        </div>
                    </div>
            </div>
            <div class="row">
                    <div class="col-xs-12 col-sm-2 item-header">限制与说明 ：</div>
                    <div class=" col-xs-12 col-sm-10">
                       <textarea class="" name="descripiton" rows="4" cols="50" id="descripiton"></textarea>                
                    </div>
            </div> 
        </div>
            
       
        
        </div> 
</div>
        

