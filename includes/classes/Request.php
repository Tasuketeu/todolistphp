<?php
class Request
{
    private function con()
  	{
      $db = mysqli_connect('localhost','root','','todo') or die(mysqli_error($db));
      return $db;
  	}

  	public function addTask(&$name,&$email,&$task,&$status)
  {
    $db = Request::con();
    $task = htmlspecialchars($_POST['task']);
    if(isset($_COOKIE['user'])||isset($_COOKIE['admin'])){
    isset($_COOKIE['user']) ? $name = $_COOKIE['user'] : $name = $_COOKIE['admin'];

    $resultSelect = $db->prepare("SELECT email FROM users WHERE name=(?)");
    $resultSelect->bind_param("s",$name);
    $resultSelect->execute();
    $result=$resultSelect->get_result();
    $email=mysqli_fetch_array($result) or die(mysqli_error($db));

    $resultSelect = $db->prepare("INSERT INTO tasks (name,email,task,status) VALUES (?,?,?,?)");
    $resultSelect->bind_param("sssi",$name,$email[0],$task,$status);
    }
    else{
    $name = $_POST['username'];
    $email = $_POST['email'];
    $resultSelect = $db->prepare("INSERT INTO tasks (name,email,task,status) VALUES (?,?,?,?)");
    $resultSelect->bind_param("sssi",$name,$email,$task,$status);
    }
    $resultSelect->execute();
    $result=$resultSelect->get_result();
    setcookie("gmsg",'Добавление задачи прошло успешно!',time()+60,"/");
    header('location:index.php?page=1');
  }

  public function deleteTask($id){
    $db = Request::con();
    $id=$_GET['del_task'];
    $resultSelect = $db->prepare("DELETE FROM tasks WHERE id=$id");
    $resultSelect->bind_param("i",$id);
    $resultSelect->execute();
    header('location:index.php?page=1');
  }
    public function onPressEditButton(){
          setcookie('editedTaskId',$_GET['edit_task'],time()+3600,"/");
          setcookie('usname',$_GET['editor_name'],time()+3600,"/");
          setcookie('edit_status',$_GET['edit_status'],time()+3600,"/");
          setcookie('task1',$_GET['tasks'],time()+3600*24,"/");
          setcookie('page1',$_GET['page'],time()+3600*24,"/");
    }

    public function editTaskAndStatus($editedTask,$editedStatusId){
    $uname=$_COOKIE['usname'];
    $db = Request::con();
    $editedTaskId=$_COOKIE['editedTaskId'];

    if(isset($_COOKIE['user'])){
      $resultSelect = $db->prepare("UPDATE tasks SET task=(?),status=(?) WHERE name=(?) AND id=(?)");
    }
    else if(isset($_COOKIE['admin']))
    {
      $resultSelect = $db->prepare("UPDATE tasks SET task=(?),status=(?), admin_status=1 WHERE name=(?) AND id=(?)");
    }
      $resultSelect->bind_param("sisi",$editedTask,$editedStatusId,$uname,$editedTaskId);
      $resultSelect->execute();
    }

    public function editTask($editedTask){
          $db = Request::con();
          $uname=$_COOKIE['usname'];
          $editedTaskId=$_COOKIE['editedTaskId'];

          if(isset($_COOKIE['user'])){
          $resultSelect = $db->prepare("UPDATE tasks SET task=(?) WHERE name=(?) AND id=(?)");
          }
          else if(isset($_COOKIE['admin']))
          {
          $resultSelect = $db->prepare("UPDATE tasks SET task=(?), admin_status=1 WHERE name=(?) AND id=(?)");
          }
          $resultSelect->bind_param("ssi",$editedTask,$uname,$editedTaskId);
          $resultSelect->execute();
    }

    public function editStatus($editedStatusId){

          $db = Request::con();
          $uname=$_COOKIE['usname'];
          $editedTaskId=$_COOKIE['editedTaskId'];

          $resultSelect = $db->prepare("UPDATE tasks SET status=(?) WHERE name=(?) AND id=(?)");
          $resultSelect->bind_param("isi",$editedStatusId,$uname,$editedTaskId);
          $resultSelect->execute();
    }

  public function doPagination(&$result1,&$pagesCount){
  $db=Request::con();
  if(isset($_COOKIE['page1'])){
    $page=$_COOKIE['page1'];
  }
  if(isset($_GET['page'])){
    $page = $_GET['page'];
    setcookie('page1','',time()-3600*24,"/");
  }
  if(empty($_GET['page'])&&empty($_COOKIE['page1'])){
    $page = 1;
  }
  $tasksOnPage = 3;
  $fromTask = ($page -1) * $tasksOnPage;

  if(isset($_GET['order'])){
    $order = $_GET['order'];
    $sort = $_GET['sort'];
    if(($order=='id'||$order=='name'||$order=='email'||$order=='status')&&($sort=='ASC'||$sort=='DESC'))
    {
    $query = "SELECT * FROM tasks,statuses WHERE id>0 AND status=status_id ORDER BY $order $sort LIMIT $fromTask,$tasksOnPage";
    $result1 = mysqli_query($db,$query) or die(mysqli_error($db));
    }
    
  }
  else{
      $query = "SELECT * FROM tasks,statuses WHERE id>0 AND status=status_id ORDER BY id ASC LIMIT $fromTask,$tasksOnPage";
      $result1 = mysqli_query($db,$query) or die(mysqli_error($db));
  }

  $query = "SELECT COUNT(*) as count FROM tasks";
  $result2 = mysqli_query($db,$query) or die(mysqli_error($db));
  $count = mysqli_fetch_assoc($result2)['count'];
  $pagesCount = ceil($count/$tasksOnPage);
  }

    public function isUserExisted(&$username,&$password,&$email,&$msg){

    $db=Request::con();

    $resultSelect = $db->prepare("SELECT * FROM users WHERE name=(?) and email=(?)");
    $resultSelect->bind_param("ss",$username,$email);
    $resultSelect->execute();
    $result=$resultSelect->get_result();
    $count = mysqli_num_rows($result);

    if($count==1)
    {
      $msg['bmsg']="Ошибка! Такой пользователь уже существует!";
    }
    else{
      Request::register($db,$username,$password,$email,$msg);
    }
    }

    private function register($db,&$username,&$password,&$email,&$msg){
    
    $resultSelect = $db->prepare("INSERT INTO users(name,email,password) VALUES (?,?,?)");
    $resultSelect->bind_param("sss",$username,$email,$password);
    $resultSelect->execute();
    $msg['gmsg']="Вы успешно зарегистрировались!";
}
  
  public function checkUserAuthData($username,$password,&$msg){

    $db=Request::con();
    
    $resultSelect = $db->prepare("SELECT * FROM users WHERE name=(?) and password=(?)");
    $resultSelect->bind_param("ss",$username,$password);
    $resultSelect->execute();
    $result=$resultSelect->get_result();
    $count = mysqli_num_rows($result);
    if($count==1){
      Request::login($result,$username,$password,$msg);
    }
    else{
      $msg['bmsg']="Ошибка! Неправильные реквизиты доступа";
    }
  }

	private function login($result,$username,$password,&$msg){

    $row = mysqli_fetch_array($result);

    if($row['adminMode']==0){
      setcookie('user',$username,time()+3600,"/");
    }
    else{
      setcookie('admin',$username,time()+3600,"/");
    }
      $msg['gmsg']="Вы успешно вошли в систему!";
  	}

  	public function logout(){
	if(isset($_COOKIE['user']))
	{
    setcookie("user",$_COOKIE['user'],time()-3600,"/");
	}
	if(isset($_COOKIE['admin']))
	{
    setcookie("admin",$_COOKIE['admin'],time()-3600,"/");
	}

    header('location: index.php?page=1');
	}

}
?>
