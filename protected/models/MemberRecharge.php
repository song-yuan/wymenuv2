<?php

/**
 * This is the model class for table "nb_member_recharge".
 *
 * The followings are the available columns in table 'nb_member_recharge':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $member_card_id
 * @property string $reality_money
 * @property string $give_money
 * @property string $delete_flag
 * * @property string $is_sync
 */
class MemberRecharge extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MemberRecharge the static model class
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
		return 'nb_member_recharge';
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
			array('lid, dpid, reality_money, give_money', 'length', 'max'=>10),
			array('delete_flag', 'length', 'max'=>1),
			array('create_at', 'safe'),
			array('member_card_id','length','max'=>11),
			array('is_sync','length','max'=>50),
			array('lid, dpid, create_at, is_sync, update_at, member_card_id, reality_money, give_money, delete_flag', 'safe', 'on'=>'search'),
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
				'MemberCard' => array(self::BELONGS_TO , 'MemberCard' ,'','on'=> 't.member_card_id=MemberCard.rfid and MemberCard.dpid=t.dpid'),
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
			'member_card_id' => '会员卡号',
			'reality_money' => '充值金额',
			'give_money' => '赠送金额',
			'delete_flag' => 'Delete Flag',
				'is_sync' => yii::t('app','是否同步'),
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
		$criteria->compare('member_card_id',$this->member_card_id,true);
		$criteria->compare('reality_money',$this->reality_money,true);
		$criteria->compare('give_money',$this->give_money,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}