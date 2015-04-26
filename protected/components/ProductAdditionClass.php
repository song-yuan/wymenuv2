<?php
class ProductAdditionClass
{
	public function __construct($dpid,$orderId,$productAdditionId){
		$this->db = Yii::app()->db;
		$this->dpid = $dpid;
		$this->orderId = $orderId;
		$this->lid = $productAdditionId;
		$this->productAddition();
	}
	
	public function productAddition(){
		$sql = 'select * from nb_product_addition where lid=:lid and dpid=:dpid';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':dpid',$this->dpid);
		$conn->bindValue(':lid',$this->lid);
		$this->productAddition = $conn->queryRow();
	}
    
    public function save(){
    	$sql = 'SELECT NEXTVAL("order_product") AS id';
		$maxId = Yii::app()->db->createCommand($sql)->queryRow();
    	$insertData = array(
    						'lid'=>$maxId['id'],
    						'dpid'=>$this->productAddition['dpid'],
    						'create_at'=>date('Y-m-d H:i:s',time()),
    						'order_id'=>$this->orderId,
    						'main_id'=>$this->productAddition['mproduct_id'],
    						'product_id'=>$this->productAddition['sproduct_id'],
    						'price'=>$this->productAddition['price'],
    						'amount'=>1,
    						);
    	$result = $this->db->createCommand()->insert('nb_order_product',$insertData);
    	if($result){
    		return true;
    	}else{
    		return false;
    	}
    }   
}