<?php

/**
 * This is the model class for table "nb_order_pay".
 *
 * The followings are the available columns in table 'nb_order_pay':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $order_id
 * @property string $account_no
 * @property string $pay_amount
 * @property string $paytype 
 * @property string $payment_method_id
 * @property string $remark
 *  @property string $is_sync
 */
class OrderPay extends CActiveRecord
{
    public $should_all;
    public $all_reality;
	public $y_all;
	public $m_all;
	public $d_all;
	public $all_status;
    public $reality_all;
    public $all_total;
    public $all_huiyuan;
    public $all_num;
    public $all_nums;
    public $transactionId;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_order_pay';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid', 'required'),
			array('lid, dpid, order_id, pay_amount, payment_method_id', 'length', 'max'=>10),
                        array('paytype', 'length', 'max'=>1),
			array('remark, is_sync', 'length', 'max'=>50),
				array('account_no', 'length', 'max'=>20),
			array('create_at', 'safe'),
			//array('pay_amount','compare','compareValue'=>'0','operator'=>'<','message'=>yii::t('app','退款金额必须大于零啊')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, account_no, update_at, is_sync, order_id, pay_amount, payment_method_id, remark', 'safe', 'on'=>'search'),
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
                'company' => array(self::BELONGS_TO , 'Company' ,'' ,'on'=>'t.dpid=company.dpid') ,
                'order8' => array(self::BELONGS_TO , 'Order' ,'' ,'on'=>'t.dpid=order8.dpid and t.order_id=order8.lid and order8.order_status in (8)') , //not 4,8
                'order' => array(self::BELONGS_TO , 'Order' ,'' ,'on'=>'t.dpid=order.dpid and t.order_id=order.lid and order.order_status in (4)') , //not 4,8
                'order4' => array(self::BELONGS_TO , 'Order' ,'' ,'on'=>'t.dpid=order4.dpid and t.order_id=order4.lid and order4.order_status in (3,4,8)') , //not 4,8
                'paymentMethod' => array(self::BELONGS_TO , 'PaymentMethod' ,'' ,'on'=>'t.payment_method_id = paymentMethod.lid and t.dpid = paymentMethod.dpid '),
                'micropay' => array(self::BELONGS_TO , 'MicroPayMode' ,'' ,'on'=>'t.remark = micropay.out_trade_no and t.dpid = micropay.dpid '),
                'card' => array(self:: BELONGS_TO, 'MemberCard' ,'' ,'on'=>'t.paytype_id = card.rfid  and t.dpid=card.dpid') ,
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
			'update_at' => yii::t('app','更新时间'),
			'order_id' =>yii::t('app', '订单编号'),
			'account_no' =>yii::t('app', '账单号'),
			'pay_amount' => yii::t('app','金额'),
            'paytype' => yii::t('app','支付方式'),
			'payment_method_id' =>yii::t('app', '方式'),
			'remark' => yii::t('app','备注'),
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
		$criteria->compare('account_no',$this->account_no,true);
		$criteria->compare('pay_amount',$this->pay_amount,true);
        $criteria->compare('paytype',$this->paytype,true);
		$criteria->compare('payment_method_id',$this->payment_method_id,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderPay the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
