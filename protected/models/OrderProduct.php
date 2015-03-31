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
 * @property string $price
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
			array('lid, dpid, order_id, taste_memo, retreat_memo', 'required'),
			array('lid, dpid, order_id, amount, zhiamount', 'numerical', 'integerOnly'=>true),
			array('set_id, product_id, price, weight', 'length', 'max'=>10),
			array('is_retreat, is_waiting, is_giving, delete_flag, product_order_status', 'length', 'max'=>1),
			array('taste_memo, retreat_memo', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, order_id, set_id, product_id, is_retreat, price, amount, zhiamount, is_waiting, weight, taste_memo, retreat_memo, is_giving, delete_flag, product_order_status', 'safe', 'on'=>'search'),
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
                        'category_id' => '分类',
			'create_at' => '创建时间',
			'update_at' => '更新时间',
			'order_id' => '订单',
			'set_id' => '套餐编号',
			'product_id' => '产品编号',
			'is_retreat' => '0非退菜，1退菜',
			'price' => '下单时价格',
			'amount' => '下单数量',
			'zhiamount' => '下单只数',
			'is_waiting' => '0不等叫，1等叫，2已上菜',
			'weight' => '重量',
			'taste_memo' => '口味说明',
			'retreat_memo' => '退菜理由',
			'is_giving' => '是否赠送',
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
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('is_retreat',$this->is_retreat,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('zhiamount',$this->zhiamount);
		$criteria->compare('is_waiting',$this->is_waiting,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('taste_memo',$this->taste_memo,true);
		$criteria->compare('retreat_memo',$this->retreat_memo,true);
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
		$sql = "select t.*,t1.*,t2.category_name from nb_order_product t
				left join nb_product t1 on t.product_id = t1.lid and t.dpid=t1.dpid
				left join nb_product_category t2 on t1.category_id = t2.lid and t1.dpid=t2.dpid
				where t.order_id=".$orderId." and t.dpid=".$dpid;
		return $db->createCommand($sql)->queryAll();
	}
	static public function getTotal($orderId,$dpid){
		$db = Yii::app()->db;
		$sql = "select sum(price*amount) as total from nb_order_product where order_id=".$orderId." and dpid=".$dpid;
		$ret= $db->createCommand($sql)->queryScalar();
                return empty($ret)?0:$ret;
	}
}
