<?php

/**
 * This is the model class for table "nb_micro_pay".
 *
 * The followings are the available columns in table 'nb_micro_pay':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $pay_type
 * @property string $out_trade_no
 * @property string $transaction_id
 * @property string $total_fee
 * @property string $pay_result
 * @property string $delete_flag
 * @property string $is_sync
 */
class MicroPay extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_micro_pay';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, out_trade_no', 'required'),
			array('lid, dpid', 'length', 'max'=>10),
			array('pay_type', 'length', 'max'=>2),
			array('out_trade_no, total_fee', 'length', 'max'=>32),
			array('transaction_id', 'length', 'max'=>64),
			array('delete_flag', 'length', 'max'=>1),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, pay_result', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, pay_type, out_trade_no, transaction_id, total_fee, pay_result, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'lid' => '自身id，同一dpid下递增',
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'pay_type' => '支付类型 0 微信 1 支付宝',
			'out_trade_no' => '系统订单号',
			'transaction_id' => 'Transaction',
			'total_fee' => '支付金额',
			'pay_result' => '支付结果',
			'delete_flag' => 'Delete Flag',
			'is_sync' => 'Is Sync',
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
		$criteria->compare('pay_type',$this->pay_type,true);
		$criteria->compare('out_trade_no',$this->out_trade_no,true);
		$criteria->compare('transaction_id',$this->transaction_id,true);
		$criteria->compare('total_fee',$this->total_fee,true);
		$criteria->compare('pay_result',$this->pay_result,true);
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
	 * @return MicroPay the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
