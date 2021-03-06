<?php

/**
 * This is the model class for table "nb_cupon_branduser".
 *
 * The followings are the available columns in table 'nb_cupon_branduser':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $cupon_id
 * @property string $cupon_source
 * @property string $source_id
 * @property string $brand_user_id
 * @property string $is_used
 * @property string $used_time
 * @property string $delete_flag
 * @property string $is_sync
 */
class CuponBranduser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_cupon_branduser';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, is_used', 'required'),
			array('lid, dpid, cupon_id, source_id, brand_user_id', 'length', 'max'=>10),
			array('cupon_source, to_group, is_used, delete_flag', 'length', 'max'=>2),
			array('create_at, used_time', 'safe'),
				array('is_sync','length','max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, to_group, cupon_id, is_sync, cupon_source, source_id, brand_user_id, is_used, used_time, delete_flag', 'safe', 'on'=>'search'),
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
			'cupon_id' => '代金券id',
			'cupon_source' => '优惠券来源；0活动，1红包领取',
			'source_id' => '活动或者红包id',
				'to_group' => '0表示所有人，1表示关注微信，2表示会员等级，3表示会员个人',
			'brand_user_id' => '会员id',
			'is_used' => '是否已经使用；0表示尚未使用，1表示已经使用',
			'used_time' => '使用时间',
			'delete_flag' => '0表示存在，1表示删除',
				'is_sync' => yii::t('app','是否同步'),
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
		$criteria->compare('cupon_id',$this->cupon_id,true);
		$criteria->compare('cupon_source',$this->cupon_source,true);
		$criteria->compare('source_id',$this->source_id,true);
		$criteria->compare('to_group',$this->to_group,true);
		$criteria->compare('brand_user_id',$this->brand_user_id,true);
		$criteria->compare('is_used',$this->is_used,true);
		$criteria->compare('used_time',$this->used_time,true);
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
	 * @return CuponBranduser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
