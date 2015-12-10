<?php

/**
 * This is the model class for table "nb_weixin_card_shop".
 *
 * The followings are the available columns in table 'nb_weixin_card_shop':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $card_id
 * @property integer $wx_location_id
 * @property string $is_sync
 */
class WeixinCardShop extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WeixinCardShop the static model class
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
		return 'nb_weixin_card_shop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, wx_location_id', 'required'),
			array('wx_location_id', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('card_id', 'length', 'max'=>64),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, card_id, wx_location_id, is_sync', 'safe', 'on'=>'search'),
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
			'dpid' => 'Dpid',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'card_id' => 'Card',
			'wx_location_id' => 'Wx Location',
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
		$criteria->compare('card_id',$this->card_id,true);
		$criteria->compare('wx_location_id',$this->wx_location_id);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}