<?php

/**
 * This is the model class for table "nb_order_retreat".
 *
 * The followings are the available columns in table 'nb_order_retreat':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $retreat_id
 * @property string $order_detail_id
 * @property string $retreat_memo
 * @property string $delete_flag
 */
class OrderRetreat extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_order_retreat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('retreat_memo', 'required'),
			array('lid, dpid, retreat_id, order_detail_id', 'length', 'max'=>10),
			array('retreat_memo', 'length', 'max'=>50),
			array('delete_flag', 'length', 'max'=>1),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, retreat_id, order_detail_id, retreat_memo, delete_flag', 'safe', 'on'=>'search'),
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
                    'retreat'=> array(self::HAS_ONE , 'Retreat' , '','on'=>'t.dpid=retreat.dpid and t.retreat_id=retreat.lid'),
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
			'retreat_id' => yii::t('app','退菜理由'),
			'order_detail_id' => 'Order Detail',
			'retreat_memo' => yii::t('app','具体原因'),
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
		$criteria->compare('retreat_id',$this->retreat_id,true);
		$criteria->compare('order_detail_id',$this->order_detail_id,true);
		$criteria->compare('retreat_memo',$this->retreat_memo,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderRetreat the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
