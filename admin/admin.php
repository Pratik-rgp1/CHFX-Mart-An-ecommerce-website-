<?php
session_start();
include("../connection.php");

if(!$_SESSION['admin_id']){
  header('location:../login.php');
}

//  total number of users
$queryUsers = "SELECT COUNT(*) as total_users FROM CUSTOMER";
$stmtUsers = oci_parse($conn, $queryUsers);
oci_execute($stmtUsers);
$rowUsers = oci_fetch_assoc($stmtUsers);
$totalUsers = $rowUsers['TOTAL_USERS'];

//  total number of products
$queryProducts = "SELECT COUNT(*) as total_products FROM PRODUCT";
$stmtProducts = oci_parse($conn, $queryProducts);
oci_execute($stmtProducts);
$rowProducts = oci_fetch_assoc($stmtProducts);
$totalProducts = $rowProducts['TOTAL_PRODUCTS'];

//  total number of orders
$queryOrders = "SELECT COUNT(*) as total_orders FROM PRODUCT_ORDER";
$stmtOrders = oci_parse($conn, $queryOrders);
oci_execute($stmtOrders);
$rowOrders = oci_fetch_assoc($stmtOrders);
$totalOrders = $rowOrders['TOTAL_ORDERS'];


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admins.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img src="../images/CHFXLogo.png" alt="Logo">
                <h2>Admin Dashboard</h2>
            </div>
            <ul class="menu">
                <li><a href="admin.php" class="">Dashboard</a></li>
                <li><a href="traderlist.php">Traders</a></li>
                <li><a href="shoplist.php">Shops</a></li>
                <li><a href="customerlist.php">Users</a></li>
                <li><a href="productlist.php">Products</a></li>
                <li><a href="order.php">Orders</a></li>
                <li><a href="review.php">Review</a></li>
                <li><a href="http://localhost:8080/apex/f?p=105:LOGIN_DESKTOP:2667570840115::::::">Report</a></li>
                <li><a href="../logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </div>
        <div class="main-content" >
            <h1>Dashboard</h1>
            <p>Welcome back, Admin!</p>
            <div class="cards">

                 <div class="card"  onclick="window.location.href='customerlist.php'">
                    <h3>Total Users</h3> 
                    <!-- <p>100</p> -->
                     <p><?php echo $totalUsers; ?></p>
                </div> 

                <div class="card" onclick="window.location.href='productlist.php'">
                    <h3>Total Products</h3>
                    <!-- <p>50</p> -->
                    <p><?php echo $totalProducts; ?></p>
                </div>

                <div class="card" onclick="window.location.href='order.php'">
                    <h3>Total Orders</h3>
                    <!-- <p>200</p> -->
                    <p><?php echo $totalOrders; ?></p>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
