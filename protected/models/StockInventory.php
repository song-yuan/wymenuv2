<?php

/**
 * This is the model class for table "nb_stock_inventory".
 *
 * The followings are the available columns in table 'nb_stock_inventory':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $stock_inven_accountno
 * @property string $organization_id
 * @property string $remark
 * @property string $delete_flag
 * @property string $is_aync
 */
class StockInventory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_stock_inventory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, update_at, stock_inven_accountno, remark', 'required'),
			array('lid, dpid, organization_id', 'length', 'max'=>10),
			array('stock_inven_accountno', 'length', 'max'=>25),
			array('remark', 'length', 'max'=>255),
			array('delete_flag', 'length', 'max'=>2),
			array('is_aync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, stock_inven_accountno, organization_id, remark, delete_flag, is_aync', 'safe', 'on'=>'search'),
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
			'stock_inven_accountno' => '盘存单号',
			'organization_id' => '组织名称',
			'remark' => '原因备注',
			'delete_flag' => '删除标志',
			'is_aync' => '同步标志',
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
		$criteria->compare('stock_inven_accountno',$this->stock_inven_accountno,true);
		$criteria->compare('organization_id',$this->organization_id,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);
		$criteria->compare('is_aync',$this->is_aync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StockInventory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function updateStatus($dpid,$id)
	{
		$sql = 'update nb_stock_inventory set status = 1 where dpid='.$dpid.' and lid='.$id;
		Yii::app()->db->createCommand($sql)->execute();
	}
}
