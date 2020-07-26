<?php 


Request::doPagination($result1,$pagesCount);

if(isset($_GET['page'])){
  $cur_page=$_GET['page'];
}
else{
  $cur_page=1;
}

if(isset($_POST['addTask'])){
    Root::checkTaskForm($name,$email,$task,$_POST['status1']);
}

if((empty($_COOKIE['user'])&&empty($_COOKIE['admin']))&&(isset($_GET['del_task'])||
  isset($_GET['edit_task'])||isset($_POST['editedTask'])||isset($_POST["status"]))){
  header('location: login.php');
}

if(isset($_COOKIE['user'])||isset($_COOKIE['admin'])){

if(isset($_GET['del_task'])){
    Request::deleteTask($_GET['del_task']);  
}
if(isset($_GET['edit_task'])){
    Request::onPressEditButton();
}
if(isset($_POST['editedTask'])){

    if($_COOKIE['task1']!=$_POST['editedTask'])
    {
    Request::editTask($_POST['editedTask']);
    }
}
if(isset($_POST["status"])){
    Request::editStatus($_POST['status']);
}
if(isset($_POST['editedTask'])&&isset($_POST['status'])&&(isset($_COOKIE['task1']))){
    if($_COOKIE['task1']!=$_POST['editedTask'])
    {
    Request::editTaskAndStatus($_POST['editedTask'],$_POST['status']);
    }
    else{
      Request::editStatus($_POST['status']);
    }
}

}

if(isset($_GET['sort'])){
    $sort=$_GET['sort'];
}
else{
  $sort='ASC';
}

function sorting($sort){
$sort=='DESC'? $sort='ASC' : $sort='DESC';
return $sort;
}

  if(isset($_GET['order']))
  {
     $order=$_GET['order']; 
  } 
  else{$order='id'; $sort='ASC';} 

?>
<!DOCTYPE html>
<html>
<head>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>
		tesz1115
	</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="static/css/index.css">
</head>
<body>

  <nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
    <div class="container">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">

                <?php if(empty($_COOKIE['user'])&&empty($_COOKIE['admin']))
                {
                echo "<li class=\"nav-item\">
                  <a class=\"nav-link\" href=\"register.php\">Зарегистрироваться</a>
                </li>
                <li class=\"nav-item\">
                  <a class=\"nav-link\" href=\"login.php\">Войти в аккаунт</a>
                </li>";
              } ?>
                <?php if(isset($_COOKIE['user'])||isset($_COOKIE['admin']))
                {
                  isset($_COOKIE['user']) ? $uname = $_COOKIE['user'] : $uname=$_COOKIE['admin'];
                  echo "<li class=\"nav-item\"><a class=\"nav-link\">$uname</a></li>
                        <li class=\"nav-item\"><a class=\"nav-link\" href=\"logout.php\">Выйти из аккаунта</a> </li>
                  ";
                } ?>

        </ul>
      </div>
    </div>
  </nav>

<div class="content-width">
      <div class="container jumbotron">


        <div class="row justify-content-center">
          <form method="POST" action="index.php">
            <?php if(isset($_COOKIE['gmsg'])){?> <div class="alert alert-success" role="alert"> <?php echo $_COOKIE['gmsg']; ?> </div> <?php } ?>
            <?php if(isset($_COOKIE['bmsg'])){?> <div class="alert alert-danger" role="alert"> <?php echo $_COOKIE['bmsg']; ?> </div> <?php } ?>

            <div class="form-group">
    <p class="text-center">Приложение-задачник</p>
  </div>
                          <div class="form-group">
    <label for="exampleInputUserName1">Имя пользователя</label>
    <input type="text" name="username" class="form-control" id="exampleInputUserName1" required>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">E-mail</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>
  </div>
			<div class="form-group">
			<label for="InputTask1">Задача</label>
            <input type="text" name="task" class="form-control" id="InputTask1" required/>
        </div>
        <div class="form-group">
        <label for="exampleFormControlSelect1">Статус</label>
        <select class="form-control" id="exampleFormControlSelect1" name="status1">
          <option value="0">
            Выберите статус:
          </option>
          <option value="1">
              не начато
          </option> 
          <option value="2">
              выполняется
          </option> 
        </select>
      </div>
            <button type="submit" class="btn btn-primary" name="addTask">Добавить задачу</button>
          </form>
        </div>

        <div class="row">
          <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">№</th>
                <th scope="col"><a href="?page=<?php echo $cur_page; ?>&&order=name&&sort=<?php $sort1=sorting($sort); echo $sort1; ?>">Имя пользователя</a></th>
                <th scope="col"><a href="?page=<?php echo $cur_page; ?>&&order=email&&sort=<?php $sort1=sorting($sort); echo $sort1; ?>">E-mail</a></th>
                <th scope="col">Задача</th>
                <th scope="col"><a href="?page=<?php echo $cur_page; ?>&&order=status&&sort=<?php $sort1=sorting($sort); echo $sort1; ?>">Статус</a></th>
                <th scope="col">Удаление</th>
                <th scope="col">Редактирование</th>
              </tr>
            </thead>
            <tbody>
            	<?php $i = 1; while ($row = mysqli_fetch_array($result1)){ ?>
				<tr>
                <th scope="row"><?php echo $i; ?></th>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>

               <?php
                $task_row = $row['task'];
                $_COOKIE['task1']=$row['task'];
                $task_table_row = "<td>$task_row</td>";

                if(isset($_GET['edit_task']) && (isset($_COOKIE['user'])||isset($_COOKIE['admin'])))
                {
                  $editId=$row['id'];

                  $form ="<td>
                    <form method=\"POST\" action=\"index.php\">
                    <div class=\"form-group\">
                    <input type=\"text\" name=\"editedTask\" class=\"form-control\" id=\"InputTask1\" value=\"$task_row\" required/>
                    </div>
                    <button class=\"btn btn-primary\" type=\"submit\">Завершить редактирование</button>
                    </form>
                  </td>";

                  if(isset($_COOKIE['user'])){

                  if($_COOKIE['user']==$row['name'] and $editId==$_GET['edit_task']){
                  echo $form;
                    }
                    else{
                  echo $task_table_row;
                        }
                  }

                    if(isset($_COOKIE['admin'])){
                    if($editId==$_GET['edit_task']){
                        echo $form;
                    }
                    else{
                    echo $task_table_row;
                        }
                  }
                }
                else{
                  echo $task_table_row;
                }
                ?>
                <?php 
                  $status_name = $row['status_name'];
                  $td_open="<td>";
                  $status_row="<p>$status_name</p>";
                  $redactedByAdmin="<p>отредактировано администратором</p>";
                  $td_close="</td>";
                  if(isset($_GET['edit_task']) && (isset($_COOKIE['user'])||isset($_COOKIE['admin'])))
                  {
                  $form1 ="<td>
                    <form method=\"POST\" action=\"index.php\">
                    <select class=\"form-control\" name=\"status\" id=\"status\">
                    <option value=\"0\">
                      Статус
                    </option>
                    <option value=\"1\">
                        не начато
                    </option> 
                    <option value=\"2\">
                        выполняется
                    </option>
                    <option value=\"3\">
                                выполнено
                    </option> 
                    <option value=\"4\">
                                отложено
                    </option>  
                    </select>
                    </form>
                  </td>";

                  if(isset($_COOKIE['user'])){

                  if($_COOKIE['user']==$row['name'] and $editId==$_GET['edit_task']){
                  echo $form1;
                    }
                    else{
                      echo $td_open;
                  echo $status_name;
                  if($row['admin_status']>0){
                  echo $redactedByAdmin;
                  }
                  echo $td_close;
                }
                    }
                    if(isset($_COOKIE['admin'])){
                    if($editId==$_GET['edit_task']){
                        echo $form1;
                    }
                    else{
                      echo $td_open;
                  echo $status_name;
                  if($row['admin_status']>0){
                  echo $redactedByAdmin;
                  }
                  echo $td_close;
                  }
                    }
                  }
                  else{
                    echo $td_open;
                  echo $status_name;
                  if($row['admin_status']>0){
                  echo $redactedByAdmin;
                  }
                  echo $td_close;
                  }

                 ?>
                <td class="delete">
                  <?php
                  		
                  		$dontDelete = "<a class=\"btn btn-danger\" href=\"index.php\">x</a>";
                  		if(isset($_COOKIE['user'])||isset($_COOKIE['admin']))
                  		{
                        $row_id=$row['id'];
                        $delete = "<a class=\"btn btn-danger\" href=\"index.php?page=$cur_page&&del_task=$row_id\">x</a>";
                  		}

                      if(isset($_COOKIE['user']))
                      {
                        if($_COOKIE['user']==$row['name'])
                        {
                        echo $delete;
                        }
                        else{
                      echo $dontDelete;
                      }
                      }

                      if(isset($_COOKIE['admin'])){
                        echo $delete;
                      }
                      if(empty($_COOKIE['user'])&&(empty($_COOKIE['admin']))){
                      echo $dontDelete;
                      }
                   ?>
                </td>
                <td class="edit">
                  <?php
                      
                      $dontEdit = "<a class=\"btn btn-warning\" href=\"index.php\">!!!!</a>";
                      if(isset($_COOKIE['user'])||isset($_COOKIE['admin']))
                      {
                        $row_id=$row['id'];
                        $row_name=$row['name'];
                        $row_status=$row['status'];
                        $row_task=$row['task'];
                        $edit = "<a class=\"btn btn-warning\" href=\"index.php?page=$cur_page&&edit_task=$row_id&&editor_name=$row_name&&edit_status=$row_status&&tasks=$row_task&&order=$order&&sort=$sort\">!!!!</a>";
                      }

                      if(isset($_COOKIE['user']))
                      {
                        if($_COOKIE['user']==$row['name'])
                        {
                        echo $edit;
                        }
                        else{
                      echo $dontEdit;
                      }
                      }

                      if(isset($_COOKIE['admin'])){
                        echo $edit;
                      }
                      if(empty($_COOKIE['user'])&&(empty($_COOKIE['admin']))){
                      echo $dontEdit;
                      }
                   ?>
                  
                </td>
              </tr>

            	<?php  $i++; } ?>
              
            </tbody>
          </table>
          </div>
        </div>
        <div class="row justify-content-end">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php
                for($k=1; $k<=$pagesCount;$k++){  ?>
    <?php echo "<li name=\"page\" class=\"page-item\"><a class=\"page-link\" href=\"?page=$k&&order=$order&&sort=$sort\">$k</a></li>"; ?>
  <?php } ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>

<script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<script type="text/javascript">
  $(document).ready(function(){
  $('#status').on('change', function() {
    var status_id = $(this).val();
    if(status_id){
      $.ajax({
        type:'POST',
        data: {
      "status" : status_id
       },
      success: function(data){
      console.log(data)
      }
      });
    }
  })
});
</script>
</body>

<?php include('Footer.php'); ?>

</html>