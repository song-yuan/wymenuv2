<?php

/**
 * This is the model class for table "nb_data_sync".
 *
 * The followings are the available columns in table 'nb_data_sync':
 * @property string $lid
 * @property string $dpid
 * @property string $cmd_code
 * @property string $create_at
 * @property string $update_at
 * @property string $cmd_data
 * @property string $sync_result
 * @property string $is_interface
 */
class DataSync extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DataSync the static model class
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
		return 'nb_data_sync';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cmd_code, update_at', 'required'),
			array('lid, dpid, cmd_code', 'length', 'max'=>10),
			array('sync_result, is_interface', 'length', 'max'=>1),
			array('create_at, cmd_data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, dpid, cmd_code, create_at, update_at, cmd_data, sync_result, is_interface', 'safe', 'on'=>'search'),
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
			'lid' => '指令序列号',
			'dpid' => 'Dpid',
			'cmd_code' => '指令',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'cmd_data' => '指令数据',
			'sync_result' => '同步结果',
			'is_interface' => '是否第三方接口',
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

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('cmd_code',$this->cmd_code,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('cmd_data',$this->cmd_data,true);
		$criteria->compare('sync_result',$this->sync_result,true);
		$criteria->compare('is_interface',$this->is_interface,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function primaryKey()
        {
            //return 'id';
            // 对于复合主键，要返回一个类似如下的数组
            return array('lid', 'dpid','cmd_code');
        }
}