<?php

/**
 * This is the model class for table "nb_user_company".
 *
 * The followings are the available columns in table 'nb_user_company':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $company_id
 * @property string $user_id
 * @property string $delete_flag
 */
class UserCompany extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_user_company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid', 'required'),
			array('lid, dpid, company_id, user_id', 'length', 'max'=>10),
			array('delete_flag', 'length', 'max'=>1),
			array('create_at,update_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, company_id, user_id, delete_flag', 'safe', 'on'=>'search'),
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
                    'user' => array(self::BELONGS_TO , 'User' ,'','on' =>'t.user_id=user.lid and t.dpid=user.dpid'),
                    'company' => array(self::BELONGS_TO , 'Company' ,'','on' =>'t.company_id=company.dpid')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => '自身id，统一dpid下递增',
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'company_id' => yii::t('app','被管理店铺'),
			'user_id' => '用户id',
			'delete_flag' => 'Delete Flag',
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
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserCompany the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
