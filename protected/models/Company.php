<?php

/**
 * This is the model class for table "nb_company".
 *
 * The followings are the available columns in table 'nb_company':
 * @property string $company_id
 * @property string $company_name
 * @property string $logo
 * @property string $contact_name
 * @property string $mobile
 * @property string $telephone
 * @property string $email
 * @property string $address
 * @property string $homepage
 * @property integer $create_time
 * @property integer $delete_flag
 * @property string $description
 * @property string $printer_id
 */
class Company extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time,printer_id, delete_flag', 'numerical', 'integerOnly'=>true),
			array('company_name, email', 'length', 'max'=>50),
			array('logo, homepage', 'length', 'max'=>255),
			array('contact_name, mobile, telephone', 'length', 'max'=>20),
			array('description','length'),
			array('address','length'),
			array('company_name, logo, contact_name, mobile' , 'required'),
			array('email', 'length', 'min'=>6, 'max'=>40,'message'=>'请输入4到20的电子邮件'),
			array('mobile','match','pattern'=>'/^[1][358]\d{9}$/','message'=>'请填写有效的手机号码'),
			array('telephone', 'match','pattern'=>'/(^[0-9]{3,4}[0-9]{7,8}$)|(^400\-[0-9]{3}\-[0-9]{4}$)|(^[0-9]{3,4}\-[0-9]{7,8}$)|(^0{0,1}13[0-9]{9}$)/' ,'message'=>'请填写有效的电话号码'),
			
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('company_id, company_name, logo, contact_name, mobile, telephone, email, homepage, create_time, delete_flag, description', 'safe', 'on'=>'search'),
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
			'company_id' => 'Company',
			'company_name' => '公司名称',
			'logo' => 'Logo',
			'contact_name' => '联系人',
			'mobile' => '联系人手机',
			'telephone' => '电话',
			'email' => '电子邮箱',
			'address'=>'公司地址',
			'homepage' => '公司主页',
			'create_time' => '创建时间',
			'delete_flag' => '状态',
			'description' => '公司描述',
			'printer_id' => '打印机ID',
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

		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('contact_name',$this->contact_name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('homepage',$this->homepage,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('printer_id',$this->printer_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Company the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
