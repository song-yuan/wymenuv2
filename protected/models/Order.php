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
 * @property string $reality_total
 * @property string $should_total
 * @property string $callno 
 * @property string $paytype 
 * @property string $payment_method_id
 * @property string $pay_time
 * @property string $remark
 * @property string $taste_memo
 *  @property string $account_no
 *  @property string $classes
 */
class Order extends CActiveRecord
{
        public $should_all;
	public $all_reality;
	public $y_all;
	public $m_all;
	public $d_all;
	public $all_status;
        public $reality_all;
        public $all_total;
        public $all_money;
        public $pay_total=0;
        public $pay_discount_total=0;
        public $all_number;
        public $all_ordertype;
        public $all_num;
        public $all_nums;
        //支付部分传递参数
        public $account_cash=0;
        public $account_otherdetail="";
        public $account_membercard="";
        public $account_union=0;
        public $notpaydetail="";
        public $paycashaccountori="";
        public $paychangeaccount="";
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
			array('lid, dpid, site_id', 'required'),
			array('lid, dpid, payment_method_id, site_id,user_id, number', 'numerical', 'integerOnly'=>true),
			array('should_total,classes,reality_total,callno', 'length', 'max'=>10),
				array('account_no', 'length', 'max'=>20),
			array('is_temp, order_status, lock_status', 'length', 'max'=>1),
                        array('paytype,order_type', 'length', 'max'=>1),
			array('remark, username, taste_memo,is_sync', 'length', 'max'=>50),
			//array('create_at,pay_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, paytype, account_no, classes, update_at,username,payment_method_id, pay_time, site_id,user_id, is_temp, number, order_status,order_type,is_sync,lock_status, callno,should_total, reality_total, remark, taste_memo', 'safe', 'on'=>'search'),
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
		'company' => array(self::BELONGS_TO , 'Company' , 'dpid'),
                //'siteNo' => array(self::HAS_ONE , 'SiteNo' , '','on'=>' t.dpid=siteNo.dpid and t.site'),
                'paymentMethod' => array(self::BELONGS_TO , 'PaymentMethod' ,'' ,'on'=>'t.payment_method_id = paymentMethod.lid and t.dpid = paymentMethod.dpid '),
				'channel' => array(self::BELONGS_TO , 'Channel' ,'' ,'on'=>'t.takeout_typeid = channel.lid and t.dpid = channel.dpid '),
				'orderpay' => array(self::BELONGS_TO , 'OrderPay' ,'' ,'on'=>'t.dpid = orderpay.dpid and t.lid = orderpay.order_id '),
				'channel' => array(self::BELONGS_TO , 'Channel' ,'' ,'on'=>'t.dpid = channel.dpid and t.takeout_typeid = channel.lid '),
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
			'create_at' => yii::t('app','下单时间'),
			'update_at' => yii::t('app','更新时间'),
            'username' => yii::t('app','员工登录名'),
            'user_id' => yii::t('app','微信会员ID'),
			'account_no' => yii::t('app','账单号'),
			'classes' => yii::t('app','班次'),
			'site_id' => yii::t('app','餐桌'),
			'is_temp' => '0固定台 1临时台',
			'number' => '人数，和开台中的人数保持一致',
			'order_status' => '0未结算、1结单、2被并台、3被撤台、4被换台的标志',
			'lock_status' => '0未锁定，1锁定',
			'callno' => yii::t('app','呼叫器编号'),
            'paytype' => yii::t('app','支付方式'),
            'order_type' => yii::t('app','0pad1微信堂食2微信外卖'),
            'is_sync' => yii::t('app','是否同步'),
            'payment_method_id'=>yii::t('app','支付方式'),//后台手动支付方式
            'payment_time'=>yii::t('app','支付时间'),
			'reality_total' =>yii::t('app', '实付金额'),
			'should_total' =>yii::t('app', '应付金额'),
			'remark' => yii::t('app','支付说明'),
			'taste_memo' =>yii::t('app', '口味描述'),
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
                $criteria->compare('user_id',$this->user_id);
                $criteria->compare('username',$this->username);
                $criteria->compare('account_no',$this->account_no,true);
                $criteria->compare('classes',$this->classes,true);
		$criteria->compare('is_temp',$this->is_temp,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('order_status',$this->order_status,true);
                $criteria->compare('order_type',$this->order_type,true);
                $criteria->compare('is_sync',$this->is_sync,true);
		$criteria->compare('lock_status',$this->lock_status,true);
		$criteria->compare('should_total',$this->should_total,true);
		$criteria->compare('reality_total',$this->reality_total,true);
                $criteria->compare('pay_time',$this->pay_time,true);
                $criteria->compare('payment_method_id',$this->payment_method_id,true);
		$criteria->compare('callno',$this->callno,true);
                $criteria->compare('paytype',$this->paytype,true);
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
        
        static public function getOrderList($dpid,$site_id,$is_temp){
            $orderlist="0000000000";
//            if(!empty($siteNo))
//            {
		$sqlorderlist="select lid from nb_order where order_status in ('1','2','3') and dpid=".$dpid." and is_temp=".$is_temp." and site_id=".$site_id;
                $orderlistmodel=Yii::app()->db->createCommand($sqlorderlist)->queryAll();
                
                if(!empty($orderlistmodel))
                {
                    foreach ($orderlistmodel as $ol)
                    {
                        $orderlist.=",".$ol["lid"];
                    }
                }
//            }
            return $orderlist;
	}
        
        /**
         * 
         * @param type $dpid
         * @param type $site_id
         * @param type $is_temp
         * @param type $orderid 万一没有取到，说明还没有其他订单，可以根据这个orderid来生成
         */
        static public function getAccountNo($dpid,$site_id,$is_temp,$orderid)
        {
            $sql="select ifnull(min(account_no),'000000000000') as account_no from nb_order where dpid="
                    .$dpid." and site_id=".$site_id." and is_temp=".$is_temp
                    ." and order_status in ('1','2','3')";
            $ret=Yii::app()->db->createCommand($sql)->queryScalar();      
            if(empty($ret) || $ret=="0000000000")
            {
                $ret=substr(date('Ymd',time()),-6).substr("000".$dpid, -3).substr("0000000000".$orderid, -6);
            }
            return $ret;
        }
}
