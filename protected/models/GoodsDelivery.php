<?php

/**
 * This is the model class for table "nb_goods_delivery".
 *
 * The followings are the available columns in table 'nb_goods_delivery':
 * @property string $lid
 * @property string $dpid
 * @property string $compid
 * @property string $create_at
 * @property string $update_at
 * @property integer $goods_order_id
 * @property string $goods_address_id
 * @property string $goods_order_accountno
 * @property string $delivery_accountno
 * @property string $auditor
 * @property string $operators
 * @property string $status
 * @property string $delivery_amount
 * @property string $pay_status
 * @property string $remark
 * @property string $delete_flag
 * @property string $is_sync
 */
class GoodsDelivery extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'nb_goods_delivery';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('update_at, goods_order_id, goods_address_id, goods_order_accountno, delivery_accountno, delivery_amount, pay_status, remark', 'required'),
            array('goods_order_id', 'numerical', 'integerOnly'=>true),
            array('lid, dpid, compid, goods_address_id, auditor, operators, delivery_amount', 'length', 'max'=>10),
            array('goods_order_accountno, delivery_accountno, remark', 'length', 'max'=>30),
            array('status, pay_status, delete_flag', 'length', 'max'=>2),
            array('is_sync', 'length', 'max'=>50),
            array('create_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('lid, dpid, compid, create_at, update_at, goods_order_id, goods_address_id, goods_order_accountno, delivery_accountno, auditor, operators, status, delivery_amount, pay_status, remark, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
            'goods_order_id' => '商品订单id',
            'goods_address_id' => '商品收货地址',
            'goods_order_accountno' => '商品订单账单号',
            'delivery_accountno' => '出货单号',
            'auditor' => '审核人、仓管',
            'operators' => '经办人、负责人',
            'status' => '出货单状态',
            'delivery_amount' => '出货单总额',
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
        $criteria->compare('goods_order_id',$this->goods_order_id);
        $criteria->compare('goods_address_id',$this->goods_address_id,true);
        $criteria->compare('goods_order_accountno',$this->goods_order_accountno,true);
        $criteria->compare('delivery_accountno',$this->delivery_accountno,true);
        $criteria->compare('auditor',$this->auditor,true);
        $criteria->compare('operators',$this->operators,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('delivery_amount',$this->delivery_amount,true);
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
     * @return GoodsDelivery the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}