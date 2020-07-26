<?php

class Root {
	public function checkTaskForm(&$name,&$email,&$task,&$status)
	{
		if($status==0){
			setcookie("bmsg",'Необходимо выбрать статус задачи!',time()+60,"/");
			setcookie("gmsg",'',time()-60,"/");
		}
		else{
			Request::addTask($name,$email,$task,$status);
		}
	}

}
?>