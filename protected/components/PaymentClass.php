<?php
class PaymentClass
{
	public static function getPaymentMethodList($companyId){
		$paymentMethods = PaymentMethod::model()->findAll(' dpid=:dpid',array(':dpid'=>$companyId)) ;
		return CHtml::listData($paymentMethods, 'lid', 'name');
	}
        
}