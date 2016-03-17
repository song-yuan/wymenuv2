<?php

/**
 * This is the model class for table "nb_order_product".
 *
 * The followings are the available columns in table 'nb_order_product':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $order_id
 * @property string $set_id
 * @property string $private_promotion_lid
 * @property string $product_id
 * @property string $is_retreat
 * @property string $is_print
 * @property string $price
 * @property string $offprice
 * @property integer $amount
 * @property integer $zhiamount
 * @property string $is_waiting
 * @property string $weight
 * @property string $taste_memo
 * @property string $retreat_memo
 * @property string $is_giving
 * @property string $product_status
 * @property string $delete_flag
 * @property string $product_order_status
 * @property string $is_sync
 */
class OrderProduct extends CActiveRecord
{
    public $all_money;
	public $all_total;
	public $all_price;
	public $y_all;
	public $m_all;
	public $d_all;
	public $all_jiage;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_order_product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, order_id', 'required'),
			array('lid, dpid, order_id', 'numerical', 'integerOnly'=>true),
			array('main_id,set_id,private_promotion_lid, product_id, price, weight', 'length', 'max'=>10),
			array('is_print, is_retreat, is_waiting, is_giving, delete_flag, product_order_status', 'length', 'max'=>1),
			//array('taste_memo', 'length', 'max'=>50),
			array('create_at, offprice, amount, zhiamount', 'safe'),
			array('is_sync','length','max'=>50),
			array('product_name, product_pic','length','max'=>255),
			array('product_status, product_type','length','max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, is_sync, order_id, main_id, set_id,private_promotion_lid, product_id, product_name, product_pic, product_type, is_retreat, is_print, price, offprice, amount, zhiamount, is_waiting, weight, taste_memo, is_giving, product_status, delete_flag, product_order_status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		'company' => array(self::BELONGS_TO , 'Company' , 'dpid'),
                'order' => array(self::BELONGS_TO , 'Order' , '','on'=>'t.dpid=order.dpid and t.order_id=order.lid'),
                'order8' => array(self::BELONGS_TO , 'Order' , '','on'=>'t.dpid=order8.dpid and t.order_id=order8.lid and order8.order_status=7'),
                'product'=> array(self::BELONGS_TO , 'Product' , '','on'=>'t.dpid=product.dpid and t.product_id=product.lid'),
                'productSet'=> array(self::BELONGS_TO , 'ProductSet' , '','on'=>'t.dpid=productSet.dpid and t.set_id=productSet.lid'),
				'productcg'=>array(self::BELONGS_TO , 'Product' , '','on'=>'t.dpid=product.dpid and t.product_id=product.lid '),
				
				
		
		
		
				
				
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => '自身id，统一dpid下递增',
			'dpid' => '店铺id',
                        'category_id' => yii::t('app','单品分类'),
                        'original_price' => yii::t('app','产品原价'),
			'create_at' => yii::t('app','创建时间'),
			'update_at' => yii::t('app','更新时间'),
			'order_id' => yii::t('app','订单'),
			'set_id' => yii::t('app','套餐编号'),
                        'private_promotion_lid' => yii::t('app','专享活动ID'),
                        'main_id' => yii::t('app','主菜'),
			'product_id' => yii::t('app','产品编号'),
				'product_name' => yii::t('app','产品名称'),
				'product_pic' => yii::t('app','产品图片'),
				'product_type' => yii::t('app','产品类型'),
			'is_retreat' => '0非退菜，1退菜',
                        'is_print' => yii::t('app','厨打'),
			'price' => yii::t('app','下单时价格'),
                        'offprice' => yii::t('app','优惠价格'),
			'amount' => yii::t('app','下单数量'),
			'zhiamount' => yii::t('app','下单只数'),
			'is_waiting' => '0不等叫，1等叫，2已上菜',
			'weight' => yii::t('app','重量'),
			'taste_memo' => yii::t('app','口味说明'),
			'is_giving' => yii::t('app','赠送'),
				'product_status' => yii::t('app','状态'),
			'delete_flag' => '1删除，0未删除',
			'product_order_status' => '0未下单、1已下单',
				'is_sync' => yii::t('app','是否同步'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('set_id',$this->set_id,true);
                $criteria->compare('private_promotion_lid',$this->set_id,true);
                $criteria->compare('main_id',$this->main_id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('product_pic',$this->product_pic,true);
		$criteria->compare('product_type',$this->product_type,true);
		$criteria->compare('is_retreat',$this->is_retreat,true);
                $criteria->compare('is_print',$this->is_print,true);
		$criteria->compare('price',$this->price,true);
                $criteria->compare('offprice',$this->offprice,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('zhiamount',$this->zhiamount);
		$criteria->compare('is_waiting',$this->is_waiting,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('taste_memo',$this->taste_memo,true);
		$criteria->compare('is_giving',$this->is_giving,true);
		$criteria->compare('product_status',$this->product_status,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);
		$criteria->compare('product_order_status',$this->product_order_status,true);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderProduct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        static public function getOrderProducts($orderId,$dpid){
		$db = Yii::app()->db;
		$sql = "select t.*,t1.product_name as product_name_order,t1.original_price,t1.is_temp_price,t1.is_special,t1.is_discount,
                                t1.product_unit,t1.weight_unit,t1.is_weight_confirm,t1.printer_way_id,t2.category_name,t3.set_name from nb_order_product t
				left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid
				left join nb_product_category t2 on t1.category_id = t2.lid and t1.dpid=t2.dpid
                                left join nb_product_set t3 on t.set_id = t3.lid and t.dpid=t3.dpid
				where t.order_id in (".$orderId.") and t.dpid=".$dpid.' and t.delete_flag=0 order by t.product_type asc,t.order_id, t.set_id,t.main_id,t1.category_id,t.lid'; //and is_retreat=0
		return $db->createCommand($sql)->queryAll();
	}
	
	static public function getOrderProductsByType($orderId,$dpid,$type=1){
		$db = Yii::app()->db;
		$sql = "select * from nb_order_product where order_id in (".$orderId.") and dpid=".$dpid." and delete_flag=0 and product_type=".$type." order by order_id,lid";
		return $db->createCommand($sql)->queryAll();
	}   
        
        //单个订单已经下单的产品
        static public function getHasOrderProducts($orderId,$dpid){
		$db = Yii::app()->db;
		$sql = "select t.*,t1.product_name,t1.original_price,t1.is_temp_price,t1.is_special,t1.is_discount,
                                t1.product_unit,t1.weight_unit,t1.is_weight_confirm,t1.printer_way_id,t2.category_name,t3.set_name from nb_order_product t
				left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid
				left join nb_product_category t2 on t1.category_id = t2.lid and t1.dpid=t2.dpid
                                left join nb_product_set t3 on t.set_id = t3.lid and t.dpid=t3.dpid
				where t.order_id=".$orderId." and t.dpid=".$dpid." and t.is_print=1 and t.product_order_status in ( '1','2') and t.delete_flag=0 order by t.set_id,t.main_id,t1.category_id";
		return $db->createCommand($sql)->queryAll();// and t.is_retreat=0
	}
        
        //多个订单已经下单的产品
        static public function getHasOrderProductsAll($orderList,$dpid){
		$db = Yii::app()->db;//下行CF修改AS之后
		$sql = "select t.*,t1.product_name as product_name_p,t1.original_price as original_price_p,t1.is_temp_price,t1.is_special,t1.is_discount,
                t1.product_unit,t1.weight_unit,t1.is_weight_confirm,t1.printer_way_id,t2.category_name,t3.set_name from nb_order_product t
				left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid
				left join nb_product_category t2 on t1.category_id = t2.lid and t1.dpid=t2.dpid
                left join nb_product_set t3 on t.set_id = t3.lid and t.dpid=t3.dpid
				where t.order_id in (".$orderList.") and t.dpid=".$dpid." and t.is_print=1 and t.product_order_status in ('1','2') and t.delete_flag=0 order by t.set_id,t.main_id,t1.category_id";
		return $db->createCommand($sql)->queryAll();// and t.is_retreat=0
	}
        
        //单个订单挂单的产品
        static public function getHasPauseProducts($orderId,$dpid){
		$db = Yii::app()->db;//CFtianjia AS之后
		$sql = "select t.*,t1.product_name as product_name_P,t1.original_price as original_price_p,t1.is_temp_price,t1.is_special,t1.is_discount,
                                t1.product_unit,t1.weight_unit,t1.is_weight_confirm,t1.printer_way_id,t2.category_name,t3.set_name from nb_order_product t
				left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid
				left join nb_product_category t2 on t1.category_id = t2.lid and t1.dpid=t2.dpid
                                left join nb_product_set t3 on t.set_id = t3.lid and t.dpid=t3.dpid
				where t.order_id=".$orderId." and t.dpid=".$dpid.' and t.product_order_status in("0","9") and t.is_retreat=0 and t.delete_flag=0 order by t.set_id,t.main_id,t1.category_id';
		return $db->createCommand($sql)->queryAll();
	}
        
        //微信单个订单支付的产品
        static public function getHasPayProducts($orderId,$dpid){
		$db = Yii::app()->db;
		$sql = "select t.*,t1.product_name as product_name_p,t1.original_price as original_price_p,t1.is_temp_price,t1.is_special,t1.is_discount,
                                t1.product_unit,t1.weight_unit,t1.is_weight_confirm,t1.printer_way_id,t2.category_name,t3.set_name from nb_order_product t
				left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid
				left join nb_product_category t2 on t1.category_id = t2.lid and t1.dpid=t2.dpid
                                left join nb_product_set t3 on t.set_id = t3.lid and t.dpid=t3.dpid
				where t.order_id=".$orderId." and t.dpid=".$dpid.' and t.product_order_status in("2","8") and t.delete_flag=0 order by t.product_type ASC,t.set_id,t.main_id,t1.category_id';
		return $db->createCommand($sql)->queryAll();// and t.is_retreat=0
	}
        
        //多个订单挂单的产品
        static public function getHasPauseProductsAll($orderList,$dpid){
		$db = Yii::app()->db;
		$sql = "select t.*,t1.product_name,t1.original_price,t1.is_temp_price,t1.is_special,t1.is_discount,
                                t1.product_unit,t1.weight_unit,t1.is_weight_confirm,t1.printer_way_id,t2.category_name,t3.set_name from nb_order_product t
				left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid
				left join nb_product_category t2 on t1.category_id = t2.lid and t1.dpid=t2.dpid
                                left join nb_product_set t3 on t.set_id = t3.lid and t.dpid=t3.dpid
				where t.order_id in (".$orderList.") and t.dpid=".$dpid.' and t.product_order_status in("0","9") and t.is_retreat=0 and t.delete_flag=0 order by t.set_id,t.main_id,t1.category_id';
		return $db->createCommand($sql)->queryAll();//
	} 
        
        //原价，产品原价
	static public function getTotal($orderlist,$dpid){
		$db = Yii::app()->db;
		$sql = "select sum(price*(IF(weight>0,weight,amount))) as total from nb_order_product "
                        . "where delete_flag=0 and product_order_status in ('1','2') and is_giving=0 "
                        . "and is_retreat=0 and order_id in (".$orderlist.") and dpid=".$dpid;
		$ret= $db->createCommand($sql)->queryScalar();
                return empty($ret)?0:$ret;
	}
        
        //订单产品原价
	static public function getOriginalTotal($orderlist,$dpid){
		$db = Yii::app()->db;
		$sql = "select ifnull(sum(t.price*(IF(t.weight>0,t.weight,t.amount))),0.00) as total"
                        . ",ifnull(sum(t.original_price*(IF(t.weight>0,t.weight,t.amount))),0.00) as originaltotal"//CF:-->tp.->>t.
                        . " from nb_order_product t"//CF:-->,nb_product tp
                        . " where t.delete_flag=0 and t.product_order_status in('1','2')"//CF:-->t.dpid=tp.dpid and t.product_id=tp.lid and 
                        . " and t.is_giving=0 and t.is_retreat=0 and t.order_id in (".$orderlist.") and t.dpid=".$dpid;
		$ret= $db->createCommand($sql)->queryRow();
                return $ret;
	}
        
        //订单产品原价
	static public function getPayTotalAll($orderlist,$dpid){
		$db = Yii::app()->db;
		$sql = "select ifnull(sum(t.price*(IF(t.weight>0,t.weight,t.amount))),0.00) as paytotal"
                        . " from nb_order_product t"
                        . " where t.delete_flag=0 and t.product_order_status=2"
                        . " and t.is_giving=0 and t.is_retreat=0 and t.order_id in (".$orderlist.") and t.dpid=".$dpid;
		$ret= $db->createCommand($sql)->queryScalar();
                return $ret;
	}
        
        //单个订单的挂单总价
        static public function getPauseTotal($orderId,$dpid){
		$db = Yii::app()->db;
		$sql = "select ifnull(sum(t.price*(IF(t.weight>0,t.weight,t.amount))),0.00) as total"
                        . ",ifnull(sum(tp.original_price*(IF(t.weight>0,t.weight,t.amount))),0.00) as originaltotal"
                        . " from nb_order_product t,nb_product tp"
                        . " where t.dpid=tp.dpid and t.product_id=tp.lid and t.delete_flag=0 "
                        . " and t.is_giving=0 and t.is_retreat=0 and t.order_id=".$orderId." and t.dpid=".$dpid;
		$ret= $db->createCommand($sql)->queryRow();
                return $ret;
	}
        
        //微信已支付的总价，状态是8
        static public function getPayTotal($orderId,$dpid){
            //echo "222";exit;
		$db = Yii::app()->db;
		$sql = "select ifnull(sum(t.price*(IF(t.weight>0,t.weight,t.amount))),0.00) as total"
                        . ",ifnull(sum(t.original_price*(IF(t.weight>0,t.weight,t.amount))),0.00) as originaltotal"
                        . " from nb_order_product t"
                        . " where t.product_order_status in('2','8') and t.delete_flag=0 "
                        . " and t.is_giving=0 and t.is_retreat=0 and t.order_id=".$orderId." and t.dpid=".$dpid;
		$ret= $db->createCommand($sql)->queryRow();
                //echo $sql;exit;
                return $ret;
	}
        
        //多个订单的挂单总价
        static public function getPauseTotalAll($orderList,$dpid){
		$db = Yii::app()->db;
		$sql = "select ifnull(sum(t.price*(IF(t.weight>0,t.weight,t.amount))),0.00) as total"
                        . ",ifnull(sum(t.original_price*(IF(t.weight>0,t.weight,t.amount))),0.00) as originaltotal"//CF:-->tp.>>t.
                        . " from nb_order_product t"//CF:-->,nb_product tp
                        . " where t.delete_flag=0 "//CF:-->t.dpid=tp.dpid and t.product_id=tp.lid and 
                        . " and t.is_giving=0 and t.is_retreat=0 and t.order_id in(".$orderList.") and t.dpid=".$dpid;//CF:-->
		$ret= $db->createCommand($sql)->queryRow();
                return $ret;
	}
        
        //获得一个订单所有参见的微信会员活动，客户自己点菜的活动，不是后台活动
        static public function getPromotion($accountno,$dpid){
		$db = Yii::app()->db;
		$sqlorderproductpromotion=
                            "select t.promotion_id,t.promotion_type,tpm.promotion_title,(sum((tp.original_price-tp.price)*IF(tp.weight>0,tp.weight,tp.amount))) as subprice"
                            . " from nb_order_product_promotion t"
                            . " LEFT JOIN nb_normal_promotion tpm on t.dpid=tpm.dpid and t.promotion_id=tpm.lid"
                            . " LEFT JOIN nb_order_product tp on t.dpid=tp.dpid and t.order_product_id=tp.lid"
                            . " where t.account_no=".$accountno." and t.dpid=".$dpid." and t.promotion_type=0"
                            . "  group by t.promotion_id,t.promotion_type,tpm.promotion_title"
                            . " UNION "
                            . "select t.promotion_id,t.promotion_type,tpm.promotion_title,(sum((tp.original_price-tp.price)*IF(tp.weight>0,tp.weight,tp.amount))) as subprice"
                            . " from nb_order_product_promotion t"
                            . " LEFT JOIN nb_private_promotion tpm on t.dpid=tpm.dpid and t.promotion_id=tpm.lid "
                            . " LEFT JOIN nb_order_product tp on t.dpid=tp.dpid and t.order_product_id=tp.lid"
                            . " where t.account_no=".$accountno." and t.dpid=".$dpid." and t.promotion_type=1"
                            . "  group by t.promotion_id,t.promotion_type,tpm.promotion_title";
                //echo $sqlorderproductpromotion;exit;
		return $db->createCommand($sqlorderproductpromotion)->queryAll();
	}
        
        //有折扣优惠后的价格//没有参与折扣的去出来
        static public function getDisTotal($orderlist,$dpid){
		$db = Yii::app()->db;
                //关联 nb_order_production_promotion
		$sql = "select sum(t.price*(IF(t.weight>0,t.weight,t.amount))) as total from nb_order_product t"
                        ." left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid"
                        . " where t.delete_flag=0 and t1.is_discount=1 and t.product_order_status=1"
                        . " and t.lid not in (select order_product_id from nb_order_product_promotion where dpid=.".$dpid." and order_id in (".$orderlist."))"
                        . " and t.is_giving=0 and t.is_retreat=0 and t.order_id in (".$orderlist.") and t.dpid=".$dpid;
		$ret= $db->createCommand($sql)->queryScalar();
                return empty($ret)?0:$ret;
	}
        
        //固定台的最大的status
        static public function getMaxStatus($site_id,$is_temp,$dpid){
		$db = Yii::app()->db;
		$sql = "select ifnull(max(tt2.product_order_status),-1)+1 as max_status from nb_order tt1 left join nb_order_product tt2"
                                . " on tt1.dpid=tt2.dpid and tt1.lid=tt2.order_id"
                                . " where tt1.is_temp='".$is_temp."'and tt1.order_status in ('1','2','3')"
                                . "  and tt1.site_id=".$site_id." and tt1.dpid=".$dpid;
		$ret= $db->createCommand($sql)->queryScalar();
                return empty($ret)?0:$ret;
	}
        
       static public function getTaste($orderId,$dpid,$isorder){
		$db = Yii::app()->db;
		$sql = "select t.*,t1.order_id from nb_taste t"
                        . " left join nb_order_taste t1 on t.dpid=t1.dpid and t.lid=t1.taste_id"
                        . " where t1.order_id=".$orderId." and t.dpid=".$dpid.' and t1.is_order='.$isorder;
		return $db->createCommand($sql)->queryAll();
	}
        
        static public function getRetreat($orderId,$dpid){
		$db = Yii::app()->db;
		$sql = "select t.*,t1.order_detail_id,t1.retreat_memo from nb_retreat t"
                        . " left join nb_order_retreat t1 on t.dpid=t1.dpid and t.lid=t1.retreat_id"
                        . " where t1.order_detail_id=".$orderId." and t.dpid=".$dpid.' and t1.delete_flag=0';
		return $db->createCommand($sql)->queryAll();
	}
        
        static public function setOrderCall($dpid,$site_id,$is_temp){
            $db = Yii::app()->db;
            $retarr=array();
            $temparr=array();
            $lidlistarr=array("0000000000"=>"0000000000");
            //$lidlisttemp="";
            $lidlistproduct="0000000000";
            $transaction = $db->beginTransaction();
            try {
		$sqlorderproduct="select tp.lid,tp.order_id,t.site_id,t.is_temp,t.order_type from nb_order_product tp"
                        . " LEFT JOIN nb_order t on t.dpid=tp.dpid and t.lid=tp.order_id"
                        . " where tp.product_order_status='9' and tp.dpid="
                        .$dpid; 
                if($site_id!="0000000000")
                {
                    $sqlorderproduct.=" and t.site_id=".$site_id." and t.is_temp=".$is_temp;
                }
                $modelorderproduct=$db->createCommand($sqlorderproduct)->queryAll();
                foreach ( $modelorderproduct as $mop)
                {
                    if($mop["is_temp"]=="1")
                    {
	                    if($mop["order_type"]=="2"){
		                        $lidlistproduct.=",".$mop["lid"];
		                        $temparr[$mop["order_id"]]="微信外卖".$mop["site_id"]%1000;
	                    	}elseif($mop["order_type"]=="3"){
		                        $lidlistproduct.=",".$mop["lid"];
		                        $temparr[$mop["order_id"]]="微信预约".$mop["site_id"]%1000;
	                    	}else{
		                        $lidlistproduct.=",".$mop["lid"];
		                        $temparr[$mop["order_id"]]="临时座位".$mop["lid"]%1000;
	                    	}
                    }else{
                        $lidlistproduct.=",".$mop["lid"];
                        $lidlistarr[$mop["order_id"]]=$mop["site_id"];
                    }
                }
                
                $db->createCommand("update nb_order_product set product_order_status='0'"
                        . " where lid in (".$lidlistproduct.") and product_order_status='9'"
                        . " and dpid=".$dpid)->execute();
                
                $sqlsite="select concat(ifnull(tt.name,''),ifnull(t.serial,'')) as name from nb_site t "
                        . " LEFT JOIN nb_site_type tt on t.type_id=tt.lid and t.dpid =tt.dpid"
                        . " where t.dpid=".$dpid." and t.lid in (".implode(",",$lidlistarr).")";
                //var_dump($sqlsite);exit;
                $modelsite=$db->createCommand($sqlsite)->queryAll();
                //var_dump($modelsite);exit;
                foreach ($modelsite as $value) {
                    array_push($retarr, $value["name"]);
                }  
                foreach ($temparr as $value) {
                    array_push($retarr, $value);
                } 
                //var_dump($retarr);exit;                
                $transaction->commit();
                            
            } catch (Exception $e) {
                $transaction->rollback();
            }
            return $retarr;
	}
        
        static public function setPayCall($dpid,$site_id,$is_temp){
            $db = Yii::app()->db;
            $retarr=array();
            $temparr=array();
            $lidlistarr=array("0000000000"=>"0000000000");
            //$lidlisttemp="";
            $lidlistproduct="0000000000";
            $transaction = $db->beginTransaction();
            try {
		$sqlorderproduct="select tp.lid,tp.order_id,t.site_id,t.is_temp,t.order_type from nb_order_product tp"
                        . " LEFT JOIN nb_order t on t.dpid=tp.dpid and t.lid=tp.order_id"
                        . " where tp.product_order_status='8' and tp.dpid="
                        .$dpid; 
                if($site_id!="0000000000")
                {
                    $sqlorderproduct.=" and t.site_id=".$site_id." and t.is_temp=".$is_temp;
                }
                $modelorderproduct=$db->createCommand($sqlorderproduct)->queryAll();
                foreach ( $modelorderproduct as $mop)
                {
                    if($mop["is_temp"]=="1")
                    {
                    	if($mop["order_type"]=="2"){
	                        $lidlistproduct.=",".$mop["lid"];
	                        $temparr[$mop["order_id"]]="微信外卖".$mop["site_id"]%1000;
                    	}elseif($mop["order_type"]=="3"){
	                        $lidlistproduct.=",".$mop["lid"];
	                        $temparr[$mop["order_id"]]="微信预约".$mop["site_id"]%1000;
                    	}else{
	                        $lidlistproduct.=",".$mop["lid"];
	                        $temparr[$mop["order_id"]]="临时座位".$mop["lid"]%1000;
                    	}
                    }else{
                        $lidlistproduct.=",".$mop["lid"];
                        $lidlistarr[$mop["order_id"]]=$mop["site_id"];
                    }
                }
                
                $db->createCommand("update nb_order_product set product_order_status='2'"
                        . " where lid in (".$lidlistproduct.") and product_order_status='8'"
                        . " and dpid=".$dpid)->execute();
                
                $sqlsite="select concat(ifnull(tt.name,''),ifnull(t.serial,'')) as name from nb_site t "
                        . " LEFT JOIN nb_site_type tt on t.type_id=tt.lid and t.dpid =tt.dpid"
                        . " where t.dpid=".$dpid." and t.lid in (".implode(",",$lidlistarr).")";
                //var_dump($sqlsite);exit;
                $modelsite=$db->createCommand($sqlsite)->queryAll();
                //var_dump($modelsite);exit;
                foreach ($modelsite as $value) {
                    array_push($retarr, $value["name"]);
                }  
                foreach ($temparr as $value) {
                    array_push($retarr, $value);
                } 
                //var_dump($retarr);exit;                
                $transaction->commit();
                            
            } catch (Exception $e) {
                $transaction->rollback();
            }
            return $retarr;
	}
        
        static public function setPauseJobs($compayId,$padId){
		$sqljoborder="select distinct order_id from nb_order_product where product_order_status='9' and dpid=".$compayId." order by order_id";
                $modeljoborder=Yii::app()->db->createCommand($sqljoborder)->queryAll();                        
                //var_dump($padId,$pad);exit;
                //前面加 barcode
                $precode="";//"1D6B450B".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                $cardtotal=0;
                $memo="挂单清单";
                $temporderid=0;
                //$orderProducts=array();
                //$modelprinterjob=array();
                if(!empty($modeljoborder))
                {
                    $pad=Pad::model()->with('printer')->find(' t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$compayId,'lid'=>$padId));
                    //var_dump($modeljoborder);exit;
                    foreach ($modeljoborder as $mjo)
                    {
                        if($mjo["order_id"] !='0000000000')
                        {
                            $order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$mjo["order_id"],':dpid'=>$compayId));
                            if(empty($order))
                            {
                                //throw new Exception(json_encode(array('status'=>false,'msg'=>"该订单不存在")));
                                continue;
                            }
                            $productTotalarray = OrderProduct::getPauseTotal($order->lid,$order->dpid);
                            //var_dump($productTotalarray);exit;
                            $total=$productTotalarray["total"];
                            $originaltotal=$productTotalarray["originaltotal"]; 
                            $criteria = new CDbCriteria;
                            $criteria->condition =  't.dpid='.$compayId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                            $criteria->order = ' t.lid desc ';                    
                            $siteNo = SiteNo::model()->find($criteria);
                            if($order->is_temp=='0')
                            {
                                $total = Helper::calOrderConsume($order,$siteNo, $total);
                            }
                            $order->should_total=$originaltotal;
                            $order->reality_total=$total["total"];
                            //var_dump($order);exit;
                            $orderProducts = OrderProduct::getHasPauseProducts($order->lid,$order->dpid);
                            //var_dump($orderProducts);exit;
                            $printList = Helper::printPauseList($order,$orderProducts,$pad,$precode,"0",$memo,$cardtotal);
//                                    if($printList["status"])
//                                    {
//                                        array_push($modelprinterjob,$printList);
//                                    }
                        }                               
                    }
                    //var_dump($modelprinterjob);exit;
                }
	}
	static public function setProductallJobs($compayId){
		//$sqljobsite="select distinct site_id from nb_order_product where product_order_status='8' and dpid=".$compayId." order by site_id";
		//$modeljobsite=Yii::app()->db->createCommand($sqljobsite)->queryAll();exit;
		$sqljoborder="select distinct order_id from nb_order_product where product_order_status='8' and dpid=".$compayId." order by order_id";
		$modeljoborder=Yii::app()->db->createCommand($sqljoborder)->queryAll();
		
		if(!empty($modeljoborder))
		{
			
			foreach ($modeljoborder as $mjo)
			{
				if($mjo["order_id"] !='0000000000')
				{
					//$site = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':site_id'=>$mjo["site_id"],':dpid'=>$compayId));
					$order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$mjo["order_id"],':dpid'=>$compayId));
						
					if(empty($order))
					{
						//throw new Exception(json_encode(array('status'=>false,'msg'=>"该订单不存在")));
						continue;
					}
					$criteria = new CDbCriteria;
					$criteria->condition =  't.dpid='.$compayId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
					$criteria->order = ' t.lid desc ';
					$siteNo = SiteNo::model()->find($criteria);
					$site=new Site();
					if($order->is_temp=="0")
					{
						$criteria2 = new CDbCriteria;
						$criteria2->condition =  't.dpid='.$compayId.' and t.lid='.$order->site_id ;
						$criteria2->order = ' t.lid desc ';
						$site = Site::model()->with("siteType")->find($criteria2);
					}elseif($order->is_temp=="1"){

						$criteria2 = new CDbCriteria;
						$criteria2->condition =  't.dpid='.$compayId.' and t.lid="0000000040"' ;
						$criteria2->order = ' t.lid desc ';
						$site = Site::model()->with("siteType")->find($criteria2);
						//var_dump($site);exit;
					}
                
                	$orderList=Order::getOrderList($compayId,$siteNo->site_id,$siteNo->is_temp);
					//$orderProducts = OrderProduct::getHasPauseProducts($order->lid,$order->dpid);
					//var_dump($orderList);exit;
					$printList = Helper::printKitchenAll8($order,$orderList,$site,false);
					//return $printList;
				}
			}
			//var_dump($modelprinterjob);exit;
		}
	}
        
        static public function setPayJobs($compayId,$padId){
		$sqljoborder="select distinct order_id from nb_order_product where product_order_status='8' and dpid=".$compayId." order by order_id";
                $modeljoborder=Yii::app()->db->createCommand($sqljoborder)->queryAll();                        
                //var_dump($modeljoborder,$padId);exit;
                //前面加 barcode
                $precode="";//"1D6B450B".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                $cardtotal=0;
                $memo="客人自助付款单";
                $temporderid=0;
                //$orderProducts=array();
                //$modelprinterjob=array();
                if(!empty($modeljoborder))
                {
                    $pad=Pad::model()->with('printer')->find(' t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$compayId,'lid'=>$padId));
                    //var_dump($modeljoborder);exit;
                    foreach ($modeljoborder as $mjo)
                    {
                        if($mjo["order_id"] !='0000000000')
                        {
                            //echo "1111";
                            $order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$mjo["order_id"],':dpid'=>$compayId));
                            //var_dump($order);echo "1111";exit;
                            if(empty($order))
                            {
                                //throw new Exception(json_encode(array('status'=>false,'msg'=>"该订单不存在")));
                                continue;
                            }
                            //$productTotalarray = OrderProduct::getPauseTotal($order->lid,$order->dpid);
                            $productTotalarray = OrderProduct::getPayTotal($order->lid,$order->dpid);
                            //var_dump($productTotalarray);exit;
                            $total=$productTotalarray["total"];
                            $originaltotal=$productTotalarray["originaltotal"]; 
                            $criteria = new CDbCriteria;
                            $criteria->condition =  't.dpid='.$compayId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                            $criteria->order = ' t.lid desc ';                    
                            $siteNo = SiteNo::model()->find($criteria);
//                            if($order->is_temp=='0')
//                            {
//                                $total = Helper::calOrderConsume($order,$siteNo, $total);
//                            }
                            $order->should_total=$originaltotal;
                            $order->reality_total=$total;//["total"];
                            //var_dump($order);exit;
                            //$orderProducts = OrderProduct::getHasPauseProducts($order->lid,$order->dpid);
                            $orderProducts = OrderProduct::getHasPayProducts($order->lid,$order->dpid);
                            //var_dump($orderProducts);exit;
                            //$printList = Helper::printPauseList($order,$orderProducts,$pad,$precode,"0",$memo,$cardtotal);
                            $printList = Helper::printPayList($order,$orderProducts,$pad,$precode,"0",$memo,$cardtotal);
//                                    if($printList["status"])
//                                    {
//                                        array_push($modelprinterjob,$printList);
//                                    }
                            //var_dump($printList);exit;
                        }                               
                    }
                    //return $printList;
                    //var_dump($modelprinterjob);exit;
                }
	}
}
