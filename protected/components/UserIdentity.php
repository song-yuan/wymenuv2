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
		if(!$user = User::model()->find('username=:username and status=1',array(':username' => $this->username))) {
			$field = 'username';
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}elseif(!$user = User::model()->find('username=:username and password_hash=:password and status=1',array(':username'=>$this->username,':password'=>Helper::genPassword($this->password)))) {
			$field = 'password';
			$this->errorCode =  self::ERROR_PASSWORD_INVALID;
		}else {

			$comps = Yii::app()->db->createCommand('select * from nb_company where delete_flag = 0 and  dpid ='.$user->dpid)->queryRow();
			//var_dump($companyId);var_dump($role);
			if($comps){

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
		//var_dump($user);exit;
		return array('field' =>$field , 'status' => $this->errorCode);
	}
}