<?php

/**
 * This is the model class for table "nb_normal_promotion_detail".
 *
 * The followings are the available columns in table 'nb_normal_promotion_detail':
 * @property integer $lid
 * @property integer $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $normal_promotion_id
 * @property string $product_id
 * @property integer $is_set
 * @property integer $is_discount
 * @property string $promotion_money
 * @property string $promotion_discount
 * @property integer $order_num
 * @property string $delete_flag
 */
class NormalPromotionDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_normal_promotion_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, is_set, is_discount', 'required'),
			array('lid, dpid, is_set, is_discount, order_num', 'numerical', 'integerOnly'=>true),
			array('normal_promotion_id, product_id, promotion_money', 'length', 'max'=>10),
			array('promotion_discount', 'length', 'max'=>6),
			array('delete_flag', 'length', 'max'=>2),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, normal_promotion_id, product_id, is_set, is_discount, promotion_money, promotion_discount, order_num, delete_flag', 'safe', 'on'=>'search'),
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
			'lid' => 'Lid',
			'dpid' => 'Dpid',
			'create_at' => '创建时的时间',
			'update_at' => '最近一次更新时间',
			'normal_promotion_id' => '普通活动的id',
			'product_id' => '单品或者套餐的id',
			'is_set' => '是否优惠，0单品，1套餐',
			'is_discount' => '0折扣，1优惠',
			'promotion_money' => '优惠金额',
			'promotion_discount' => '优惠的折扣',
			'order_num' => '默认1 ，单个订单的数量限制',
			'delete_flag' => '0表示存在，1表示删除',
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

		$criteria->compare('lid',$this->lid);
		$criteria->compare('dpid',$this->dpid);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('normal_promotion_id',$this->normal_promotion_id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('is_set',$this->is_set);
		$criteria->compare('is_discount',$this->is_discount);
		$criteria->compare('promotion_money',$this->promotion_money,true);
		$criteria->compare('promotion_discount',$this->promotion_discount,true);
		$criteria->compare('order_num',$this->order_num);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NormalPromotionDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
