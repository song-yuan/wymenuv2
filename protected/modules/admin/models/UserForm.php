<?php
class UserForm extends CFormModel
{
	public $lid ;
	public $update_at ;
	public $username ;
	public $password_old ;
	public $password ;
	public $dpid ;
	public $mobile ;
	public $staff_no ;
	public $email ;
	public $role ;
	public $status = 1;
	public $delete_flag = 0;
	public function tableName() {
		return 'nb_user';
	}
	public function rules()
	{
		return array(
				// username and password are required
				array('lid,dpid,username, password , mobile , role', 'required'),
				array('username' , 'length' , 'min' => 5 , 'max' => 20),
				array('password' , 'length' , 'min' => 6 , 'max' => 16),
				array('dpid' , 'numerical'),
				array('lid , staff_no , email , password_old , update_at' , 'safe'),
		);
	}
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
				'lid' => 'ID',
				'username' => yii::t('app','登陆名'),
				'password' => yii::t('app','密码'),
				'dpid'=>yii::t('app','公司名称'),
				'mobile' => yii::t('app','手机号'),
				'staff_no' => yii::t('app','姓名（工号）'),
				'email' => yii::t('app','电子邮箱'),
				'role' => yii::t('app','管理员类型'),
		);
	}
	public function find($condition , $params) {
		$model = User::model()->find($condition , $params) ;
		
		$this->lid = $model->lid ;
		$this->username = $model->username;
		$this->mobile= $model->mobile;
		$this->staff_no = $model->staff_no ;
		$this->email = $model->email ;
		$this->role = $model->role ;
		$this->dpid = $model->dpid ;
		$this->password = $this->password_old = $model->password_hash ;
	}
	public function save() {
		if($this->lid) {
			$model = User::model()->find('lid=:id' , array(':id' => $this->lid));
		} else {
			$model = new User() ;
                        $se=new Sequence("user");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        
			//$model->lid = $this->getPkValue();
		}
		$model->update_at = date('Y-m-d H:i:s',time());
		$model->username = $this->username;
		$model->mobile = $this->mobile ;
		$model->staff_no = $this->staff_no;
		$model->email = $this->email;
		$model->role = $this->role ;
		$model->dpid = $this->dpid ;
		$model->status = 1;
		$model->delete_flag = '0';
		
		if($this->password_old != $this->password) {
			$model->password_hash = $this->password ;
		}
		if($model->validate()){
			if($this->password_old != $this->password) {
				$model->password_hash = Helper::genPassword($this->password) ;
			}
                        //var_dump($model);exit;
			$model->save();
			return true;
		} else {
			$this->addErrors($model->getErrors());
			if($passwordError = $model->getError('password_hash')){
				$this->addError('password', $passwordError);
			}
			return false;
		}
	}
	public function getPkValue() {
		$sql = 'SELECT NEXTVAL("'.$this->tableName().'") AS id';
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		return $row ? $row['id'] : 1 ;
	}
	
}