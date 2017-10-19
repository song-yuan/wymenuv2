<style>

.portlet-body {
margin-top: 30px;
}

.col-xs-6,col-sm-3{
 text-align: center;
 margin-bottom: 50px;
}

.col-xs-6 a,col-sm-3 a,
.col-xs-6 a:hover,col-sm-3 a:hover,
.col-xs-6 a:visited,col-sm-3 a:visited
{
    text-decoration: none;
   
    display:block;
    
}

.col-xs-6 .list_picture,col-sm-3 .list_picture {
    font-size: 45px;
    color:#3385ff;
    
}
.col-xs-6 .list_text,col-sm-3 .list_text{
    color:black; 
    font-size: 25px;
    margin-top: 10px;
}

  
</style>
<div class="page-content">
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营销活动'),'url'=>''))));?>
   

                <div class="portlet purple box">
					<div class="portlet-title">
						<div class="caption"><i class=" fa fa-gift"></i>营销活动</div>
					</div> 
                    <div class="portlet-body clearfix" >
                        <div class="panel_body">
                             <div class="row">
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="<?php ?>">
                                            <span class="fa fa-cog list_picture"></span>
                                            <div class="list_text">设置</div>
                                        </a> 
                                    </div>
                                    <div class="col-xs-6  col-sm-3">
                                        <a href="<?php echo $this->createUrl('discount/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-pencil-square-o list_picture"></span>
                                            <div class="list_text">POS折扣模板</div>
                                        </a> 
                                    </div>
                                     <div class="col-xs-6  col-sm-3">
                                        <a href="<?php echo $this->createUrl('normalpromotion/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-dollar list_picture"></span>
                                            <div class="list_text">普通优惠</div>
                                        </a> 
                                     </div>
                                    <div class="col-xs-6  col-sm-3">
                                        <a href="<?php echo $this->createUrl('fullSentPromotion/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-gift list_picture"></span>
                                            <div class="list_text">满送优惠</div>
                                        </a> 
                                    </div>
                                    <div class="col-xs-6  col-sm-3">
                                        <a href="<?php echo $this->createUrl('fullMinusPromotion/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-cut list_picture"></span>
                                            <div class="list_text">满减优惠</div>
                                        </a> 
                                    </div>
                                    <div class="col-xs-6  col-sm-3">
                                        <a href="<?php echo $this->createUrl('buysentpromotion/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-leaf list_picture"></span>
                                            <div class="list_text">买送</div>
                                        </a> 
                                    </div>
                                    <div class="col-xs-6  col-sm-3">
                                        <a href="">
                                            <span class="fa fa-pagelines list_picture"></span>
                                            <div class="list_text">买减</div>
                                        </a> 
                                    </div>
                            </div> 
                                  
                    </div> 
                </div>
             
    </div>
</div>
