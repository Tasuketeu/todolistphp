<?php

class Login{
  public function checkAuth(&$username,&$password,&$msg){
    Request::checkUserAuthData($username,$password,$msg);
  }
}
?>
