<?php

/**
 * This is the model class for table "nb_inventory".
 *
 * The followings are the available columns in table 'nb_inventory':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $opretion_id
 * @property string $reason_id
 * @property string $inventory_account_no
 * @property string $status
 * @property string $remark
 * @property string $delete_flag
 * @property string $is_sync
 */
class Inventory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_inventory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, update_at, opretion_id, inventory_account_no,reason_id', 'required'),
			//array('opretion_id', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('inventory_account_no', 'length', 'max'=>32),
			array('status, delete_flag', 'length', 'max'=>2),
			array('opretion_id,is_sync', 'length', 'max'=>50),
			array('create_at,remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, opretion_id, reason_id,inventory_account_no, status, remark, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
				'retreat'=>array(self::BELONGS_TO , 'Retreat' , '','on'=>'t.dpid=retreat.dpid and t.reason_id=retreat.lid '),
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
			'opretion_id' => '操作员',
			'reason_id' => '盘损原因',
			'inventory_account_no' => '盘损单号',
			'status' => '状态',
			'remark' => '备注',
			'delete_flag' => '1表示删除',
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
		$criteria->compare('organization_id',$this->organization_id);
		$criteria->compare('inventory_account_no',$this->inventory_account_no,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('remark',$this->remark,true);
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
	 * @return Inventory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
