<?php
if(isset($_POST['username1']) and isset($_POST['password1']) and empty($_COOKIE['user']) and empty($_COOKIE['admin'])){
      Login::checkAuth($_POST['username1'],$_POST['password1'],$msg);
  }
  if(isset($_COOKIE['user'])||isset($_COOKIE['admin'])){
    header('location: index.php');
  }
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
  <link rel="stylesheet" type="text/css" href="./static/css/index.css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
    <div class="container">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
        	<?php if(isset($msg['gmsg'])){?> 
        		<li class="nav-item"><a class="nav-link" href="logout.php">Выйти из аккаунта</a> </li>
        	<?php } ?>
            		<?php if(isset($msg['bmsg'])||(empty($msg['bmsg'])&&empty($msg['gmsg']))){?> 
            			<li class="nav-item">
                  			<a class="nav-link" href="register.php">Зарегистрироваться</a>
                		</li>
            		<?php }  ?>

                
                <li class="nav-item">
                  <a class="nav-link" href="index.php?page=1">Вернуться назад</a>
                </li>

        </ul>
      </div>
    </div>
  </nav>

<div class="content-width">
      <div class="container jumbotron">


        <div class="row justify-content-center">


        <div class="row">
          <form method="POST" action="login.php">
            <?php if(isset($msg['gmsg'])){?> <div class="alert alert-success" role="alert"> <?php echo $msg['gmsg']; ?> </div> <?php } ?>
            <?php if(isset($msg['bmsg'])){?> <div class="alert alert-danger" role="alert"> <?php echo $msg['bmsg']; ?> </div> <?php }  ?>
              <div class="form-group">
    <label for="exampleInputUserName1">Имя пользователя</label>
    <input type="text" name="username1" class="form-control" id="exampleInputUserName1" required>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Пароль</label>
    <input type="password" name="password1" class="form-control" id="exampleInputPassword1" required>
  </div>
  <button class="btn btn-primary" type="submit">Залогиниться</button>

          </form>
        </div>

        
        </div>
      </div>
      </div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

<?php include('Footer.php'); ?>
</html>