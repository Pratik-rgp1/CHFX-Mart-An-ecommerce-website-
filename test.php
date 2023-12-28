
<?php
session_start();
include('connection.php');
$cartid=$_SESSION['cust_id'];
echo $cartid;
?>