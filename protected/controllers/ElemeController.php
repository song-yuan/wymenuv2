<?php
header("Content-type: text/html; charset=utf-8"); 
class ElemeController extends Controller
{
	/*
	*ElemeToken 授权
	*CreateCategory  菜品分类
	*ShopId 店铺对应
	*CreateItem 菜品对应
	*/
	public function actionElemeToken(){ 
		if(!empty($_GET['code'])){
			$code = $_GET['code'];
			$dpid = $_GET['state'];
			$res = Elm::eleMetoken($code,$dpid);
			echo $res;
		 }else{
			 echo "授权失败";
		}
	}
	public function actionElemeOrder(){
		ob_end_flush();
		ob_start();
		echo '{"message":"ok"}';
		
		header("Content-Type: text/html;charset=utf-8");
		header("Connection: close");
		header('Content-Length: '. ob_get_length());
		
		ob_flush();
		flush();
		$data = file_get_contents('php://input');
		if(!empty($data)){
			$data = urldecode($data);
			$obj = json_decode($data);
			$type = $obj->type;
			$shopId = $obj->shopId;
			$message = $obj->message;
			$elemeDy = Elm::getErpDpid($shopId);
			if($elemeDy){
				$dpid = $elemeDy['dpid'];
				Helper::writeLog($dpid.'--eleme message--'.$data);
				if($type==10){
					$result = Elm::order($message,$dpid);
				}elseif($type==12){
					$result = Elm::orderStatus($message,$dpid);
				}elseif($type==20){
					$result = Elm::orderCancel($message,$dpid);
				}elseif($type==30){
					$result = Elm::refundOrder($message,$dpid);
				}else {
					$result = true;
				}
				if($result){
					echo '{"message":"ok"}';
				}else{
					echo '{"message":"error"}';
				}
			}else{
				echo '{"message":"ok"}';
			}
		}
		exit;
	}
}