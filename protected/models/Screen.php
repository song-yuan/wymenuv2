<?php

/**
 * This is the model class for table "nb_screen".
 *
 * The followings are the available columns in table 'nb_screen':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $title
 * @property string $vedio_pic
 * @property string $vedio_url
 * @property string $remark
 * @property integer $delete_flag
 * @property string $is_sync
 */
class Screen extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Screen the static model class
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
		return 'nb_screen';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, title, vedio_url', 'required'),
			array('delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('title, vedio_pic, vedio_url, discuss_pic, default_content, remark', 'length', 'max'=>255),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, title, vedio_pic, discuss_pic, vedio_url, default_content, remark, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'title' => '视频名称',
			'vedio_pic' => '视频图片',
			'vedio_url' => '视频地址',
			'discuss_pic' => '弹幕背景(941*706以上)',
			'default_content'=>'默认显示内容',
			'remark' => '备注',
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
		$criteria->compare('vedio_pic',$this->vedio_pic,true);
		$criteria->compare('vedio_url',$this->vedio_url,true);
		$criteria->compare('discuss_pic',$this->discuss_pic,true);
		$criteria->compare('default_content',$this->default_content,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}