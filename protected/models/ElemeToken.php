
<?php

/**
 * This is the model class for table "nb_eleme_token".
 *
 * The followings are the available columns in table 'nb_eleme_token':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $token_type
 * @property string $access_token
 * @property integer $expires_in
 * @property string $refresh_token
 * @property string $delete_flag
 * @property string $is_sync
 */
class ElemeToken extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'nb_eleme_token';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('update_at, access_token, expires_in, refresh_token', 'required'),
            array('expires_in', 'numerical', 'integerOnly'=>true),
            array('lid, dpid, token_type', 'length', 'max'=>10),
            array('access_token', 'length', 'max'=>64),
            array('refresh_token', 'length', 'max'=>25),
            array('delete_flag', 'length', 'max'=>2),
            array('is_sync', 'length', 'max'=>50),
            array('create_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('lid, dpid, create_at, update_at, token_type, access_token, expires_in, refresh_token, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
            'token_type' => 'Token Type',
            'access_token' => 'Access Token',
            'expires_in' => 'Expires In',
            'refresh_token' => 'Refresh Token',
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
        $criteria->compare('token_type',$this->token_type,true);
        $criteria->compare('access_token',$this->access_token,true);
        $criteria->compare('expires_in',$this->expires_in);
        $criteria->compare('refresh_token',$this->refresh_token,true);
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
     * @return ElemeToken the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}