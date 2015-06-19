<?php

/**
 * This is the model class for table "nb_pad".
 *
 * The followings are the available columns in table 'nb_pad':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $name
 * @property string $printer_id
 * @property string $server_address
 * @property string $pad_type
 * @property string $delete_flag
 */
class Pad extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_pad';
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
			array('lid, dpid, printer_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>100),
			array('server_address', 'length', 'max'=>70),
			array('pad_type, delete_flag,is_bind', 'length', 'max'=>1),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, name, printer_id, server_address, pad_type,is_bind, delete_flag', 'safe', 'on'=>'search'),
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
                    'printer' => array(self::HAS_ONE , 'Printer' ,'' ,'on'=>'t.printer_id=printer.lid and t.dpid=printer.dpid') 				
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => 'PAD',
			'dpid' => '店铺',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'name' => yii::t('app','名称'),
			'printer_id' =>yii::t('app', '默认打印机'),
			'server_address' =>yii::t('app', '消息服务器地址'),
			'pad_type' =>yii::t('app', '类型'),
                        'is_bind' => yii::t('app','绑定'),
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
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('printer_id',$this->printer_id,true);
		$criteria->compare('server_address',$this->server_address,true);
		$criteria->compare('pad_type',$this->pad_type,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pad the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
