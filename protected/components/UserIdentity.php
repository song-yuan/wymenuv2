<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	public $role = 0 ;
	public $mobile = '';
	public $companyId = 0;
	public $email = '';
	public $staffNo = 0;
	public $status = 0;
	public $userId = 0;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = $field = '';
		$user = User::model()->find('username=:username and password_hash=:password and status=1 and role < 15 and delete_flag=0',array(':username'=>$this->username,':password'=>Helper::genPassword($this->password)));
		if(!$user){
			$user = User::model()->find('username=:username and status=1 and role < 15 and delete_flag=0',array(':username' => $this->username));
			if(!$user){
				$field = 'username';
				$this->errorCode = self::ERROR_USERNAME_INVALID;
			}else{
				$field = 'password';
				$this->errorCode =  self::ERROR_PASSWORD_INVALID;
			}
		}else{
			$company = Company::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$user->dpid));
			if($company){
				$this->userId = $user->lid.'_'.$user->dpid ;
				$this->role = $user->role ;
				$this->mobile = $user->mobile;
				$this->companyId = $user->dpid;
				$this->email = $user->email;
				$this->staffNo = $user->staff_no;
				$this->status = $user->status;
				$this->errorCode =  self::ERROR_NONE;
			}else{
				$field = 'username';
				$this->errorCode =  self::ERROR_USERNAME_INVALID;
			}
		}
		return array('field' =>$field , 'status' => $this->errorCode);
	}
}