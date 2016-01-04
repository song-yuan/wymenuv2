<?php

/**
 * This is the model class for table "nb_gift".
 *
 * The followings are the available columns in table 'nb_gift':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $title
 * @property string $intro
 * @property string $price
 * @property string $gift_pic
 * @property integer $count
 * @property integer $stock
 * @property string $begin_time
 * @property string $end_time
 * @property integer $delete_flag
 * @property string $is_sync
 */
class Gift extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Gift the static model class
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
		return 'nb_gift';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, price, count', 'required'),
			array('count, stock, delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, price', 'length', 'max'=>10),
			array('title, gift_pic', 'length', 'max'=>255),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, intro, begin_time, end_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, title, intro, price, gift_pic, count, stock, begin_time, end_time, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'title' => '礼品名称',
			'intro' => '礼品简介',
			'price' => 'Price',
			'gift_pic' => 'Gift Pic',
			'count' => 'Count',
			'stock' => 'Stock',
			'begin_time' => 'Begin Time',
			'end_time' => 'End Time',
			'delete_flag' => 'Delete Flag',
			'is_sync' => 'Is Sync',
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
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('intro',$this->intro,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('gift_pic',$this->gift_pic,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('stock',$this->stock);
		$criteria->compare('begin_time',$this->begin_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}