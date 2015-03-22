<?php

/**
 * This is the model class for table "nb_product_tempprice".
 *
 * The followings are the available columns in table 'nb_product_tempprice':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $product_id
 * @property string $price
 * @property integer $order_number
 * @property integer $favourite_number
 * @property string $begin_time
 * @property string $end_time
 */
class ProductTempprice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_tempprice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid', 'required'),
			array('order_number, favourite_number', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, product_id, price', 'length', 'max'=>10),
			array('create_at, begin_time, end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, product_id, price, order_number, favourite_number, begin_time, end_time', 'safe', 'on'=>'search'),
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
		'product'=>array(self::HAS_ONE, 'Product', '', 'on'=>'t.product_id=product.lid'),
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
			'price' => '时价',
			'order_number' => '时价期间的点单率，和菜品总的点单率重复统计',
			'favourite_number' => '时价期间的点赞率，和菜品总的点赞率重复统计',
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
		$criteria->compare('price',$this->price,true);
		$criteria->compare('order_number',$this->order_number);
		$criteria->compare('favourite_number',$this->favourite_number);
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
	 * @return ProductTempprice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
