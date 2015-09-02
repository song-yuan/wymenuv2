<?php

/**
 * This is the model class for table "nb_close_account_detail".
 *
 * The followings are the available columns in table 'nb_close_account_detail':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $close_account_id
 * @property string $paytype
 * @property string $payment_method_id
 * @property string $all_money
 */
class CloseAccountDetail extends CActiveRecord
{
	public $should_all;
    public $d_all;
    public $m_all;
    public $y_all;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_close_account_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at', 'required'),
			array('lid, dpid, close_account_id, payment_method_id, all_money', 'length', 'max'=>10),
			array('paytype', 'length', 'max'=>1),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, close_account_id, paytype, payment_method_id, all_money', 'safe', 'on'=>'search'),
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
				'company' => array(self::BELONGS_TO , 'Company' ,'' ,'on'=>'t.dpid = company.dpid') ,
				'paymentMethod' => array(self::BELONGS_TO , 'PaymentMethod' ,'' ,'on'=>'t.payment_method_id = paymentMethod.lid and t.dpid = paymentMethod.dpid ') ,
			    'closeAccount'=>array(self::BELONGS_TO , 'CloseAccount' ,'' ,'on'=>'t.dpid = closeAccount.dpid and t.close_account_id = closeAccount.lid'),
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
			'close_account_id' => 'Close Account',
			'paytype' => '0现金支付1微信2支付宝3后台手动支付',
			'payment_method_id' => 'Payment Method',
			'all_money' => 'All Money',
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
		$criteria->compare('close_account_id',$this->close_account_id,true);
		$criteria->compare('paytype',$this->paytype,true);
		$criteria->compare('payment_method_id',$this->payment_method_id,true);
		$criteria->compare('all_money',$this->all_money,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CloseAccountDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
