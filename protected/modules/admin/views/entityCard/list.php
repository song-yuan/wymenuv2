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
.col-xs-6 a:visited,col-sm-3 a:visited{
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
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','实体卡'),'url'=>''))));?>		
    <div class="portlet purple box ">                      
        <div class="portlet-body">
            <div class="panel_body">
                <div class="row">
                    <div class="col-xs-6  col-sm-3">
                        <a href="<?php echo $this->createUrl('memberWxlevel/index',array('companyId'=>$this->companyId));?>">
                            <div class="fa  fa-sort-amount-asc list_picture"></div>
                            <div class="list_text">卡等级</div>
                        </a>  
                    </div>
                    <div class="col-xs-6  col-sm-3">
                        <a href="<?php echo $this->createUrl('entityCard/cardsearch',array('companyId'=>$this->companyId));?>">
                            <div class="fa  fa-user  list_picture"></div>
                            <div class="list_text">卡查询</div>
                        </a> 
                    </div>
                    <div class="col-xs-6 col-sm-3">
                       <a href="<?php echo $this->createUrl('entityCard/recharge',array('companyId'=>$this->companyId));?>">
                            <div class="fa fa-rmb list_picture"></div>
                            <div class="list_text">充值</div>
                        </a> 
                    </div>
                    <?php if(Yii::app()->user->role <= User::ADMIN_VICE):?>
                    <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo $this->createUrl('StaffRecharge/index',array('companyId'=>$this->companyId));?>">
                            <div class="fa fa-dollar  list_picture"></div>
                            <div class="list_text">员工充值</div>
                        </a> 
                    </div>
                    <?php endif;?>
                     <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo $this->createUrl('entityCard/zero',array('companyId'=>$this->companyId));?>">
                            <div class="fa fa-frown-o list_picture"></div>
                            <div class="list_text">清零</div>
                        </a> 
                     </div>
                    <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo $this->createUrl('member/index',array('companyId'=>$this->companyId));?>">
                            <div class="fa fa-folder-open  list_picture"></div>
                            <div class="list_text">添加会员</div>
                        </a> 
                    </div>
                    <div class="col-xs-6 col-sm-3">
                        <a href="#">
                            <div class="fa fa-heart-o   list_picture"></div>
                            <div class="list_text">生日关怀</div>
                        </a> 
                    </div>
                    <div class="col-xs-6 col-sm-3">
                        <a href="javascript:void(0)">
                            <div class="fa  fa-th-large list_picture"></div>
                            <div class="list_text">卡魔方</div>
                        </a> 
                    </div>
                    <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo $this->createUrl('entityCard/active',array('companyId'=>$this->companyId));?>">
                            <div class="fa  fa-sun-o list_picture"></div>
                            <div class="list_text">活跃会员</div>
                        </a> 
                    </div> 
                    <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo $this->createUrl('entityCard/unActive',array('companyId'=>$this->companyId));?>">
                            <div class="fa  fa-moon-o list_picture"></div>
                            <div class="list_text">不活跃会员</div>
                        </a> 
                    </div>
                    <div class="col-xs-6 col-sm-3">
                        <a href="#">
                            <div class="fa  fa-files-o list_picture"></div>
                            <div class="list_text">会员流水</div>
                        </a> 
                    </div>
                </div>                             
            </div>        
        </div> 
    </div>                    
</div>



 
                      