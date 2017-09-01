<?php

/**
 * This is the model class for table "nb_buysent_promotion_detail".
 *
 * The followings are the available columns in table 'nb_buysent_promotion_detail':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $sole_code
 * @property string $buysent_pro_id
 * @property string $fa_sole_code
 * @property string $is_set
 * @property string $product_id
 * @property string $phs_code
 * @property integer $buy_num
 * @property string $s_product_id
 * @property string $s_phs_code
 * @property integer $sent_num
 * @property integer $limit_num
 * @property integer $group_no
 * @property string $is_available
 * @property string $source
 * @property string $delete_flag
 * @property string $is_sync
 */
class BuysentPromotionDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_buysent_promotion_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, sole_code, fa_sole_code, is_set, phs_code, buy_num, sent_num, limit_num, group_no', 'required'),
			array('buy_num, sent_num, limit_num, group_no', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, buysent_pro_id, product_id , s_product_id', 'length', 'max'=>10),
			array('sole_code, fa_sole_code, is_set', 'length', 'max'=>20),
			array('phs_code , s_phs_code', 'length', 'max'=>15),
			array('is_available, source, delete_flag', 'length', 'max'=>2),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, sole_code, buysent_pro_id, fa_sole_code, is_set, product_id, phs_code, buy_num, s_product_id, s_phs_code, sent_num, limit_num, group_no, is_available, source, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'dpid' => '店铺id',
			'create_at' => '创建时间',
			'update_at' => '最近一次更新时间',
			'sole_code' => '唯一编码',
			'buysent_pro_id' => '父级活动id',
			'fa_sole_code' => '父级活动编码',
			'is_set' => '0表示单品，1表示套餐',
			'product_id' => '参与活动的产品id',
			'phs_code' => '产品编码',
			'buy_num' => '购买数量',
			's_product_id' => '赠送产品id',
			's_phs_code' => '赠送产品编码',
			'sent_num' => '赠送数量',
			'limit_num' => '限制数量',
			'group_no' => '多级买送的级数,最大三级',
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
		$criteria->compare('buysent_pro_id',$this->buysent_pro_id,true);
		$criteria->compare('fa_sole_code',$this->fa_sole_code,true);
		$criteria->compare('is_set',$this->is_set,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('phs_code',$this->phs_code,true);
		$criteria->compare('buy_num',$this->buy_num);
		$criteria->compare('s_product_id',$this->s_product_id,true);
		$criteria->compare('s_phs_code',$this->s_phs_code,true);
		$criteria->compare('sent_num',$this->sent_num);
		$criteria->compare('limit_num',$this->limit_num);
		$criteria->compare('group_no',$this->group_no);
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
	 * @return BuysentPromotionDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
