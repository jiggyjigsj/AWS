<?php 
session_start();
include '../password.php';

if (isset($_SESSION["user"]) && !empty($_SESSION["user"]))
{
header("Location: gallery.php");
} else {
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, scale-to-fit=no">
    <style>

</style>
  </head>
  <body>
<div class="background">
<div class="transbox">
    <div class="jumbotron text-center">
    <h1>Login to view Gallery</h1> 
    <h4> Two Users: Username: (admin user) admin Password admin ; jpatel74@hawk.iit.edu : 123456 ; controller : password ;
<center>
<form class="form-inline" action="#" method="post">
Username:</span> <input type="text" class="form-control" name="user" size "25"><br>
Password: <input type="password" class="form-control" name="password" size "25"><br></span>
<input class="btn btn-danger" type="submit" value="Submit">

</form></center>
<?php
if(isset($_POST['user']) && $_POST['user'] != '' && isset($_POST['password']) && $_POST['password'] != '')
{
  $mysqli = new mysqli($_SESSION["hostname"],$username,$password,"app");

  $usr = $_POST['user'];
  $pass = $_POST['password'];
  $chk_usr = "SELECT * FROM users WHERE username='$usr' AND password='$pass'";
  $result = $mysqli->query($chk_usr);
  $num = $result->num_rows;
  if($num == '1') {
    $_SESSION["user"] = $usr;
    header("Location: gallery.php");

  } else {
  echo '<span style="color: #f00;">Wrong Username or Password Try Again</span>';
  }


$mysqli->close();
}
}
?>
 </div></div></div>
</body>
</html>
