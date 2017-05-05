<?php
class Common{
    static public function getStockName($stockId){
        //var_dump($stockId);
        $unitname = "";
        $sql="select t.unit_name from nb_material_unit t where  t.lid=".$stockId ;
        $connect = Yii::app()->db->createCommand($sql);
        $stock = $connect->queryRow();
        //var_dump($stock);exit;
        $unitname = $stock['unit_name'];
        return $unitname;
    }
    static public function getSalesName($salesId){
        //var_dump($stockId);
        $unitname = "";
        $sql="select t.unit_name from nb_material_unit t where  t.lid=".$salesId;
        $connect = Yii::app()->db->createCommand($sql);
        $stock = $connect->queryRow();
        //var_dump($stock);exit;
        $unitname = $stock['unit_name'];
        return $unitname;
    }
    static public function getmfrName($mfrId){
        $mfrname = "";
        $sql="select t.manufacturer_name from nb_manufacturer_information t where  t.lid=".$mfrId;
        $connect = Yii::app()->db->createCommand($sql);
        $minfo = $connect->queryRow();
        $mfrname = $minfo['manufacturer_name'];
        return $mfrname;
    }
    static public function getorgName($orgId){
        $orgname = "";
        $sql="select t.organization_name from nb_organization_information t where  t.lid=".$orgId;
        $connect = Yii::app()->db->createCommand($sql);
        $oinfo = $connect->queryRow();
        $orgname = $oinfo['organization_name'];
        return $orgname;
    }
    static public function getuserName($userId){
        $username = "";
        $sql="select t.username,t.staff_no from nb_user t where  t.lid=".$userId;
        $connect = Yii::app()->db->createCommand($sql);
        $user = $connect->queryRow();
        $username = $user['username'].'('.$user['staff_no'].')';
        return $username;
    }
    static public function getmaterialName($materialId){
        $materialname = "";
        $sql="select t.material_name from nb_product_material t where  t.lid=".$materialId;
        $connect = Yii::app()->db->createCommand($sql);
        $material = $connect->queryRow();
        $materialname = $material['material_name'];
       // var_dump($sql);exit;
        return $materialname;
    }
    static public function getStockSortCode($stockId){
        //var_dump($stockId);
        $unitname = "";
        $sql="select t.unit_name,t.sort_code from nb_material_unit t where  t.lid=".$stockId ;
        $connect = Yii::app()->db->createCommand($sql);
        $stock = $connect->queryRow();
        //var_dump($stock);exit;
        $unitname = $stock['sort_code'];
        return $unitname;
    }
    /**
     * 
     * 获取入库单入库单
     * 
     */
     public static function getStorageOrder($companyId){
    	$sql="select * from nb_storage_order t where  t.dpid=".$companyId.' and status=3 and delete_flag=0';
    	$result = Yii::app()->db->createCommand($sql)->queryAll();
    	return $result;
    }
    /*
     * 生成总部唯一编码。。。
     * */
    static public function getCode($dpid,$lid,$id)
    {
    	$ret = substr("0000000000".$dpid,-3).substr("0000000000".$lid,-4).substr("0000000000".$id,-5);
    	return $ret;
    }
}
?>