<?php

/**
 * This is the model class for table "nb_site".
 *
 * The followings are the available columns in table 'nb_site':
 * @property string $lid
 * @property string $splid
 * @property string $serial
 * @property integer $type_id
 * @property string $site_level
 * @property string $dpid
 * @property integer $delete_flag
 * @property integer $has_minimum_consumption
 * @property integer $minimum_consumption_type
 * @property string $minimum_consumption
 * @property string $number
 * @property double $period
 * @property double $overtime
 * @property double $buffer
 * @property string $overtime_fee
 * @property string $is_sync
 * @property string $site_channel_lid
 */
class Site extends CActiveRecord
{
    public $queuepersons;
    public $name;
    public $min;
    public $max;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_site';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('serial , type_id ,lid,floor_id, dpid' , 'required'),
			array('floor_id,type_id,splid, delete_flag, has_minimum_consumption, minimum_consumption_type', 'numerical', 'integerOnly'=>true),
			array('period, overtime,buffer', 'numerical'),
			array('serial', 'length', 'max'=>20),
                        array('qrcode', 'length', 'max'=>255),
				array('is_sync','length','max'=>50),
			//array('site_level', 'length', 'max'=>20),
			array('floor_id','compare','compareValue'=>'0','operator'=>'>','message'=>'楼层必须选择'),
			array('dpid, site_channel_lid, minimum_consumption, number, period, overtime, overtime_fee', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, serial,splid, , site_channel_lid, type_id, site_level, dpid, delete_flag, has_minimum_consumption, minimum_consumption_type,qr_code, minimum_consumption, number, period, overtime, buffer, overtime_fee, floor_id,qrcode, is_sync', 'safe', 'on'=>'search'),
		);
	}
	public function validate($attributes = NULL, $clearErrors = true){
		
		$valid = parent::validate();
		if(!$this->dpid){
			return false;
		}
		$site = Site::model()->find('lid<>:siteId and type_id=:typeId and dpid=:companyId and serial=:serial and delete_flag=0' , array(':serial'=>$this->serial,':siteId'=>$this->lid?$this->lid:'',':typeId'=>$this->type_id,':companyId'=>$this->dpid));
		if($site) {
			$this->addError('serial', '座位号已经存在');
			return false;
		}
		return !$this->hasErrors();
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'isfree' => array(self::HAS_ONE , 'SiteNo' , '' , 'on' => 't.lid=isfree.site_id and t.dpid=isfree.dpid and isfree.status in(2,3,4,5)'),
				'siteType' => array(self::BELONGS_TO , 'SiteType' ,'','on' =>'t.type_id=siteType.lid and t.dpid=siteType.dpid'),
                                'sitePersons' => array(self::BELONGS_TO , 'SitePersons' ,'','on' =>'t.splid=sitePersons.lid and t.dpid=sitePersons.dpid'),
                                'floor' => array(self::BELONGS_TO , 'Floor' ,'','on' =>'t.floor_id=floor.lid and t.dpid=floor.dpid')
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => 'Site',
			'serial' =>yii::t('app', '座位编号'),
			'type_id' =>yii::t('app', '座位类型'),
			'site_level' =>yii::t('app', '座位等级'),
                        'splid' =>yii::t('app', '座位人数'),
			'dpid' => 'Company',
			'delete_flag' => yii::t('app','删除'),
			'has_minimum_consumption' => yii::t('app','是否有最低消费'),
			'minimum_consumption_type' => yii::t('app','最低消费类型'),
			'minimum_consumption' =>yii::t('app', '最低消费（元/间（人））'),
			'number' =>yii::t('app', '人数'),
			'site_channel_lid' =>yii::t('app', '座位类型'),
			'period' => yii::t('app','最低消费时间（分钟）'),
			'overtime' =>yii::t('app', '超时单位（分钟）'),
			'buffer' =>yii::t('app', '超时计算点（分钟）'),
			'overtime_fee' =>yii::t('app', '超时费（元）'),
                        'floor_id' =>yii::t('app', '楼层'),
                        'Status' =>yii::t('app', '座位状态'),
                        'qrcode' =>yii::t('app', '二维码'),
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
		$criteria->compare('serial',$this->serial,true);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('site_level',$this->site_level,true);
		$criteria->compare('site_channel_lid',$this->site_channel_lid);
		$criteria->compare('dpid',$this->dpid,true);
                $criteria->compare('splid',$this->splid,true);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('has_minimum_consumption',$this->has_minimum_consumption);
		$criteria->compare('minimum_consumption_type',$this->minimum_consumption_type);
		$criteria->compare('minimum_consumption',$this->minimum_consumption,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('period',$this->period,true);
		$criteria->compare('overtime',$this->overtime,true);
		$criteria->compare('buffer',$this->buffer);
		$criteria->compare('overtime_fee',$this->overtime_fee,true);
                $criteria->compare('floor_id',$this->floor_id,true);
                $criteria->compare('status',$this->status,true);
                $criteria->compare('qrcode',$this->qrcode,true);
                $criteria->compare('is_sync',$this->is_sync,true);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Site the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
