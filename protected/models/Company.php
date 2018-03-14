<?php

/**
 * This is the model class for table "nb_company".
 *
 * The followings are the available columns in table 'nb_company':
 * @property string $dpid
 * @property string $company_name
 * @property string $logo
 * @property string $contact_name
 * @property string $mobile
 *  @property string $type
 * @property string $telephone
 * @property string $email
 * @property string $address
 * @property string $homepage
 * @property integer $create_at
 * @property integer $delete_flag
 * @property string $description
 * @property string $queuememo
 * @property string $printer_id
 * @property string $is_sync
 * @property string $is_membercard_recharge
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
			array('printer_id, distance, delete_flag, ', 'numerical', 'integerOnly'=>true),
			array('company_name, email, is_sync', 'length', 'max'=>50),
			array('logo, domain, homepage, country, province, city, county_area', 'length', 'max'=>255),
			array('contact_name, mobile, telephone', 'length', 'max'=>20),
			array('lng, lat', 'length', 'max'=>10),
			array('description','length'),
			array('queuememo','length'),
			array('address','length'),
			array('type, is_membercard_recharge, membercard_points_type','length','max'=>2),
			array('membercard_code','length','max'=>16),
			array('membercard_enable_date','length','max'=>3),
			array('company_name, logo, contact_name, mobile' , 'required'),
			array('email', 'length', 'min'=>6, 'max'=>40,'message'=>yii::t('app','请输入6到20的电子邮件')),
			//array('mobile','match','pattern'=>'/^[1][358]\d{9}$/','message'=>yii::t('app','请填写有效的手机号码')),
			//array('telephone', 'match','pattern'=>'/(^[0-9]{3,4}[0-9]{7,8}$)|(^400\-[0-9]{3}\-[0-9]{4}$)|(^[0-9]{3,4}\-[0-9]{7,8}$)|(^0{0,1}13[0-9]{9}$)/' ,'message'=>yii::t('app','请填写有效的电话号码')),
			
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dpid, company_name, logo, token, contact_name,type, is_membercard_recharge, membercard_code, membercard_enable_date, membercard_points_type, is_sync, mobile, telephone, email, lng, lat, distance, country, province, city, county_area, homepage, domain, create_at, delete_flag, description, queuememo', 'safe', 'on'=>'search'),
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
				'property'=>array(self::HAS_ONE,'CompanyProperty','','on'=>'t.dpid = property.dpid'),
				'posfee'=>array(self::HAS_MANY,'PoscodeFee','','on'=>'t.dpid = posfee.dpid')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dpid' => 'Company',
			'company_name' => yii::t('app','公司名称'),
			'logo' => 'Logo',
			'contact_name' => yii::t('app','联系人'),
			'type' => yii::t('app','类型'),
			'is_membercard_recharge' => yii::t('app','是否允许会员卡充值'),
			'membercard_code' => yii::t('app','店铺会员卡使用秘钥'),
			'membercard_enable_date' => yii::t('app','店铺会员卡有效期限（年）'),
			'membercard_points_type' => yii::t('app','店铺会员卡消费积分方式'),
			'mobile' =>yii::t('app', '联系人手机'),
			'telephone' => yii::t('app','电话'),
			'email' => yii::t('app','电子邮箱'),
				'country' => yii::t('app','国家'),
				'province' => yii::t('app','省份'),
				'city' => yii::t('app','城市'),
				'county_area' => yii::t('app','县区'),
			'address'=>yii::t('app','具体街道号'),
			'homepage' => yii::t('app','公司主页'),
            'domain'=>yii::t('app','系统服务地址'),
            'distance'=> yii::t('app','外卖范围'),
			'create_at' => yii::t('app','创建时间'),
			'delete_flag' => yii::t('app','状态'),
			'description' => yii::t('app','公司描述'),
			'queuememo' => yii::t('app','取号打印小票备注'),
			'printer_id' => yii::t('app','打印机ID'),
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

		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('contact_name',$this->contact_name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('is_membercard_recharge',$this->is_membercard_recharge,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('province',$this->province,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('county_area',$this->county_area,true);
		$criteria->compare('homepage',$this->homepage,true);
        $criteria->compare('domain',$this->domain,true);
		$criteria->compare('create_at',$this->create_at);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('queuememo',$this->queuememo,true);
		$criteria->compare('lng',$this->lng,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('distance',$this->distance,true);
		$criteria->compare('printer_id',$this->printer_id,true);
		$criteria->compare('is_sync',$this->is_sync,true);

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
        
        static public function getCompanyName($dpid){
            $db = Yii::app()->db;
            $sql = "SELECT company_name from nb_company where dpid=:dpid";
            $command=$db->createCommand($sql);
            $command->bindValue(":dpid" , $dpid);
            $nowval= $command->queryScalar();
            return $nowval;
        }
        
        static public function getQueueMemo($dpid){
            $db = Yii::app()->db;
            $sql = "SELECT queuememo from nb_company where dpid=:dpid";
            $command=$db->createCommand($sql);
            $command->bindValue(":dpid" , $dpid);
            $nowval= $command->queryScalar();
            return $nowval;
        }
}
