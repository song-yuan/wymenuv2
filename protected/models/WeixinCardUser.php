<?php

/**
 * This is the model class for table "nb_weixin_card_user".
 *
 * The followings are the available columns in table 'nb_weixin_card_user':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $from_user_name
 * @property string $friend_user_name
 * @property string $card_id
 * @property integer $is_giveby_friend
 * @property string $user_card_code
 * @property integer $outer_id
 * @property integer $status
 * @property integer $use_time
 * @property integer $delete_flag
 */
class WeixinCardUser extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WeixinCardUser the static model class
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
		return 'nb_weixin_card_user';
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
			array('is_giveby_friend, outer_id, status, use_time, delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('from_user_name, friend_user_name', 'length', 'max'=>255),
			array('card_id, user_card_code', 'length', 'max'=>64),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, from_user_name, friend_user_name, card_id, is_giveby_friend, user_card_code, outer_id, status, use_time, delete_flag', 'safe', 'on'=>'search'),
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
			'from_user_name' => 'From User Name',
			'friend_user_name' => 'Friend User Name',
			'card_id' => 'Card',
			'is_giveby_friend' => 'Is Giveby Friend',
			'user_card_code' => 'User Card Code',
			'outer_id' => 'Outer',
			'status' => 'Status',
			'use_time' => 'Use Time',
			'delete_flag' => 'Delete Flag',
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
		$criteria->compare('from_user_name',$this->from_user_name,true);
		$criteria->compare('friend_user_name',$this->friend_user_name,true);
		$criteria->compare('card_id',$this->card_id,true);
		$criteria->compare('is_giveby_friend',$this->is_giveby_friend);
		$criteria->compare('user_card_code',$this->user_card_code,true);
		$criteria->compare('outer_id',$this->outer_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('use_time',$this->use_time);
		$criteria->compare('delete_flag',$this->delete_flag);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}