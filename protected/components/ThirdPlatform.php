<?php
    /**
    * 第三方平台对接
    * 交易流水数据提交
    */
    class ThirdPlatform 
    {
    	public static function getXstInfo(){
    		$sql = "select * from nb_web_service where is_avalible = 1 and delete_flag = 0";
    		$models = Yii::app()->db->createCommand($sql)->queryAll();
    		return $models;
    	}
    	public static function xst($order,$info){
			$soap=new SoapClient($info['interface_url']);
			$soap->soap_defencoding = 'utf-8';
			$soap->decode_utf8 = false;
			$soap->xml_encoding = 'utf-8';
			$ParamData = array(array(
					'STATIONNAME'=>$info['stationname'],
			    	'STATIONID'=>$info['stationid'],
			    	'SHOPNAME'=>$info['shopname'],
			    	'SHOPNO'=>$info['shopid'],
			    	'BILLTYPE'=>"",
			    	'BILLNO'=>$order['lid'],//订单号
			    	'BILLALLPRICES'=>$order['total'],//总价
			    	'BILLTIME'=>$order['create_at'],//下单时间
			    	'PAYMENT'=>$order['payment'],//现金
			    	'TRANSTYPE'=>$order['transtype'],//销售
			    	'SOURCETYPE'=>$order['sourcetype'],//POS机
			    	'SOURCENO'=>"",
			    	'BRANCH'=>$info['branch']
			));
			$ParamData = json_encode($ParamData,JSON_UNESCAPED_UNICODE);
			$param["tradeChange"] = $ParamData;
			$param["valiKey"] = $valiKEY;
			$result = $soap->__Call('Save',array($param));
			Helper::writeLog($ParamData.'--'.$result);
		}
    }
?>