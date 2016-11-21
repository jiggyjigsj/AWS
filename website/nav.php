<?php include 'check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/bootstrap.css">
</head>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
	<ul class="nav navbar-nav">
		<li><a class="navbar-brand" href="">Welcome <?php echo $username ?></a></li>
		<li><a href="gallery.php">Gallery</a></li>
		<li><a href="settings.php">Settings</a></li>
		<li><a href="logout.php">Logout <?php echo $username ?></a></li>
	</ul>
</div>
</nav>
<html>