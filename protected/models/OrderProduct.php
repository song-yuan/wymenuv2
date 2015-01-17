<?php

/**
 * This is the model class for table "nb_order_product".
 *
 * The followings are the available columns in table 'nb_order_product':
 * @property string $item_id
 * @property string $order_id
 * @property string $product_id
 * @property string $price
 * @property string $amount
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
			array('order_id, product_id, amount', 'length', 'max'=>10),
			array('price', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('item_id, order_id, product_id, price, amount', 'safe', 'on'=>'search'),
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
		 'prodcut'=>array(self::HAS_ONE,'Product','product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'item_id' => 'Item',
			'order_id' => 'Order',
			'product_id' => 'Product',
			'price' => '价格',
			'amount' => '数量',
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

		$criteria->compare('item_id',$this->item_id,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('amount',$this->amount,true);

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
	static public function getOrderProducts($orderId){
		$db = Yii::app()->db;
		$sql = "select t.*,t1.*,t2.category_name from nb_order_product t
				left join nb_product t1 on t.product_id = t1.product_id
				left join nb_product_category t2 on t1.category_id = t2.category_id
				where t.order_id=:orderId";
		return $db->createCommand($sql)->bindValue(':orderId' , $orderId)->queryAll();
	}
	static public function getTotal($orderId){
		$db = Yii::app()->db;
		$sql = "select sum(price*amount) as total from nb_order_product where order_id=:orderId";
		return $db->createCommand($sql)->bindValue(":orderId" , $orderId)->queryScalar();
	}
	
}
