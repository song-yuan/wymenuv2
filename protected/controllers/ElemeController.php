<?php
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
		
		$notify = new ElmNotify();
		$notify->Handle();
	}
}