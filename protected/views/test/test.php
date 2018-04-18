<?php 
	
?>
<script type="text/javascript">
var jsonStr = '[{"lid":"5","dpid":"27","create_at":"2018-04-18 09:47:38","update_at":"2018-04-18 09:47:38","jobid":"448","sync_type":"2","sync_url":"admin/dataAppSync/createOrder","content":"{\"order_info\":{\"creat_at\":\"2018-04-18 09:47:08\",\"account_no\":\"152401602823\",\"classes\":\"0\",\"username\":\"jq\",\"site_id\":\"0\",\"is_temp\":\"1\",\"number\":\"1\",\"order_status\":\"4\",\"order_type\":\"0\",\"should_total\":\"3.6\",\"reality_total\":\"4\",\"takeout_typeid\":\"0\",\"callno\":\"0\"},\"order_product\":[{\"is_set\":\"0\",\"set_id\":\"0\",\"product_id\":\"4982\",\"product_name\":\"超级牛肉堡\",\"original_price\":\"4.00\",\"price\":\"3.6000\",\"amount\":\"1\",\"product_taste\":[],\"product_promotion\":[]}],\"order_discount\":[{\"discount_title\":\"会员卡优惠\",\"discount_type\":\"4\",\"discount_id\":\"15755402686\",\"discount_money\":\"0.40\"}],\"order_pay\":[{\"pay_amount\":\"3.6\",\"paytype\":\"4\",\"payment_method_id\":\"0\",\"paytype_id\":\"15755402686\",\"remark\":\"\"}],\"member_points\":{\"card_type\":\"0\",\"receive_points\":\"3\",\"member_card_rfid\":\"15755402686\"}}","delete_flag":"0","is_sync":"11111"},{"lid":"7","dpid":"27","create_at":"2018-04-18 10:27:30","update_at":"2018-04-18 10:27:30","jobid":"448","sync_type":"2","sync_url":"admin/dataAppSync/createOrder","content":"{\"order_info\":{\"creat_at\":\"2018-04-18 10:27:22\",\"account_no\":\"152401844225\",\"classes\":\"0\",\"username\":\"jq\",\"site_id\":\"0\",\"is_temp\":\"1\",\"number\":\"1\",\"order_status\":\"4\",\"order_type\":\"0\",\"should_total\":\"3.6\",\"reality_total\":\"4\",\"takeout_typeid\":\"0\",\"callno\":\"0\"},\"order_product\":[{\"is_set\":\"0\",\"set_id\":\"0\",\"product_id\":\"4982\",\"product_name\":\"超级牛肉堡\",\"original_price\":\"4.00\",\"price\":\"3.6000\",\"amount\":\"1\",\"product_taste\":[],\"product_promotion\":[]}],\"order_discount\":[{\"discount_title\":\"会员卡优惠\",\"discount_type\":\"4\",\"discount_id\":\"18601739563\",\"discount_money\":\"0.40\"}],\"order_pay\":[{\"pay_amount\":\"3.6\",\"paytype\":\"4\",\"payment_method_id\":\"0\",\"paytype_id\":\"18601739563\",\"remark\":\"\"}],\"member_points\":{\"card_type\":\"0\",\"receive_points\":\"3\",\"member_card_rfid\":\"18601739563\"}}","delete_flag":"0","is_sync":"11111"}]';
$.ajax({
    url : 'http://menu.wymenu.com/wymenuv2/admin/dataAppSync/batchSync',
    type : 'POST',
    data : {
         admin_id : 548,
         poscode : '04480027819609',
         data:jsonStr
     },
     success:function(msg){
         // 保存同步
         if(msg.status){
              
         }
     },
     dataType:'json'
});
</script>

