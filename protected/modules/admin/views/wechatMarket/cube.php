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
   padding-top: 20px !important;
}

ul {
    list-style: none;
}
.panel_body  {
    margin-bottom: 10px;
} 	
.panel_body a, .panel_body a:hover, .panel_body a:visited {
    text-decoration:none;
    -webkit-transition: all .3s linear;
    -moz-transition: all .3s linear;
    -o-transition: all .3s linear;
    -ms-transition: all .3s linear;
    transition: all .3s linear;
}
.fliter-group{
    margin: 0 auto;
    padding:20px 0px 20px 20px;
    width: 95%;
  /*  background-color: #eee;*/
}




.panel_body ul li {
  display: block;
  float: left;
  margin:0px 10px 0px 10px;
  overflow:auto;
}
.panel_body ul li a{
    display:block;
    font-size:1.4rem;
    padding:0 8px;
    color:#000;
}
.selected a{
    color:#e77600!important;
}
.selected {
    display: block;   
    outline:.1rem solid  #e77600!important;
}

.panel_item{
    float: left;
    line-height: 40px;
}
.panel-title{
    font-size:1.5rem;
    color: #2d78f4;
    font-weight: bold;
}
@media (max-width: 768px) {

    .fliter-group{
        width: 100%;
    }
    .panel_body ul li{
        margin:5px 10px 5px 10px; 
    }
}
	
.clear{
    clear: both;
    height:0;
    font-size: 1px;
    line-height: 0px;
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
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','会员商城'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">
    <div class="col-md-12">

    <div class="portlet purple box">

        <div class="portlet-body" >
           
                <div class="fliter-group">
                    <div class="panel_body  ">                      
                        <div class="panel_item  panel-title">会员资料</div>
                        <div class="panel_item " >
                             <ul class="list-group">
                                <li date-type="1-1"><a href="javascript:;">手机号</a></li>
                                <li date-type="1-2"><a href="javascript:;">性别</a></li>
                                <li date-type="1-3"><a href="javascript:;">年龄</a></li>
                                <li date-type="1-4"><a href="javascript:;">生日</a></li>
                                <li date-type="1-5"><a href="javascript:;">开卡时间</a></li>
                                <li date-type="1-6"><a href="javascript:;">消费门店</a></li>
                                <li date-type="1-7"><a href="javascript:;">顾客来源</a></li>
                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>                
                  
                    <div class="panel_body">
                        <div class="panel_item panel-title ">    会员账号</div>
                        <div class="panel_item ">
                            <ul class="list-group">
                                    <li date-type="2-1"><a href="javascript:;">会员等级</a></li>
                                    <li date-type="2-2"><a href="javascript:;">储值余额</a></li>
                                    <li date-type="2-3"><a href="javascript:;">积分余额</a></li>
                                    <li date-type="2-4"><a href="javascript:;">可用券张数</a></li>
                                    <li date-type="2-5"><a href="javascript:;">可用代金券总额</a></li>
                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>
                    
                    <div class="panel_body">
                        <div class="panel_item  panel-title">交易数据</div>
                        <div class="panel_item ">
                            <ul class="list-group">
                                    <li date-type="3-1"><a href="javascript:;">消费次数</a></li>
                                    <li date-type="3-2"><a href="javascript:;">距上次消费天数</a></li>
                                    <li date-type="3-3"><a href="javascript:;">累计消费金额</a></li>
                                    <li date-type="3-4"><a href="javascript:;">单笔消费金额</a></li>
                                    <li date-type="3-5"><a href="javascript:;">累计储值金额</a></li>
                                    <li date-type="2-6"><a href="javascript:;">单笔储值金额</a></li>
                                   
                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>                    
                </div>
                    
             
    
        </div> 
    </div>
        

</div>
</div>
<script type="text/javascript">

$(".list-group li").click(function() {
var type = $(this).attr("date-type");
var copyThisB = $(this).clone();

$(this).toggleClass("selected");
});

</script>
