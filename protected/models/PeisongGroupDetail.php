<?php

/**
 * This is the model class for table "nb_peisong_group_detail".
 *
 * The followings are the available columns in table 'nb_peisong_group_detail':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $peisong_group_id
 * @property string $stock_dpid
 * @property string $material_id
 * @property string $mphs_code
 * @property string $delete_flag
 * @property string $is_sync
 */
class PeisongGroupDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_peisong_group_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, update_at, peisong_group_id, stock_dpid, material_id, mphs_code', 'required'),
			array('lid, dpid, peisong_group_id, stock_dpid, material_id', 'length', 'max'=>10),
			array('mphs_code', 'length', 'max'=>12),
			array('delete_flag', 'length', 'max'=>2),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, peisong_group_id, stock_dpid, material_id, mphs_code, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'dpid' => '总部id',
			'create_at' => 'Create At',
			'update_at' => '最近一次更新时间',
			'peisong_group_id' => '配送分组表的lid',
			'stock_dpid' => '配送组内原料的指定仓库的lid',
			'material_id' => '产品原料表的lid',
			'mphs_code' => '品项编码',
			'delete_flag' => '0表示存在，1表示删除',
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
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('peisong_group_id',$this->peisong_group_id,true);
		$criteria->compare('stock_dpid',$this->stock_dpid,true);
		$criteria->compare('material_id',$this->material_id,true);
		$criteria->compare('mphs_code',$this->mphs_code,true);
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
	 * @return PeisongGroupDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
