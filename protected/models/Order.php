<?php

/**
 * This is the model class for table "nb_order".
 *
 * The followings are the available columns in table 'nb_order':
 * @property string $order_id
 * @property string $company_id
 * @property string $site_no_id
 * @property integer $order_status
 * @property string $create_time
 * @property string $pay_time
 * @property string $relitity_total
 * @property string remark
 * @property integer $number
 * @property integer $payment_method_id
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
			array('site_no_id', 'required'),
			array('order_status,number,payment_method_id', 'numerical', 'integerOnly'=>true),
			array('reality_total', 'numerical'),
			array('company_id, site_no_id, create_time, pay_time', 'length', 'max'=>10),
			array('remark','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('order_id, company_id, site_no_id, order_status, create_time, pay_time ,realtity_total,number,remark', 'safe', 'on'=>'search'),
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
				'orderProduct' => array(self::HAS_MANY , 'OrderProduct' , 'order_id'),
				'siteNo' => array(self::HAS_ONE , 'SiteNo' , '' , 'on'=>'t.site_no_id=siteNo.id'),
				'company' => array(self::BELONGS_TO , 'Company' , 'company_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'order_id' => 'Order',
			'company_id' => '公司',
			'site_no_id' => '订单编码',
			'order_status' => '订单状态',
			'create_time' => '下单时间',
			'pay_time' => '付款时间',
			'reality_total'=>'实际支付（元）',
			'number'=>'人数',
			'remark'=>'备注',
			'payment_method_id'=>'付款方式'
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

		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('site_no_id',$this->site_no_id,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('order_status',$this->order_status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('pay_time',$this->pay_time,true);

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
