<style>
    .pbom{
            width: 98%;
            height: auto;
            margin-left: 1%;
            border: 1px solid pink;
    }
    .pbomhead{
            width: 98%;
            margin-left: 1%;
            border-bottom: 1px solid silver;
    }
    .pbomheadtitle{
            padding: 2px 8px;
            float: left;
            font-size: 18px;
    }
    .pbombody{
            width:98%;
            height: 400px;
            margin-left: 1%;
            border: 1px solid red;
            border-top: none;
            overflow-y: auto;
    }

    .pageend {
            margin-bottom: 10px;
    }
    .pageend .closediv {
            float: right;
            margin-right: 10px;
    }
    .pageend .closediv button{
            border: 1px solid silver;
    }
    .width40{
            width: 30% !important;
    }
    .clear{
            clear: both;
    }
    .uhide{
            display: none;
    }
    input[type="checkbox"]{
            width: 20px;
            height: 20px;
    }
    .wxcardbg{
            width: 220px;
            height: 115px;
            margin-top: 10px;
            margin-left: 15px;
            border:1px solid red;
            border-radius: 5px;
            float: left;
            color: red;
            background-color: #ff4940;
            position: relative;
    }
    .wxcardhead{
            width: 100%;
            height: 85px;
            font-size: 22px;
    }
    .wxcardheadl{
            width: 50%;
            height: 85px;
            float: left;
            border-right: 1px dashed white;
    }
    .wxcardheadll{
            width: 90px;
            height: 75px;
            margin-top: 5px;
            marin-left: 5px;
    }
    .wxcardheadll .money{
            width: 75px;
            height: 75px;
            line-height: 75px;
            float: left;
            font-size: 40px;
            font-weight: 900;
            color: white;
            text-align: center;
    }
    .wxcardheadll .unit{
            width: 15px;
            height: 75px;
            line-height: 100px;
            font-size: 22px;
            float: left;
            color: black;
    }
    .wxcardheadr{
            width: 48%;
            height: 85px;
            float: left;
    }
    .wxcardheadr .top{
            width: 100%;height: 30px;line-height: 30px;font-size: 16px;text-align: center;color: #000;
    }
    .wxcardheadr .cen{
            width: 100%;
            height: 20px;
            line-height: 20px;
            font-size: 16px;
            text-align: center;
            color: #000;
            display: none;
    }
    .wxcardheadr .bot{
            width: 100%;
            height: 35px;
            line-height: 35px;
            font-size: 18px;
            text-align: center;
            color: #000;
            font-weight: 600;
    }
    .wxcardend{
            width: 100%;
            height: 30px;
            line-height: 30px;
            font-size: 12px;
            border-top: 1px dashed pink;
            text-align: center;
            color: #fff;
    }
    .wxcardactive{
            position: absolute;
            top: 30px;
            left: 80px;
    }
    .addsave{
            float: right;
            margin: 10px 10px 0px 0px;
    }
    .addsave button{
            font-size: 18px;
            padding: 4px 10px;
            border-radius: 5px;
            background-color: #6beaff;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','群发优惠券');?></div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                </div>
            </div>
            <div style="min-height: 500px;" class="portlet-body form">
                <div class="pbom">
                    <div class="pbomhead">
                        <div class="pbomheadtitle mataction" tasteid="0000000000">系统券</div>
                        <div class="clear"></div>
                    </div>
                    <div class="pbombody">
                        <?php if($models):?>
                        <?php foreach ($models as $model):?>
                            <div class="wxcardbg" plid="<?php echo $model->lid;?>" pcode="<?php echo $model->sole_code;?>">
                                <div class="wxcardhead" style="">
                                    <div class="wxcardheadl"style="">
                                        <div class="wxcardheadll"style="">
                                            <div class="money" style=""><span><?php echo floor($model->cupon_money);?></span></div>
                                            <div class="unit" style="">元</div>
                                        </div>
                                    </div>
                                    <div class="wxcardheadr" style="">
                                        <div class="top" style="">满<span><?php echo floor($model->min_consumer);?></span>可使用</div>
                                        <div class="cen" style="">赠送1张</div>
                                        <div class="bot" style="">代金券</div>
                                    </div>
                                </div>
                                <div class="wxcardend" style="">限<span><?php echo date('Y-m-d',strtotime($model->begin_time));?></span> 至<span><?php echo date('Y-m-d',strtotime($model->end_time));?></span>使用</div>
                                <div class="wxcardactive uhide" ><img width="50px" style="" src="../../../../img/checked.png"/></div>
                            </div>									
                        <?php endforeach;?>
                        <?php endif;?>
                        <div class="clear"></div>									
                    </div>
                </div>
                <div class="addsave"style="float: right;">
                    <button id="add_save">保存</button>
                </div>
            </div>
        </div>
        <div class="pageend">
            <div class="closediv">
                <button id="close_modal" type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','关 闭');?></button>
            </div>
            <div class="clear"></div>
        </div>
    </div>							
</div>
<!-- END PAGE CONTENT-->
<script type="text/javascript">
$(document).ready(function(){
    $('.wxcardbg').on('click',function(){
        if($(this).hasClass('activechecked')){
            $(this).removeClass('activechecked');
            $(this).find('.wxcardactive').addClass('uhide');
        }else{
            $(this).find('.wxcardactive').removeClass('uhide');
            $(this).addClass('activechecked');
        }
    });
    
    $('#add_save').on('click', function(){
        var plids = '';
        var users = '<?php echo $users;?>';
        $('.activechecked').each(function(){
            var plid = $(this).attr('plid');
            var pcode = $(this).attr('pcode');
            plids = plid +','+ pcode +';'+ plids;
            });
            //alert(plids);
        if(plids!=''){
            plids = plids.substr(0,plids.length-1);//除去最后一个“;”
            //alert(plids);
        }else{
            alert("<?php echo yii::t('app','请至少选择一项！！！');?>");
            return false;
        }
        var url = "<?php echo $this->createUrl('wechatMarket/storsentwxcard',array('companyId'=>$this->companyId));?>/plids/"+plids+"/users/"+users;
        $.ajax({
            url:url,
            type:'POST',
            //data:plids,//CF
            //async:false,
            dataType: "json",
            success:function(msg){
                var data=msg;
                if(data.status){
                    layer.msg("保存成功");
                    document.getElementById("close_modal").click();                   
                }else{
                    alert("保存失败");
                }
            },
            error: function(msg){
                var data=msg;
                alert(data.msg);
            }
        });
    });
});       
</script>	