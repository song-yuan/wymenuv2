<?php
class UserForm extends CFormModel
{
	public $id ;
	public $username ;
	public $password_old ;
	public $password ;
	public $company_id ;
	public $mobile ;
	public $staff_no ;
	public $email ;
	public $role ;
	public $status = 1;
	
	public function rules()
	{
		return array(
				// username and password are required
				array('username, password , mobile , role', 'required'),
				array('username' , 'length' , 'min' => 5 , 'max' => 20),
				array('password' , 'length' , 'min' => 6 , 'max' => 16),
				array('company_id' , 'numerical'),
				array('id , staff_no , email , password_old' , 'safe'),
		);
	}
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
				'id' => 'ID',
				'username' => '用户名',
				'password' => '密码',
				'company_id'=>'公司名称',
				'mobile' => '手机号',
				'staff_no' => '员工号',
				'email' => '电子邮箱',
				'role' => '管理员类型',
		);
	}
	public function find($condition , $params) {
		$model = User::model()->find($condition , $params) ;
		
		$this->id = $model->id ;
		$this->username = $model->username;
		$this->mobile= $model->mobile;
		$this->staff_no = $model->staff_no ;
		$this->email = $model->email ;
		$this->role = $model->role ;
		$this->company_id = $model->company_id ;
		$this->password = $this->password_old = $model->password_hash ;
	}
	public function save() {
		if($this->id) {
			$model = User::model()->find('id=:id' , array(':id' => $this->id));
		} else {
			$model = new User() ;
		}
		$model->id = $this->id;
		$model->username = $this->username;
		$model->mobile = $this->mobile ;
		$model->staff_no = $this->staff_no;
		$model->email = $this->email;
		$model->role = $this->role ;
		$model->company_id = $this->company_id ;
		$model->status = 1;
		if($this->password_old != $this->password) {
			$model->password_hash = $this->password ;
		}
		if($model->validate()){
			if($this->password_old != $this->password) {
				$model->password_hash = Helper::genPassword($this->password) ;
			}
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
	
}