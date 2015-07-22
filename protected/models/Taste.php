<?php

/**
 * This is the model class for table "nb_taste".
 *
 * The followings are the available columns in table 'nb_taste':
 * @property integer $lid
 * @property string $dpid
 * @property string $taste_group_id
 * @property string $create_at
 * @property string $update_at
 * @property string $name
 * @property string $allflae
 * @property string $delete_flag
 */
class Taste extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_taste';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			//array('lid', 'numerical', 'integerOnly'=>true),
			array('lid,dpid, taste_group_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>50),
			array('allflae, delete_flag', 'length', 'max'=>1),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, taste_group_id, create_at, name, allflae, delete_flag', 'safe', 'on'=>'search'),
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
			'lid' => '自身id，统一dpid下递增',
			'dpid' => '店铺id',
			'taste_group_id' => '口味分组',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'name' => '口味描述',
			'allflae' => '1整单口味，0不是',
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

		$criteria->compare('lid',$this->lid);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('taste_group_id',$this->taste_group_id,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('allflae',$this->allflae,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Taste the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
