<?php
class ProductAdditionClass
{
	public $lastLid = 0;
	public function __construct($dpid,$orderId,$productAdditionId){
		$this->db = Yii::app()->db;
		$this->dpid = $dpid;
		$this->orderId = $orderId;
		$this->lid = $productAdditionId;
		$this->productAddition();
		$this->hasAddition();
	}
	
	public function productAddition(){
		$sql = 'select t.*,t1.main_picture,t1.product_name from nb_product_addition t, nb_product t1 where t.sproduct_id=t1.lid and t.dpid=t1.dpid and t.lid=:lid and t.dpid=:dpid';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':dpid',$this->dpid);
		$conn->bindValue(':lid',$this->lid);
		$this->productAddition = $conn->queryRow();
	}
    
    public function save(){
    	if($this->hasAddition){
    		$sql = 'update nb_order_product set amount=amount+1 where dpid=:dpid and order_id=:orderId and main_id=:mainId and product_id=:productId and product_order_status=0 and delete_flag=0';
    		$conn = $this->db->createCommand($sql);
    		$conn->bindValue(':dpid',$this->dpid);
			$conn->bindValue(':orderId',$this->orderId);
			$conn->bindValue(':mainId',$this->productAddition['mproduct_id']);
			$conn->bindValue(':productId',$this->productAddition['sproduct_id']);
			$result = $conn->execute();
    	}else{
    		$sql = 'SELECT NEXTVAL("order_product") AS id';
			$maxId = Yii::app()->db->createCommand($sql)->queryRow();
	    	$insertData = array(
	    						'lid'=>$maxId['id'],
	    						'dpid'=>$this->productAddition['dpid'],
	    						'create_at'=>date('Y-m-d H:i:s',time()),
                                                        'update_at'=>date('Y-m-d H:i:s',time()),
	    						'order_id'=>$this->orderId,
	    						'main_id'=>$this->productAddition['mproduct_id'],
	    						'product_id'=>$this->productAddition['sproduct_id'],
	    						'price'=>$this->productAddition['price'],
	    						'amount'=>1,
	    						);
	    	$result = $this->db->createCommand()->insert('nb_order_product',$insertData);
	    	$this->lastLid = $maxId['id'];
    	}
    	
    	if($result){
    		return true;
    	}else{
    		return false;
    	}
    } 
    
    public function hasAddition(){
    	$sql = 'select * from nb_order_product where dpid=:dpid and order_id=:orderId and main_id=:mainId and product_id=:productId and delete_flag=0';
    	$conn = $this->db->createCommand($sql);
		$conn->bindValue(':dpid',$this->dpid);
		$conn->bindValue(':orderId',$this->orderId);
		$conn->bindValue(':mainId',$this->productAddition['mproduct_id']);
		$conn->bindValue(':productId',$this->productAddition['sproduct_id']);
		$result = $conn->queryRow();
		if($result){
			$this->lastLid = $result['lid'];
			$this->hasAddition = true;
		}else{
			$this->hasAddition = false;
		}
    }  
}