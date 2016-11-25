<?php
session_start();
if (isset($_SESSION["user"]) && !empty($_SESSION["user"]))
{
$user= $_SESSION["user"];
} else {
header("Location: index.php");
}

?>	