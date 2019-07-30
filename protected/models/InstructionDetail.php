<?php

/**
 * This is the model class for table "nb_instruction_detail".
 *
 * The followings are the available columns in table 'nb_instruction_detail':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property integer $instruction_id
 * @property string $time
 * @property string $instruct
 * @property string $sort
 * @property string $delete_flag
 * @property string $is_sync
 */
class InstructionDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_instruction_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, create_at, instruction_id, instruct_name, time, instruct, sort', 'required'),
			array('instruction_id', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('number, time', 'length', 'max'=>4),
			array('instruct_name', 'length', 'max'=>16),
			array('instruct', 'length', 'max'=>64),
			array('sort', 'length', 'max'=>3),
			array('is_sync', 'length', 'max'=>50),
			array('is_waiting, is_enquire, delete_flag', 'length', 'max'=>2),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, instruction_id, number, instruct_name, time, instruct, sort, is_waiting, is_enquire, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
				'instruct'=>array(self::HAS_ONE,'Instruction','','on'=>'t.instruction_id=instruct.lid')
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
			'instruction_id' => 'Instruction',
			'number' => '板子编号',
			'instruct_name' => '指令名称',
			'time' => '执行时间',
			'instruct' => '指令',
			'sort' => '排序',
			'is_waiting' => '是否等待',
			'is_enquire' => '是否查询',
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
		$criteria->compare('instruction_id',$this->instruction_id);
		$criteria->compare('number',$this->time,true);
		$criteria->compare('instruct_name',$this->instruct_name,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('instruct',$this->instruct,true);
		$criteria->compare('sort',$this->sort,true);
		$criteria->compare('is_waiting',$this->is_waiting,true);
		$criteria->compare('is_enquire',$this->is_enquire,true);
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
	 * @return InstructionDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
