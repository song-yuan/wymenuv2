<?php

/**
 * This is the model class for table "nb_brand_user".
 *
 * The followings are the available columns in table 'nb_brand_user':
 * @property string $lid
 * @property integer $dpid
 * @property string $user_name
 * @property string $password
 * @property string $nickname
 * @property string $head_icon
 * @property string $mobile_num
 * @property string $sex
 * @property string $card_id
 * @property string $user_level_lid
 * @property string $user_birthday
 * @property string $openid
 * @property string $country
 * @property string $province
 * @property string $city
 * @property integer $unsubscribe
 * @property integer $unsubscribe_time
 * @property string $create_at
 * @property string $update_at
 * @property integer $consume_point_history
 * @property string $consume_point
 * @property integer $scene_type
 * @property integer $weixin_group
 */
class BrandUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_brand_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dpid, user_name, card_id, update_at, scene_type', 'required'),
			array('dpid, unsubscribe, unsubscribe_time, consume_point_history, scene_type, weixin_group', 'numerical', 'integerOnly'=>true),
			array('lid, user_level_lid, consume_point', 'length', 'max'=>10),
			array('user_name, mobile_num', 'length', 'max'=>45),
			array('password', 'length', 'max'=>50),
			array('nickname', 'length', 'max'=>30),
			array('head_icon, openid', 'length', 'max'=>255),
			array('sex', 'length', 'max'=>1),
			array('card_id', 'length', 'max'=>14),
			array('country, province, city', 'length', 'max'=>20),
			array('user_birthday, create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, user_name, password, nickname, head_icon, mobile_num, sex, card_id, user_level_lid, user_birthday, openid, country, province, city, unsubscribe, unsubscribe_time, create_at, update_at, consume_point_history, consume_point, scene_type, weixin_group', 'safe', 'on'=>'search'),
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
			'dpid' => '品牌主键',
			'user_name' => '会员名称',
			'password' => '会员密码',
			'nickname' => '微信昵称',
			'head_icon' => '会员头像',
			'mobile_num' => '手机号',
			'sex' => '0未知,1男，1女',
			'card_id' => '会员卡号',
			'user_level_lid' => '对应（brand_user_level）会员等级lid',
			'user_birthday' => '会员生日',
			'openid' => 'openid',
			'country' => 'Country',
			'province' => 'Province',
			'city' => 'City',
			'unsubscribe' => '0未取消关注 1取消关注',
			'unsubscribe_time' => '取消关注时间',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'consume_point_history' => '消费历史积分总数',
			'consume_point' => '当前持有的消费积分',
			'scene_type' => '0 自动关注,1扫码关注',
			'weixin_group' => '所在微信分组',
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
		$criteria->compare('dpid',$this->dpid);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('head_icon',$this->head_icon,true);
		$criteria->compare('mobile_num',$this->mobile_num,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('card_id',$this->card_id,true);
		$criteria->compare('user_level_lid',$this->user_level_lid,true);
		$criteria->compare('user_birthday',$this->user_birthday,true);
		$criteria->compare('openid',$this->openid,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('province',$this->province,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('unsubscribe',$this->unsubscribe);
		$criteria->compare('unsubscribe_time',$this->unsubscribe_time);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('consume_point_history',$this->consume_point_history);
		$criteria->compare('consume_point',$this->consume_point,true);
		$criteria->compare('scene_type',$this->scene_type);
		$criteria->compare('weixin_group',$this->weixin_group);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BrandUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
