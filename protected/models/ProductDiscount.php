<?php

/**
 * This is the model class for table "nb_product_discount".
 *
 * The followings are the available columns in table 'nb_product_discount':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $product_id
 * @property string $is_set
 * @property string $price_discount
 * @property string $is_discount
 * @property integer $order_number
 * @property integer $favourite_number
 * @property integer $all_count
 * @property string $reason
 * @property string $begin_time
 * @property string $end_time
 */
class ProductDiscount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_discount';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reason', 'required'),
			array('order_number, favourite_number, all_count', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, product_id, price_discount', 'length', 'max'=>10),
			array('is_set, is_discount', 'length', 'max'=>1),
			array('reason', 'length', 'max'=>50),
			array('create_at, begin_time, end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, product_id, is_set, price_discount, is_discount, order_number, favourite_number, all_count, reason, begin_time, end_time', 'safe', 'on'=>'search'),
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
			'product'=>array(self::HAS_ONE, 'Product', '', 'on'=>'t.product_id=product.lid and t.is_set=0'),
			'productSet'=>array(self::HAS_ONE, 'ProductSet', '', 'on'=>'t.product_id=productSet.lid and t.is_set=1'),
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
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'product_id' => 'Product',
			'is_set' => yii::t('app','是否是套餐'),
			'price_discount' => yii::t('app','优惠价格或折扣比例'),
			'is_discount' => yii::t('app','类型'),
			'order_number' => '优惠期间的点单数量，不大于all_count',
			'favourite_number' => yii::t('app','优惠期间的点赞数量'),
			'all_count' => '0代表不限数量',
			'reason' => 'Reason',
			'begin_time' => 'Begin Time',
			'end_time' => 'End Time',
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
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('is_set',$this->is_set,true);
		$criteria->compare('price_discount',$this->price_discount,true);
		$criteria->compare('is_discount',$this->is_discount,true);
		$criteria->compare('order_number',$this->order_number);
		$criteria->compare('favourite_number',$this->favourite_number);
		$criteria->compare('all_count',$this->all_count);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('begin_time',$this->begin_time,true);
		$criteria->compare('end_time',$this->end_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductDiscount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
