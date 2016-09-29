<?php

/**
 * This is the model class for table "nb_member_points".
 *
 * The followings are the available columns in table 'nb_member_points':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $member_card_rfid
 * @property string $order_id
 * @property integer $points
 * @property string $delete_flag
 * @property string $is_sync
 */
class MemberPoints extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_member_points';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, update_at, member_card_rfid, order_id', 'required'),
			array('points', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, member_card_rfid, order_id', 'length', 'max'=>10),
			array('delete_flag', 'length', 'max'=>2),
			array('is_sync', 'length', 'max'=>55),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, member_card_rfid, order_id, points, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
				 'order' => array(self::BELONGS_TO , 'Order' ,'' ,'on'=>'t.dpid=order.dpid and t.order_id=order.lid ') , //not 4,8
				
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
			'member_card_rfid' => 'member_card的rfid',
			'order_id' => 'Order',
			'points' => 'Points',
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
		$criteria->compare('member_card_rfid',$this->member_card_rfid,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('points',$this->points);
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
	 * @return MemberPoints the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
