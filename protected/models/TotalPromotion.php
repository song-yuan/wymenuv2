<?php

/**
 * This is the model class for table "nb_total_promotion".
 *
 * The followings are the available columns in table 'nb_total_promotion':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $is_narmal_promotion
 * @property string $is_private_promotion
 * @property string $is_cupon
 * @property string $is_cash
 * @property string $delete_flag
 */
class TotalPromotion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_total_promotion';
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
			array('lid, dpid', 'length', 'max'=>10),
			array('is_narmal_promotion, is_private_promotion, is_cupon, is_cash, delete_flag', 'length', 'max'=>2),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, is_narmal_promotion, is_private_promotion, is_cupon, is_cash, delete_flag', 'safe', 'on'=>'search'),
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
			'dpid' => '店铺ID',
			'create_at' => '创建时间',
			'update_at' => '最近一次更新时间',
			'is_narmal_promotion' => '0表示普通优惠有效，1表示无效',
			'is_private_promotion' => '0表示专享优惠有效，1表示无效',
			'is_cupon' => '0表示代金券有效，1表示无效',
			'is_cash' => '0表示返现和充值有效，1表示无效',
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
		$criteria->compare('is_narmal_promotion',$this->is_narmal_promotion,true);
		$criteria->compare('is_private_promotion',$this->is_private_promotion,true);
		$criteria->compare('is_cupon',$this->is_cupon,true);
		$criteria->compare('is_cash',$this->is_cash,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TotalPromotion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
