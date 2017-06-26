<?php

/**
 * This is the model class for table "nb_pad_setting".
 *
 * The followings are the available columns in table 'nb_pad_setting':
 * @property integer $lid
 * @property integer $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $pad_code
 * @property string $pad_sales_type
 * @property string $pad_type
 * @property string $pad_ip
 * @property string $pad_fip
 * @property string $bt_mac
 * @property string $is_product_free
 * @property string $sync_at
 * @property string $delete_flag
 * @property string $is_sync
 */
class PadSetting extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_pad_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid', 'required'),
			array('pad_code' , 'unique' , 'message'=>'请重新添加，有未知错误'),
			array('lid, dpid', 'numerical', 'integerOnly'=>true),
			array('pad_code, is_sync', 'length', 'max'=>50),
			array('pad_sales_type, screen_type, pad_type, pay_activate', 'length', 'max'=>2),
			array('pad_ip, pad_fip, bt_mac', 'length', 'max'=>20),
			array('is_product_free, delete_flag', 'length', 'max'=>1),
			array('create_at, update_at, sync_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, pad_code, pay_activate, pad_sales_type, screen_type, pad_type, pad_ip, pad_fip, bt_mac, is_product_free, sync_at, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
                    'detail'=>array(self::HAS_MANY,'PadSettingDetail','','on'=>'t.lid = detail.pad_setting_id and detail.dpid = t.dpid and detail.delete_flag = 0'),
                    'company' => array(self::BELONGS_TO , 'Company' ,'' ,'on'=>'t.dpid = company.dpid') ,
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
			'pad_code' => '店铺同步码',
			'pay_activate' => '激活开通支付',
			'pad_sales_type' => '店铺模式 ',
			'screen_type' => '收款机类型 ',
			'pad_type' => 'pos类型 0 主pos 1从pos',
			'pad_ip' => 'Pad Ip',
			'pad_fip' => 'Pad Fip',
			'bt_mac' => '蓝牙打印机mac地址',
			'is_product_free' => '是否允许售价为0',
			'sync_at' => 'Sync At',
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

		$criteria->compare('lid',$this->lid);
		$criteria->compare('dpid',$this->dpid);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('pad_code',$this->pad_code,true);
		$criteria->compare('pay_activate',$this->pay_activate,true);
		$criteria->compare('pad_sales_type',$this->pad_sales_type,true);
		$criteria->compare('pad_type',$this->pad_type,true);
		$criteria->compare('pad_ip',$this->pad_ip,true);
		$criteria->compare('pad_fip',$this->pad_fip,true);
		$criteria->compare('bt_mac',$this->bt_mac,true);
		$criteria->compare('is_product_free',$this->is_product_free,true);
		$criteria->compare('sync_at',$this->sync_at,true);
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
	 * @return PadSetting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function getRandomString($len, $chars=null)
	{
		if (is_null($chars)){
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		}elseif ($chars==0){
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		}elseif ($chars==1){
			$chars = "0123456789";
		}
		mt_srand(10000000*(double)microtime());
		for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
			$str .= $chars[mt_rand(0, $lc)];
		}
		return $str;
	}
	static public function getNo($lid,$num)
	{
		
		$ret=substr("0000000000".$lid, -$num);
		
		return $ret;
	}
}
