<?php

/**
 * This is the model class for table "nb_branduser_gift".
 *
 * The followings are the available columns in table 'nb_branduser_gift':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $branduser_lid
 * @property string $gift_lid
 * @property string $code
 * @property integer $is_used
 * @property string $used_at
 * @property integer $delete_flag
 * @property string $is_sync
 */
class BranduserGift extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BranduserGift the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_branduser_gift';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, code', 'required'),
			array('is_used, delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, branduser_lid, gift_lid', 'length', 'max'=>10),
			array('code', 'length', 'max'=>12),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, used_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, branduser_lid, gift_lid, code, is_used, used_at, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'gift'=>array(self::HAS_ONE , 'Gift' ,'','on'=> 't.gift_lid=gift.lid and gift.dpid=t.dpid'),
			'branduser'=>array(self::HAS_ONE , 'BrandUser' ,'','on'=> 't.branduser_lid=branduser.lid and branduser.dpid=t.dpid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => 'Lid',
			'dpid' => 'Dpid',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'branduser_lid' => 'Branduser Lid',
			'gift_lid' => 'Gift Lid',
			'code' => 'Code',
			'is_used' => 'Is Used',
			'used_at' => 'Used At',
			'delete_flag' => 'Delete Flag',
			'is_sync' => 'Is Sync',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('branduser_lid',$this->branduser_lid,true);
		$criteria->compare('gift_lid',$this->gift_lid,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('is_used',$this->is_used);
		$criteria->compare('used_at',$this->used_at,true);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}