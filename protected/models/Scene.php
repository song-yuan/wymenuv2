<?php

/**
 * This is the model class for table "nb_scene".
 *
 * The followings are the available columns in table 'nb_scene':
 * @property integer $dpid
 * @property integer $scene_id
 * @property integer $type
 * @property integer $id
 * @property integer $expire_time
 * @property integer $create_time
 * @property integer $update_time
 */
class Scene extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Scene the static model class
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
		return 'nb_scene';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dpid, scene_id', 'required'),
			array('dpid, scene_id, type, id, expire_time, create_time, update_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('dpid, scene_id, type, id, expire_time, create_time, update_time', 'safe', 'on'=>'search'),
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
			'dpid' => 'Dpid',
			'scene_id' => 'Scene',
			'type' => 'Type',
			'id' => 'ID',
			'expire_time' => 'Expire Time',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
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

		$criteria->compare('dpid',$this->dpid);
		$criteria->compare('scene_id',$this->scene_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('id',$this->id);
		$criteria->compare('expire_time',$this->expire_time);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}