<?php 
?>
<script>
var newSyncData = new Array();
var dpid = 28;
var domain = 'http://menu.wymenu.com/wymenuv2/';
var data = '{"order":[{"nb_order":{"lid":"0000028650","dpid":"0000000028","create_at":"2017-06-07 13:51:45","update_at":"2017-08-26 18:48:12","user_id":"0000000000","account_no":"4493613219","classes":"0000000000","username":"","site_id":"0000000000","is_temp":"1","number":"0","order_status":"4","order_type":"7","takeout_typeid":"0","takeout_status":"0","appointment_time":"0000-00-00 00:00:00","lock_status":"0","should_total":"21.00","reality_total":"21.00","callno":"","paytype":"0","payment_method_id":"0000000000","pay_time":"0000-00-00 00:00:00","remark":"\u5168\u6b3e\u652f\u4ed8dfadfdfdfd","taste_memo":"","cupon_branduser_lid":"0000000000","cupon_money":"0.00","is_sync":"111111"},"nb_order_platform":false,"nb_order_product":[{"lid":"54264","dpid":"28","create_at":"2017-06-07 13:51:45","update_at":"2017-06-07 13:51:45","order_id":"28650","set_id":"0","private_promotion_lid":"0","main_id":"0","product_id":"3336","product_name":"\u6cf0\u5f0f\u53cc\u9e21\u5821","product_pic":"","product_type":"0","is_retreat":"0","original_price":"12.00","price":"12.0000","offprice":"100%","amount":"1","zhiamount":"1","is_waiting":"0","weight":"0.00","taste_memo":"","is_giving":"0","is_print":"0","product_status":"0","delete_flag":"0","product_order_status":"2","is_sync":"11111","set_name":"","set_price":"0.00","product_taste":[],"product_promotion":[]}],"nb_order_pay":[{"lid":"0000024668","dpid":"0000000028","create_at":"2017-06-07 13:51:45","update_at":"2017-06-07 13:51:45","order_id":"0000028650","account_no":"4493613219","pay_amount":"21.00","paytype":"14","payment_method_id":"0000000000","paytype_id":"0000000000","remark":"","is_sync":"11111"}],"nb_order_taste":[],"nb_order_address":[{"lid":"0000001318","dpid":"0000000028","create_at":"2017-06-07 13:51:45","update_at":"2017-06-07 13:51:45","order_lid":"28650","consignee":"sfsadf(\u5148\u751f)","province":null,"city":null,"area":null,"street":"\u897f\u85cf\u660c\u90fd\u5e02\u6c14\u8c61\u5c40@#\u897f\u85cf\u81ea\u6cbb\u533a\u660c\u90fd\u5e02\u5361\u82e5\u533a\u57ce\u5173\u9547\u6797\u5ed3\u8def286","postcode":null,"mobile":"13242343534","tel":"13242343534","delete_flag":"0","is_sync":"11111"}],"nb_order_account_discount":[]}],"member_card":[]}';
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