<?php

/**
 * This is the model class for table "nb_announcement".
 *
 * The followings are the available columns in table 'nb_announcement':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $type
 * @property string $use_type
 * @property string $title
 * @property string $memo
 * @property string $text
 * @property string $status
 * @property string $delete_flag
 * @property string $is_sync
 */
class Announcement extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_announcement';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at', 'required'),
			array('lid, dpid', 'length', 'max'=>10),
			array('type, use_type, status, delete_flag', 'length', 'max'=>2),
			array('title, memo, organization, publisher', 'length', 'max'=>16),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, type, use_type, organization, publisher, title, memo, content, status, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
				'company' => array(self::BELONGS_TO , 'Company' ,'' ,'on'=>'t.dpid = company.dpid'),
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
			'type' => '公告属性',
			'use_type' => '类型',
			'organization' => '发布组织',
			'publisher' => '发布人',
			'title' => '标题',
			'memo' => '摘要',
			'content' => '编辑正文',
			'status' => '是否显示',
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
		$criteria->compare('use_type',$this->use_type,true);
		$criteria->compare('organization',$this->organization,true);
		$criteria->compare('publisher',$this->publisher,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status,true);
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
	 * @return Announcement the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
