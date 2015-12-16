<?php

/**
 * This is the model class for table "nb_order_product_promotion".
 *
 * The followings are the available columns in table 'nb_order_product_promotion':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $order_id
 * @property string $order_product_id
 * @property string $account_no
 * @property string $promotion_type
 * @property string $promotion_id
 * @property string $promotion_money
 * @property string $delete_flag
 * @property string $is_sync
 */
class OrderProductPromotion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_order_product_promotion';
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
			array('lid, dpid, order_id, order_product_id, promotion_id, promotion_money', 'length', 'max'=>10),
			array('account_no', 'length', 'max'=>20),
			array('promotion_type, delete_flag', 'length', 'max'=>2),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, update_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, order_id, order_product_id, account_no, promotion_type, promotion_id, promotion_money, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'dpid' => '店铺id',
			'create_at' => '添加时间',
			'update_at' => '最近一次更新时间',
			'order_id' => '订单id',
			'order_product_id' => '订单内产品id',
			'account_no' => '账单号',
			'promotion_type' => '产生优惠的类型0普通1特价2后台整单',
			'promotion_id' => '普通活动、特价活动、后台整单活动的id',
			'promotion_money' => '优惠的金额',
			'delete_flag' => '0表示存在，1表示删除',
			'is_sync' => '同步标志',
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
		$criteria->compare('order_product_id',$this->order_product_id,true);
		$criteria->compare('account_no',$this->account_no,true);
		$criteria->compare('promotion_type',$this->promotion_type,true);
		$criteria->compare('promotion_id',$this->promotion_id,true);
		$criteria->compare('promotion_money',$this->promotion_money,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderProductPromotion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
