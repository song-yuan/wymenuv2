<?php

/**
 * This is the model class for table "nb_order".
 *
 * The followings are the available columns in table 'nb_order':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $site_id
 * @property string $is_temp
 * @property integer $number
 * @property string $order_status
 * @property string $lock_status
 * @property string $pay_time
 * @property string $reality_total
 * @property string $payment_method_id
 * @property string $remark
 * @property string $taste_memo
 */
class Order extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, site_id, update_at, remark, taste_memo', 'required'),
			array('lid, dpid, site_id, number', 'numerical', 'integerOnly'=>true),
			array('reality_total', 'length', 'max'=>10),
			array('is_temp, order_status, lock_status', 'length', 'max'=>1),
			array('remark, taste_memo', 'length', 'max'=>50),
			array('create_at, pay_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, site_id, is_temp, number, order_status, lock_status, pay_time, reality_total, payment_method_id, remark, taste_memo', 'safe', 'on'=>'search'),
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
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'site_id' => '台子的编号，前台session存储site_no_id,根据site_no_id在site_no表中找到site_id和is_temp,然后找到对应订单。换台时被换对应该表中site_id和is_temp要更新；并台时被并的人数要加到并到的台；撤台改变状态就行。',
			'is_temp' => '0固定台 1临时台',
			'number' => '人数，和开台中的人数保持一致',
			'order_status' => '0未结算、1结单、2被并台、3被撤台、4被换台的标志',
			'lock_status' => '0未锁定，1锁定',
			'pay_time' => 'Pay Time',
			'reality_total' => 'Reality Total',
			'payment_method_id' => 'Payment Method',
			'remark' => 'Remark',
			'taste_memo' => 'Taste Memo',
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
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('is_temp',$this->is_temp,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('order_status',$this->order_status,true);
		$criteria->compare('lock_status',$this->lock_status,true);
		$criteria->compare('pay_time',$this->pay_time,true);
		$criteria->compare('reality_total',$this->reality_total,true);
		$criteria->compare('payment_method_id',$this->payment_method_id,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('taste_memo',$this->taste_memo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
