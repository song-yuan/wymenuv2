<?php

/**
 * This is the model class for table "nb_goods_invoice_details".
 *
 * The followings are the available columns in table 'nb_goods_invoice_details':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property integer $goods_invoice_id
 * @property integer $goods_id
 * @property string $goods_code
 * @property integer $material_id
 * @property string $material_code
 * @property string $price
 * @property string $num
 * @property string $remark
 * @property string $delete_flag
 * @property string $is_sync
 */
class GoodsInvoiceDetails extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'nb_goods_invoice_details';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('update_at, goods_invoice_id, goods_id, goods_code, material_id, material_code, price, num, remark', 'required'),
            array('goods_invoice_id, goods_id, material_id', 'numerical', 'integerOnly'=>true),
            array('lid, dpid, price, num', 'length', 'max'=>10),
            array('goods_code, material_code', 'length', 'max'=>20),
            array('remark', 'length', 'max'=>30),
            array('delete_flag', 'length', 'max'=>2),
            array('is_sync', 'length', 'max'=>50),
            array('create_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('lid, dpid, create_at, update_at, goods_invoice_id, goods_id, goods_code, material_id, material_code, price, num, remark, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
            'create_at' => 'Create At',
            'update_at' => '更新时间',
            'goods_invoice_id' => '商品出货单id',
            'goods_id' => '商品id',
            'goods_code' => '商品编码',
            'material_id' => '原料id',
            'material_code' => '原料编码',
            'price' => '单价',
            'num' => '数量',
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
        $criteria->compare('create_at',$this->create_at,true);
        $criteria->compare('update_at',$this->update_at,true);
        $criteria->compare('goods_invoice_id',$this->goods_invoice_id);
        $criteria->compare('goods_id',$this->goods_id);
        $criteria->compare('goods_code',$this->goods_code,true);
        $criteria->compare('material_id',$this->material_id);
        $criteria->compare('material_code',$this->material_code,true);
        $criteria->compare('price',$this->price,true);
        $criteria->compare('num',$this->num,true);
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
     * @return GoodsInvoiceDetails the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}