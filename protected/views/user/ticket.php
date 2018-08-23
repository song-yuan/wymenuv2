<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('我的券');
?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/example.css');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<style>
    .page{
       background-color: #EDEDED; 
    }
    
.nav-tabs {
    border-bottom: 1px solid #CFCFCF;
    padding-top: 9px;
    background-color: white!important;
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
}
.nav-tabs>li {
    float: left;
    margin-bottom: -1px;
    width: 33%;
    text-align: center;
    font-size: 14px;
       
}
.nav>li {
    position: relative;
    display: block;
}
 .nav a{
     color: #333333;
     padding-bottom: 8px;
    display: inline-block;
 }

 .active a{
     display:inline;
     color:  #e4393c ;
     border-bottom:2px solid #e4393c; 
 }

    .cupon_items{
       padding-top: 55px;
    }
   .cupon_item{
   	   width:90%;
   	   margin:0 auto 20px;
    }
    .type{
       width: 32%;
       height: 78px;
       padding-top: 30px;
       background-color: rgb(242,174,29);
       float: left;
       border-top-left-radius: 6px;
       border-bottom-left-radius: 6px;
       text-align: center;
       color:#fff;
  
    }
    	
    .type .price{
       color:white;
       font-weight: bold;
       
    }
    .type .price .yen{
       position: relative;
        top: -8px;
        font-size: 20px;
    }
   .type .price .money{
    line-height: 30px;
    font-size: 35px;
    
    }
   .type .limit{
     font-size: 14px;
    }
    .range{
        width: 64%;
        height: 88px;
        background-color: #fff;
        float: left;
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
        padding:10px 2%;
        font-size: 13px;
        color:#787878;
    }
    .range .describe{
        min-height: 68px;
    }
    .cupon_item .tips{
    	background-color: #fff;
    	border-top:1px dashed #FBF9FE;
    	padding:2px 5px;
    	font-size:12px;
    	border-radius:6px;
    }
</style>


    <div class="page">
        
        <ul class="nav nav-tabs" id="attr_info">
            <li class="active"><a href="javascript:void(0)" data-target ='not_used'><span>待使用</span></a></li>
            <li><a href="javascript:void(0)" data-target ='expire'><span>已过期</span></a></li>
            <li><a href="javascript:void(0)" data-target ='used'><span>已使用</span></a></li>
            <div style="clear:both"></div>
        </ul>
        
        
        <div class="cupon_items" id="not_used">
            <?php if(!empty($not_useds)):?>   
            <?php foreach($not_useds as $v):?>
            <div class="cupon_item">
                <div class="type ">
                        <div class="price">
                            <span class="yen">&yen;</span>
                            <span class="money">
                                
                                <?php 
                                if($v['cupon_money'] == floor($v['cupon_money'])){
                               		echo floor($v['cupon_money']);
                                }else{
                                    echo $v['cupon_money'];
                                }
                                ?>
                            </span>
                        </div>
                        <div class="limit">
                            满<?php echo floor($v['min_consumer']);?>元可用  
                        </div>   
                </div>
                <div class="range " >
                    <div class="describe">使用条件：<?php echo $v['cupon_memo'];?></div>
                    <div class="date">
                    		有效期:
                           <span>
                             <?php echo date('Y.m.d',strtotime($v['valid_day']));?>   
                           </span> 
                           -
                           <span>
                             <?php echo date('Y.m.d',strtotime($v['close_day']));?>
                           </span>                             
                    </div> 
                </div>
                <div style="clear:both"></div>
                <div class="tips">使用范围:
                <?php 
                $typeArr = explode(',',$v['type']); 
                if(in_array(1, $typeArr)){
                	echo '堂食 ';
                }
                if(in_array(2, $typeArr)){
                	echo '微信堂食 ';
                }
                if(in_array(3, $typeArr)){
                	echo '微信外卖 ';
                }
                if($v['type']=='1'){
                	echo '只限领取店铺使用 ';
                }elseif($v['type']=='2'){
                	echo '只限部分店铺可用 ';
                }
                if($v['type_prod']=='1'){
                	echo '只限部分产品可用 ';
                }
                ?>
                </div>
             </div>
            
            <?php endforeach;?>
            <?php endif;?>
         </div>
        <div class="cupon_items " id="expire">
            <?php if(!empty($expires)):?>
            <?php foreach($expires as $v):?>
            <div class="cupon_item">
                <div class="type ">
                  
                        <div class="price">
                            <span class="yen">&yen;</span>
                            <span class="money"><?php echo $v['cupon_money'];?></span>
                        </div>
                        <div class="limit">
                            满<?php echo floor($v['min_consumer']);?>元可用
                        </div>
                   
                </div>
                <div class="range ">
                    <div class="describe">限制条件：<?php echo $v['cupon_memo'];?></div>
                    <div class="date">
                           <span>
                              <?php echo date('Y.m.d',strtotime($v['valid_day']));?>  
                           </span> 
                           -
                           <span>
                             <?php echo date('Y.m.d',strtotime($v['close_day']));?>
                           </span>                             
                    </div> 
                </div>
                <div style="clear:both"></div>
             </div>
            <?php endforeach;?>
            <?php endif;?>
        </div>
        <div class="cupon_items " id="used">
            <?php if(!empty($useds)):?>
            <?php foreach($useds as $v):?>
            <div class="cupon_item">
                <div class="type ">
                  
                        <div class="price">
                            <span class="yen">&yen;</span>
                            <span class="money"><?php echo $v['cupon_money'];?></span>
                        </div>
                        <div class="limit">
                            满<?php echo floor($v['min_consumer']);?>元可用
                        </div>
                   
                </div>
                <div class="range ">
                    <div class="describe">限制条件：<?php echo $v['cupon_memo'];?></div>
                    <div class="date">
                           <span>
                             <?php echo date('Y.m.d',strtotime($v['valid_day']));?> 
                           </span> 
                           -
                           <span>
                             <?php echo date('Y.m.d',strtotime($v['close_day']));?>
                           </span>                             
                    </div> 
                </div>
                <div style="clear:both"></div>
             </div>
             <?php endforeach;?>
            <?php endif;?>
        </div>
         
     </div>   

  <script>
    $(function(){

         $(".cupon_items").hide();
          $(".cupon_items").eq(0).show();
          $("#attr_info li a").click(function(){
            $(this).parent("li").siblings("li").removeClass("active");
            $(this).parent("li").addClass("active");
            //全部隐藏
            $(".cupon_items").hide();
            //当前对应的显示
           $("#"+$(this).attr("data-target")).show();
        });
           
        }); 
    
</script>
