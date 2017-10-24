<?php

/**
 * This is the model class for table "nb_goods_invoice".
 *
 * The followings are the available columns in table 'nb_goods_invoice':
 * @property string $lid
 * @property string $dpid
 * @property string $compid
 * @property string $create_at
 * @property string $update_at
 * @property integer $goods_delivery_id
 * @property integer $goods_order_id
 * @property string $goods_address_id
 * @property string $goods_order_accountno
 * @property string $invoice_accountno
 * @property string $auditor
 * @property string $operators
 * @property string $sent_type
 * @property string $sent_personnel
 * @property string $mobile
 * @property string $status
 * @property string $invoice_amount
 * @property string $pay_status
 * @property string $remark
 * @property string $delete_flag
 * @property string $is_sync
 */
class GoodsInvoice extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'nb_goods_invoice';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('update_at, goods_delivery_id, goods_order_id, goods_address_id, goods_order_accountno, invoice_accountno, invoice_amount, pay_status, remark', 'required'),
            array('goods_delivery_id, goods_order_id', 'numerical', 'integerOnly'=>true),
            array('lid, dpid, compid, goods_address_id, auditor, operators, sent_personnel, invoice_amount', 'length', 'max'=>10),
            array('goods_order_accountno, invoice_accountno, remark', 'length', 'max'=>30),
            array('sent_type, status, pay_status, delete_flag', 'length', 'max'=>2),
            array('mobile', 'length', 'max'=>15),
            array('is_sync', 'length', 'max'=>50),
            array('create_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('lid, dpid, compid, create_at, update_at, goods_delivery_id, goods_order_id, goods_address_id, goods_order_accountno, invoice_accountno, auditor, operators, sent_type, sent_personnel, mobile, status, invoice_amount, pay_status, remark, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
            'dpid' => '仓库id',
            'compid' => '公司id',
            'create_at' => 'Create At',
            'update_at' => '更新时间',
            'goods_delivery_id' => '出货单id',
            'goods_order_id' => '商品订单id',
            'goods_address_id' => '收货地址id',
            'goods_order_accountno' => '商品订单账单号',
            'invoice_accountno' => '出货单号',
            'auditor' => '审核人、仓管',
            'operators' => '经办人、负责人',
            'sent_type' => '1总部配送，2仓库配送3第三方物流',
            'sent_personnel' => '配送员或者第三方物流名称',
            'mobile' => '联系方式或者三方物流单号',
            'status' => '出货单状态,0默认初始,1运输中,2签收',
            'invoice_amount' => '出货单总额',
            'pay_status' => '支付状态',
            'remark' => '备注',
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
        $criteria->compare('compid',$this->compid,true);
        $criteria->compare('create_at',$this->create_at,true);
        $criteria->compare('update_at',$this->update_at,true);
        $criteria->compare('goods_delivery_id',$this->goods_delivery_id);
        $criteria->compare('goods_order_id',$this->goods_order_id);
        $criteria->compare('goods_address_id',$this->goods_address_id,true);
        $criteria->compare('goods_order_accountno',$this->goods_order_accountno,true);
        $criteria->compare('invoice_accountno',$this->invoice_accountno,true);
        $criteria->compare('auditor',$this->auditor,true);
        $criteria->compare('operators',$this->operators,true);
        $criteria->compare('sent_type',$this->sent_type,true);
        $criteria->compare('sent_personnel',$this->sent_personnel,true);
        $criteria->compare('mobile',$this->mobile,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('invoice_amount',$this->invoice_amount,true);
        $criteria->compare('pay_status',$this->pay_status,true);
        $criteria->compare('remark',$this->remark,true);
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
     * @return GoodsInvoice the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}