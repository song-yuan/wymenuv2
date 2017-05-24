<?php

/**
 * This is the model class for table "nb_postable_sync".
 *
 * The followings are the available columns in table 'nb_postable_sync':
 * @property string $lid
 * @property string $create_at
 * @property string $update_at
 * @property string $content
 * @property string $delete_flag
 * @property string $is_sync
 */
class PostableSync extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_postable_sync';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('table,content', 'required'),
			array('lid', 'length', 'max'=>10),
			array('delete_flag', 'length', 'max'=>2),
			array('is_sync', 'length', 'max'=>50),
			array('update_at, create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, create_at, update_at, table, content, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'table' => '表名',
			'content' => '表结构',
			'delete_flag' => '是否删除 0 否 1 是',
			'is_sync' => '同步标志',
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
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('table',$this->table,true);
		$criteria->compare('content',$this->content,true);
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
	 * @return PostableSync the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
