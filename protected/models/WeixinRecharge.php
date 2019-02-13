<?php

/**
 * This is the model class for table "nb_weixin_recharge".
 *
 * The followings are the available columns in table 'nb_weixin_recharge':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $wr_name
 * @property string $recharge_money
 * @property string $recharge_pointback
 * @property string $recharge_cashback
 * @property string $is_available
 * @property string $delete_flag
 *  @property string $is_sync
 */
class WeixinRecharge extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_weixin_recharge';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, wr_name, recharge_number, recharge_dpid', 'required'),
			array('lid, dpid, recharge_money, recharge_cashback', 'length', 'max'=>10),
			array('wr_name, is_sync', 'length', 'max'=>50),
			array('recharge_pointback', 'length', 'max'=>10),
			array('is_available, delete_flag', 'length', 'max'=>2),
            array('recharge_pointback','compare','compareValue'=>'999999999','operator'=>'<','message'=>yii::t('app','返积分金额太大')),
			array('recharge_cashback','compare','compareValue'=>'99999999','operator'=>'<','message'=>yii::t('app','返现金额太大')),
			array('recharge_money','compare','compareValue'=>'99999999','operator'=>'<','message'=>yii::t('app','充值金额太大')),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, wr_name, recharge_money, recharge_pointback, recharge_cashback, recharge_cashcard, recharge_number, recharge_dpid, is_available, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'lid' => 'Lid',
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '最近一次更新时间',
			'wr_name' => '名称',
			'recharge_money' => '充值的金额',
			'recharge_pointback' => '返积分',
			'recharge_cashback' => '返现',
			'recharge_cashcard' => '返现金券',
			'recharge_number' => '充值次数',
			'recharge_dpid' => '参与店铺',
			'is_available' => '是否有效',//0表示有效，1表示无效
			'delete_flag' => '0表示存在，1表示删除',
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
		$criteria->compare('wr_name',$this->wr_name,true);
		$criteria->compare('recharge_money',$this->recharge_money,true);
		$criteria->compare('recharge_pointback',$this->recharge_pointback,true);
		$criteria->compare('recharge_cashback',$this->recharge_cashback,true);
		$criteria->compare('recharge_number',$this->recharge_cashcard,true);
		$criteria->compare('recharge_number',$this->recharge_number,true);
		$criteria->compare('recharge_dpid',$this->recharge_dpid,true);
		$criteria->compare('is_available',$this->is_available,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WeixinRecharge the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
