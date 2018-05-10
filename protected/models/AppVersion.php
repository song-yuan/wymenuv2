<?php

/**
 * This is the model class for table "nb_app_version".
 *
 * The followings are the available columns in table 'nb_app_version':
 * @property integer $lid
 * @property string $create_at
 * @property string $update_at
 * @property string $type
 * @property string $app_type
 * @property string $app_version
 * @property string $apk_url
 * @property string $delete_flag
 * @property string $is_sync
 */
class AppVersion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_app_version';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, apk_url, content', 'required'),
			array('lid', 'numerical', 'integerOnly'=>true),
			array('type, app_type, delete_flag', 'length', 'max'=>2),
			array('app_version', 'length', 'max'=>15),
			array('apk_url', 'length', 'max'=>255),
			array('is_sync', 'length', 'max'=>55),
			array('create_at, update_at,content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, create_at, update_at, type, app_type, app_version, apk_url, content, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'type' => '更新模式.',
			'app_type' => 'app类型',
			'app_version' => '版本号',
			'apk_url' => 'APK名称',
			'content' => '更新说明',	
			'delete_flag' => 'Delete Flag',
			'is_sync' => 'Is Sync',
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

		$criteria->compare('lid',$this->lid);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('app_type',$this->app_type,true);
		$criteria->compare('app_version',$this->app_version,true);
		$criteria->compare('apk_url',$this->apk_url,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppVersion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
