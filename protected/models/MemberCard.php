<?php

/**
 * This is the model class for table "nb_member_card".
 *
 * The followings are the available columns in table 'nb_member_card':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $selfcode
 * @property string $rfid
 * @property string $level_id
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property string $haspassword
 * @property string $password_hash
 * @property string $sex
 * @property string $ages
 * @property string $all_money
 * @property string $delete_flag
 *  @property string $is_sync
 */
class MemberCard extends CActiveRecord
{
	public $y_all;
	public $m_all;
	public $d_all;
	public $all_num;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MemberCard the static model class
	 */
	public $password_hash1;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_member_card';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, all_money,level_id', 'length', 'max'=>10),
			array('name, mobile, ages,selfcode', 'length', 'max'=>20),
			array('rfid', 'length', 'max'=>11),
			array('email', 'length', 'max'=>100),
			array('haspassword, sex, delete_flag', 'length', 'max'=>1),
			array('password_hash', 'length', 'max'=>60),
			array('create_at ,enable_date', 'safe'),
			array('is_sync','length','max'=>50),
			array('birthday','length','max'=>16),
			array('level_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','必须选择会员等级')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, is_sync, selfcode, rfid, level_id, name, mobile, birthday, email, haspassword, password_hash, sex, ages, all_money, all_points, card_status, enable_date,delete_flag', 'safe', 'on'=>'search'),
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
                'brandUserLevel' => array(self::BELONGS_TO , 'BrandUserLevel' ,'','on'=> 't.level_id=brandUserLevel.lid and brandUserLevel.dpid=t.dpid and brandUserLevel.delete_flag<1 and brandUserLevel.level_type=0'),
                'point' => array(self::HAS_MANY , 'MemberPoints' ,'','on'=> 't.rfid=point.card_id and point.dpid=t.dpid and point.delete_flag<1 and point.card_type=0'),   
		'recharge' => array(self::HAS_MANY , 'MemberRecharge' ,'','on'=> 't.rfid=recharge.member_card_id and recharge.dpid=t.dpid and recharge.delete_flag<1'),   
				
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
			'selfcode' => '会员卡号',
			'rfid' => '读取卡号或手机号',
			'level_id' => '会员等级',
			'name' => '姓名',
			'mobile' => '联系方式',
			'birthday' => '会员生日',
			'email' => '邮箱',
			'haspassword' => 'Haspassword',
			'password_hash' => '支付密码',
			'password_hash1' => '确认支付密码',
			'sex' => '性别',
			'ages' => '年龄',
			'all_money' => '总金额',
				'all_points' => '总积分',
				'card_status' => '卡状态',
				'enable_date' => '有效期',
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
		$criteria->compare('selfcode',$this->selfcode,true);
		$criteria->compare('rfid',$this->rfid,true);
		$criteria->compare('level_id',$this->level_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('haspassword',$this->haspassword,true);
		$criteria->compare('password_hash',$this->password_hash,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('ages',$this->ages,true);
		$criteria->compare('all_money',$this->all_money,true);
		$criteria->compare('all_points',$this->all_points,true);
		$criteria->compare('card_status',$this->card_status,true);
		$criteria->compare('enable_date',$this->enable_date,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}