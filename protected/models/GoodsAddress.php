<?php

/**
 * This is the model class for table "nb_goods_address".
 *
 * The followings are the available columns in table 'nb_goods_address':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property integer $user_id
 * @property string $name
 * @property string $pcc
 * @property string $street
 * @property string $mobile
 * @property integer $default_address
 * @property integer $delete_flag
 * @property string $is_sync
 */
class GoodsAddress extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'nb_goods_address';
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
            array('user_id, default_address, delete_flag', 'numerical', 'integerOnly'=>true),
            array('lid, dpid', 'length', 'max'=>10),
            array('name', 'length', 'max'=>30),
            array('pcc, mobile', 'length', 'max'=>20),
            array('street', 'length', 'max'=>255),
            array('is_sync', 'length', 'max'=>50),
            array('create_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('lid, dpid, create_at, update_at, user_id, name, pcc, street, mobile, default_address, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
            'user_id' => '会员lid',
            'name' => '收货人姓名',
            'pcc' => '省市县',
            'street' => '街道详细地址',
            'mobile' => '手机号',
            'default_address' => '设置为默认收货地址',
            'delete_flag' => '逻辑删除 0未删除 1删除',
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
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('pcc',$this->pcc,true);
        $criteria->compare('street',$this->street,true);
        $criteria->compare('mobile',$this->mobile,true);
        $criteria->compare('default_address',$this->default_address);
        $criteria->compare('delete_flag',$this->delete_flag);
        $criteria->compare('is_sync',$this->is_sync,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GoodsAddress the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}