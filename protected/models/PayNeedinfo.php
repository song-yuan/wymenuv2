<?php

/**
 * This is the model class for table "nb_pay_needinfo".
 *
 * The followings are the available columns in table 'nb_pay_needinfo':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $type
 * @property string $company_name
 * @property string $contact_name
 * @property integer $mobile
 * @property string $company_address
 * @property string $bank_name
 * @property string $bank_address
 * @property string $sub_branch_add
 * @property integer $account_opening
 * @property string $opening_name
 * @property string $photo_head
 * @property string $photo_indoor
 * @property string $photo_outdoor
 * @property string $photo_otherone
 * @property string $photo_othertwo
 * @property string $photo_otherthr
 * @property string $delete_flag
 * @property string $is_sync
 */
class PayNeedinfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_pay_needinfo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contact_name, mobile, company_address, bank_name, bank_address, sub_branch_add, account_opening, photo_head, photo_indoor, photo_outdoor, photo_otherone, photo_othertwo, photo_otherthr', 'required'),
			array('mobile, account_opening', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, company_name, contact_name, opening_name', 'length', 'max'=>10),
			array('type, delete_flag', 'length', 'max'=>2),
			array('company_address, sub_branch_add', 'length', 'max'=>32),
			array('photo_head, photo_indoor, photo_outdoor, photo_otherone, photo_othertwo, photo_otherthr', 'length', 'max'=>255),
			array('is_sync', 'length', 'max'=>50),
			array('company_name, update_at, create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, type, company_name, contact_name, mobile, company_address, bank_name, bank_address, sub_branch_add, account_opening, opening_name, photo_head, photo_indoor, photo_outdoor, photo_otherone, photo_othertwo, photo_otherthr, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'lid' => '自身id，统一dpid下递增',
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'type' => '1表示',
			'company_name' => '商户名称',
			'contact_name' => '联系人',
			'mobile' => '联系电话',
			'company_address' => '店铺地址',
			'bank_name' => '开户行',
			'bank_address' => '开户行地址',
			'sub_branch_add' => '开户行支行',
			'account_opening' => '开户账号',
			'opening_name' => '户名',
			'photo_head' => '门头照片',
			'photo_indoor' => '内景照片',
			'photo_outdoor' => '外景照片',
			'photo_otherone' => '其他照片',
			'photo_othertwo' => '其他照片',
			'photo_otherthr' => '其他照片',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('contact_name',$this->contact_name,true);
		$criteria->compare('mobile',$this->mobile);
		$criteria->compare('company_address',$this->company_address,true);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('bank_address',$this->bank_address,true);
		$criteria->compare('sub_branch_add',$this->sub_branch_add,true);
		$criteria->compare('account_opening',$this->account_opening);
		$criteria->compare('opening_name',$this->opening_name,true);
		$criteria->compare('photo_head',$this->photo_head,true);
		$criteria->compare('photo_indoor',$this->photo_indoor,true);
		$criteria->compare('photo_outdoor',$this->photo_outdoor,true);
		$criteria->compare('photo_otherone',$this->photo_otherone,true);
		$criteria->compare('photo_othertwo',$this->photo_othertwo,true);
		$criteria->compare('photo_otherthr',$this->photo_otherthr,true);
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
	 * @return PayNeedinfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
