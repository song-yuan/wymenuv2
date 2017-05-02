<?php

/**
 * This is the model class for table "nb_stock_taking_detail".
 *
 * The followings are the available columns in table 'nb_stock_taking_detail':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $logid
 * @property string $material_id
 * @property string $reality_stock
 * @property string $taking_stock
 * @property string $number
 * @property string $reasion
 * @property string $status
 * @property string $delete_flag
 * @property string $is_sync
 */
class StockTakingDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_stock_taking_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, logid, material_id, taking_stock, number,', 'required'),
			array('lid, dpid, logid, material_id, last_stock, reality_stock, taking_stock, number', 'length', 'max'=>10),
			array('reasion', 'length', 'max'=>255),
			array('status, delete_flag', 'length', 'max'=>2),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, update_at, sales_stocks, last_stock, last_stock_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, logid, material_id, last_stock_id, last_stock_time, last_stock, reality_stock, taking_stock, number, sales_stocks, reasion, status, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'dpid' => 'Dpid',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'logid' => 'nb_stock_taking母记录的id',
			'material_id' => '原料id',
			'reality_stock' => '原始库存',
			'taking_stock' => '盘点库存',
			'number' => '盈亏差值',
			'reasion' => '原因',
			'status' => '状态',
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

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('logid',$this->logid,true);
		$criteria->compare('material_id',$this->material_id,true);
		$criteria->compare('reality_stock',$this->reality_stock,true);
		$criteria->compare('taking_stock',$this->taking_stock,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('reasion',$this->reasion,true);
		$criteria->compare('status',$this->status,true);
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
	 * @return StockTakingDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
