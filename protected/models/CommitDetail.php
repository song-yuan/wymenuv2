<?php

/**
 * This is the model class for table "nb_commit_detail".
 *
 * The followings are the available columns in table 'nb_commit_detail':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $material_id
 * @property string $unit_name
 * @property string $stock
 * @property string $is_sync
 */
class CommitDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_commit_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, material_id, unit_name', 'required'),
			array('lid, dpid, commit_id, material_id, stock', 'length', 'max'=>10),
			array('unit_name', 'length', 'max'=>255),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at,commit_id, material_id, unit_name, stock, is_sync', 'safe', 'on'=>'search'),
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
			'company' => array(self::BELONGS_TO , 'Company' , 'dpid'),
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
			'commit_id'=>'Commit Id',
			'material_id' => '品项名称',
			'unit_name' => '库存单位',
			'stock' => '调拨数量',
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

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('material_id',$this->material_id,true);
		$criteria->compare('unit_name',$this->unit_name,true);
		$criteria->compare('stock',$this->stock,true);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommitDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
