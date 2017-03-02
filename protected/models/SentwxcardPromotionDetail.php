<?php

/**
 * This is the model class for table "nb_sentwxcard_promotion_detail".
 *
 * The followings are the available columns in table 'nb_sentwxcard_promotion_detail':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $sole_code
 * @property string $sentwxcard_pro_id
 * @property string $fa_sole_code
 * @property string $card_type
 * @property string $wxcard_id
 * @property string $card_code
 * @property integer $sent_num
 * @property string $is_available
 * @property string $source
 * @property string $delete_flag
 * @property string $is_sync
 */
class SentwxcardPromotionDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_sentwxcard_promotion_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, sole_code, fa_sole_code, card_type, card_code, sent_num', 'required'),
			array('sent_num', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, sentwxcard_pro_id, wxcard_id', 'length', 'max'=>10),
			array('sole_code, fa_sole_code', 'length', 'max'=>20),
			array('card_type, is_available, source, delete_flag', 'length', 'max'=>2),
			array('card_code', 'length', 'max'=>15),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, sole_code, sentwxcard_pro_id, fa_sole_code, card_type, wxcard_id, card_code, sent_num, is_available, source, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
				'cupon' => array(self::BELONGS_TO , 'Cupon' ,'' ,'on'=>'t.dpid = cupon.dpid and t.wxcard_id = cupon.lid '),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => 'Lid',
			'dpid' => '店铺id',
			'create_at' => '创建时间',
			'update_at' => '最近一次更新时间',
			'sole_code' => '唯一编码',
			'sentwxcard_pro_id' => '父级活动id',
			'fa_sole_code' => '父级活动编码',
			'card_type' => '0表示系统券，1表示微信卡券',
			'wxcard_id' => '赠送券的id',
			'card_code' => '产品编码',
			'sent_num' => '赠送数量',
			'is_available' => '是否生效，0表示生效，1表示无效。',
			'source' => '0表示自建，1表示来自总部',
			'delete_flag' => '0表示存在，1表示删除。',
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
		$criteria->compare('sole_code',$this->sole_code,true);
		$criteria->compare('sentwxcard_pro_id',$this->sentwxcard_pro_id,true);
		$criteria->compare('fa_sole_code',$this->fa_sole_code,true);
		$criteria->compare('card_type',$this->card_type,true);
		$criteria->compare('wxcard_id',$this->wxcard_id,true);
		$criteria->compare('card_code',$this->card_code,true);
		$criteria->compare('sent_num',$this->sent_num);
		$criteria->compare('is_available',$this->is_available,true);
		$criteria->compare('source',$this->source,true);
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
	 * @return SentwxcardPromotionDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
