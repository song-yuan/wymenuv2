<?php 
?>
<script>
var newSyncData = new Array();
var dpid = 28;
var domain = 'http://menu.wymenu.com/wymenuv2/';
var data = '{"order":[{"nb_order":{"lid":"0000030926","dpid":"0000000027","create_at":"2017-08-26 19:13:33","update_at":"2017-08-26 19:13:33","user_id":"0000002182","account_no":"170826030926","classes":"0000000000","username":"","site_id":"0000003174","is_temp":"1","number":"1","order_status":"3","order_type":"6","takeout_typeid":"0","takeout_status":"0","appointment_time":"2017-08-26 19:13:33","lock_status":"0","should_total":"16.20","reality_total":"27.00","callno":"","paytype":"1","payment_method_id":"0000000000","pay_time":"2017-08-26 19:13:33","remark":"\u4e0d\u653e\u8fa3\u6912\u4e86\u901f\u5ea6\u5feb\u70b9\u54e6\u54e6\u54e6\u770b\u770b","taste_memo":"","cupon_branduser_lid":"0000000000","cupon_money":"0.00","is_sync":"11111"},"nb_order_platform":false,"nb_order_product":[{"lid":"62276","dpid":"27","create_at":"2017-08-26 19:13:33","update_at":"2017-08-26 19:13:33","order_id":"30926","set_id":"0","private_promotion_lid":"0","main_id":"0","product_id":"3444","product_name":"\u9ec4\u91d1\u8774\u8776\u867e\uff084\u4e2a\uff09","product_pic":"\/wymenuv2\/.\/uploads\/company_0000000026\/70890547-4CBB-435E-AC6F-EFAF061A06F2.jpg","product_type":"0","is_retreat":"0","original_price":"6.00","price":"6.0000","offprice":"100%","amount":"1","zhiamount":"0","is_waiting":"0","weight":"0.00","taste_memo":"","is_giving":"0","is_print":"0","product_status":"0","delete_flag":"0","product_order_status":"8","is_sync":"11111","set_name":"","set_price":"0.00","product_taste":[],"product_promotion":[]},{"lid":"62278","dpid":"27","create_at":"2017-08-26 19:13:33","update_at":"2017-08-26 19:13:33","order_id":"30926","set_id":"0","private_promotion_lid":"0","main_id":"0","product_id":"3442","product_name":"\u9999\u8fa3\u9e21\u7fc5\uff084\u5757\uff09","product_pic":"\/wymenuv2\/.\/uploads\/company_0000000026\/A8B8F4E1-FCCE-4B8D-952D-8D1D57D1AB28.jpg","product_type":"0","is_retreat":"0","original_price":"10.00","price":"10.0000","offprice":"100%","amount":"1","zhiamount":"0","is_waiting":"0","weight":"0.00","taste_memo":"","is_giving":"0","is_print":"0","product_status":"0","delete_flag":"0","product_order_status":"8","is_sync":"11111","set_name":"","set_price":"0.00","product_taste":[],"product_promotion":[]},{"lid":"62280","dpid":"27","create_at":"2017-08-26 19:13:33","update_at":"2017-08-26 19:13:33","order_id":"30926","set_id":"0","private_promotion_lid":"0","main_id":"0","product_id":"2042","product_name":"\u9999\u6ea2\u53ef\u53ef","product_pic":"\/wymenuv2\/.\/uploads\/company_0000000026\/88F74EFC-A72B-4235-B4F1-FEFB5AD1D33D.jpg","product_type":"0","is_retreat":"0","original_price":"6.00","price":"6.0000","offprice":"100%","amount":"1","zhiamount":"0","is_waiting":"0","weight":"0.00","taste_memo":"","is_giving":"0","is_print":"0","product_status":"0","delete_flag":"0","product_order_status":"8","is_sync":"11111","set_name":"","set_price":"0.00","product_taste":[],"product_promotion":[]},{"lid":"62282","dpid":"27","create_at":"2017-08-26 19:13:33","update_at":"2017-08-26 19:13:33","order_id":"30926","set_id":"0","private_promotion_lid":"0","main_id":"0","product_id":"2040","product_name":"\u62ff\u94c1\u5496\u5561","product_pic":"\/wymenuv2\/.\/uploads\/company_0000000026\/6457C911-F41F-413F-83B5-D1CD758DAFAF.jpg","product_type":"0","is_retreat":"0","original_price":"5.00","price":"5.0000","offprice":"100%","amount":"1","zhiamount":"0","is_waiting":"0","weight":"0.00","taste_memo":"","is_giving":"0","is_print":"0","product_status":"0","delete_flag":"0","product_order_status":"8","is_sync":"11111","set_name":"","set_price":"0.00","product_taste":[],"product_promotion":[]}],"nb_order_pay":[{"lid":"0000027270","dpid":"0000000027","create_at":"2017-08-26 19:13:33","update_at":"2017-08-26 19:13:33","order_id":"0000030926","account_no":"170826030926","pay_amount":"16.20","paytype":"10","payment_method_id":"0000000000","paytype_id":"0000000000","remark":"10027000000853","is_sync":"11111"}],"nb_order_taste":[],"nb_order_address":[],"nb_order_account_discount":[]}],"member_card":[]}';
var obj = JSON.parse(data);
var orderobj = obj.order;
var memberCardobj = obj.member_card;
if(orderobj.length > 0){
    for(var i=0;i<orderobj.length;i++){
        var orderPre = orderobj[i];
        var orderDataPre = orderPre.nb_order;
        var orderTypePre = orderDataPre.order_type;
        var accountNoPre = orderDataPre.account_no;
        var orderKey = orderTypePre+'-'+accountNoPre;
        alert(orderKey);
        var remark = orderDataPre.remark;
        alert(remark);
        newSyncData.push(orderKey);
    }
    var newSyncDataStr = JSON.stringify(newSyncData);
    $.ajax({
        url:domain + 'admin/dataAppSync/syncDataCb',
        data:{dpid:dpid,data:newSyncDataStr},
        success:function(msg){
              
        }
    });
}
</script>