<?php
session_start();
include('connection.php');
$mail =  $_SESSION['email'];


 header("location:login.php");

?>