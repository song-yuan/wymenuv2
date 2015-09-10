<?php

/**
 * This is the model class for table "nb_order_printjobs".
 *
 * The followings are the available columns in table 'nb_order_printjobs':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $jobid
 * @property string $orderid
 * @property string $address
 * @property string $content
 * @property string $printer_type
 * @property string $finish_flag
 * @property string $delete_flag
 */
class OrderPrintjobs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_order_printjobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('address, content', 'required'),
			array('lid, dpid, jobid, orderid', 'length', 'max'=>10),
			array('address', 'length', 'max'=>64),
			array('printer_type', 'length', 'max'=>2),
			array('finish_flag, delete_flag', 'length', 'max'=>1),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, jobid, orderid, address, content, printer_type, finish_flag, delete_flag', 'safe', 'on'=>'search'),
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
                    'printer' => array(self::BELONGS_TO , 'Printer' ,'','on' =>'t.address=printer.address and t.dpid=printer.dpid')
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
			'jobid' => 'Jobid',
			'orderid' => '店铺id',
			'address' => '地址(IP/COM/USB)',
			'content' => 'Content',
			'printer_type' => '0网络，1本地',
			'finish_flag' => '0没有完成，1已经重新打印完成',
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

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('jobid',$this->jobid,true);
		$criteria->compare('orderid',$this->orderid,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('printer_type',$this->printer_type,true);
		$criteria->compare('finish_flag',$this->finish_flag,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderPrintjobs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
