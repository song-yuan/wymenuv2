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
 * @property string $delete_flag
 * @property string $product_order_status
 */
class OrderProduct extends CActiveRecord
{
        public $all_money;
	public $all_total;
	public $all_price;
	public $y_all;
	public $m_all;
	public $d_all;
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
			array('main_id,set_id, product_id, price, weight', 'length', 'max'=>10),
			array('is_print, is_retreat, is_waiting, is_giving, delete_flag, product_order_status', 'length', 'max'=>1),
			//array('taste_memo', 'length', 'max'=>50),
			array('create_at, offprice, amount, zhiamount', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, order_id, main_id, set_id, product_id, is_retreat, is_print, price, offprice, amount, zhiamount, is_waiting, weight, taste_memo, is_giving, delete_flag, product_order_status', 'safe', 'on'=>'search'),
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
                'productSet'=> array(self::BELONGS_TO , 'ProductSet' , '','on'=>'t.dpid=productSet.dpid and t.set_id=productSet.lid')
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
                        'main_id' => yii::t('app','主菜'),
			'product_id' => yii::t('app','产品编号'),
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
			'delete_flag' => '1删除，0未删除',
			'product_order_status' => '0未下单、1已下单',
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
                $criteria->compare('main_id',$this->main_id,true);
		$criteria->compare('product_id',$this->product_id,true);
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
		$criteria->compare('delete_flag',$this->delete_flag,true);
		$criteria->compare('product_order_status',$this->product_order_status,true);

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
		$sql = "select t.*,t1.product_name,t1.original_price,t1.is_temp_price,t1.is_special,t1.is_discount,
                                t1.product_unit,t1.weight_unit,t1.is_weight_confirm,t1.printer_way_id,t2.category_name,t3.set_name from nb_order_product t
				left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid
				left join nb_product_category t2 on t1.category_id = t2.lid and t1.dpid=t2.dpid
                                left join nb_product_set t3 on t.set_id = t3.lid and t.dpid=t3.dpid
				where t.order_id=".$orderId." and t.dpid=".$dpid.' and t.delete_flag=0 order by t.set_id,t.main_id,t1.category_id'; //and is_retreat=0
		return $db->createCommand($sql)->queryAll();
	}
        
        
        //已经下单的产品
        static public function getHasOrderProducts($orderId,$dpid){
		$db = Yii::app()->db;
		$sql = "select t.*,t1.product_name,t1.original_price,t1.is_temp_price,t1.is_special,t1.is_discount,
                                t1.product_unit,t1.weight_unit,t1.is_weight_confirm,t1.printer_way_id,t2.category_name,t3.set_name from nb_order_product t
				left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid
				left join nb_product_category t2 on t1.category_id = t2.lid and t1.dpid=t2.dpid
                                left join nb_product_set t3 on t.set_id = t3.lid and t.dpid=t3.dpid
				where t.order_id=".$orderId." and t.dpid=".$dpid.' and t.is_print=1 and t.product_order_status=1 and t.is_retreat=0 and t.delete_flag=0 order by t.set_id,t.main_id,t1.category_id';
		return $db->createCommand($sql)->queryAll();
	}
        
	static public function getTotal($orderId,$dpid){
		$db = Yii::app()->db;
		$sql = "select sum(price*(IF(weight>0,weight,amount))) as total from nb_order_product where delete_flag=0 and product_order_status=1 and is_giving=0 and is_retreat=0 and order_id=".$orderId." and dpid=".$dpid;
		$ret= $db->createCommand($sql)->queryScalar();
                return empty($ret)?0:$ret;
	}
        
        static public function getDisTotal($orderId,$dpid){
		$db = Yii::app()->db;
		$sql = "select sum(t.price*(IF(t.weight>0,t.weight,t.amount))) as total from nb_order_product t"
                        ." left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid"
                        . " where t.delete_flag=0 and t1.is_discount=1 and t.product_order_status=1 and t.is_giving=0 and t.is_retreat=0 and t.order_id=".$orderId." and t.dpid=".$dpid;
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
}
