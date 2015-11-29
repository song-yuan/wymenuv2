<?php

/**
 * This is the model class for table "nb_private_branduser".
 *
 * The followings are the available columns in table 'nb_private_branduser':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $private_promotion_id
 * @property string $brand_user_lid
 * @property integer $cupon_source
 * @property integer $source_id
 * @property string $is_used
 * @property string $get_time
 * @property string $ursed_time
 * @property string $delete_flag
 * * @property string $is_sync
 */
class PrivateBranduser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_private_branduser';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, cupon_source, is_used', 'required'),
			array('cupon_source, source_id', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, private_promotion_id, brand_user_lid', 'length', 'max'=>10),
			array('is_used, delete_flag', 'length', 'max'=>2),
				array('is_sync','length','max'=>50),
			array('create_at, get_time, ursed_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, is_sync, private_promotion_id, brand_user_lid, cupon_source, source_id, is_used, get_time, ursed_time, delete_flag', 'safe', 'on'=>'search'),
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
			'private_promotion_id' => '专享活动id',
			'brand_user_lid' => '会员id',
			'cupon_source' => '优惠券来源。0活动，1红包领取',
			'source_id' => '活动或者红包id',
			'is_used' => '0表示用户打开阅读，1表示领用，2表示使用',
			'get_time' => '如果用户领用，则记录领用时间',
			'ursed_time' => '使用时间',
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
		$criteria->compare('private_promotion_id',$this->private_promotion_id,true);
		$criteria->compare('brand_user_lid',$this->brand_user_lid,true);
		$criteria->compare('cupon_source',$this->cupon_source);
		$criteria->compare('source_id',$this->source_id);
		$criteria->compare('is_used',$this->is_used,true);
		$criteria->compare('get_time',$this->get_time,true);
		$criteria->compare('ursed_time',$this->ursed_time,true);
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
	 * @return PrivateBranduser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
