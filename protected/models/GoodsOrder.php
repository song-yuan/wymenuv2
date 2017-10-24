<?php

/**
 * This is the model class for table "nb_goods_order".
 *
 * The followings are the available columns in table 'nb_goods_order':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $account_no
 * @property string $goods_address_id
 * @property integer $user_id
 * @property string $username
 * @property string $order_status
 * @property string $order_type
 * @property string $should_total
 * @property string $reality_total
 * @property string $paytype
 * @property string $pay_status
 * @property string $pay_time
 * @property string $delete_flag
 * @property string $is_sync
 */
class GoodsOrder extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'nb_goods_order';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('update_at, account_no, goods_address_id, user_id, username, order_status, order_type, reality_total, paytype, pay_time', 'required'),
            array('user_id', 'numerical', 'integerOnly'=>true),
            array('lid, dpid, goods_address_id, should_total, reality_total', 'length', 'max'=>10),
            array('account_no, username, pay_time', 'length', 'max'=>30),
            array('order_status, order_type, paytype, pay_status, delete_flag', 'length', 'max'=>2),
            array('is_sync', 'length', 'max'=>50),
            array('create_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('lid, dpid, create_at, update_at, account_no, goods_address_id, user_id, username, order_status, order_type, should_total, reality_total, paytype, pay_status, pay_time, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
            'account_no' => '账单号',
            'goods_address_id' => '收货地址表id',
            'user_id' => '会员id',
            'username' => '登陆名',
            'order_status' => '订单状态 0 未支付，1已支付 ，3总部审核中，4已生成发货单，5已发货，6已确认收货。',
            'order_type' => '订单类型 0 自动生成 1手动生成',
            'should_total' => '应收',
            'reality_total' => '实收',
            'paytype' => '1,线上支付；2，货到付款',
            'pay_status' => '0未支付，1已支付。',
            'pay_time' => '付款时间',
            'delete_flag' => 'Delete Flag',
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
        $criteria->compare('account_no',$this->account_no,true);
        $criteria->compare('goods_address_id',$this->goods_address_id,true);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('username',$this->username,true);
        $criteria->compare('order_status',$this->order_status,true);
        $criteria->compare('order_type',$this->order_type,true);
        $criteria->compare('should_total',$this->should_total,true);
        $criteria->compare('reality_total',$this->reality_total,true);
        $criteria->compare('paytype',$this->paytype,true);
        $criteria->compare('pay_status',$this->pay_status,true);
        $criteria->compare('pay_time',$this->pay_time,true);
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
     * @return GoodsOrder the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}