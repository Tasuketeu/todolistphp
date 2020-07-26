<?php

class Register {

	public function checkUser(&$username,&$password,&$email,&$msg)
	{
		Request::isUserExisted($username,$password,$email,$msg);
	}
}
?>