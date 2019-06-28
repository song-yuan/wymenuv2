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
			if($res){
				echo '已授权成功,请刷新当前页面';
			}
		 }
		 echo '授权失败,请重新授权';
		 exit;
	}
	public function actionElemeOrder(){
		
		$notify = new ElmNotify();
		$notify->Handle();
	}
}