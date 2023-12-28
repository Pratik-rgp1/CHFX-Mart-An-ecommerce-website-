<?php
  session_start();
  include "db_conn.php";

  $trader_id = $_SESSION['trader_id'];
  $sql = "SELECT * FROM TRADER WHERE TRADER_ID = '$trader_id'";
  $stid = oci_parse($conn, $sql);
  oci_execute($stid);
  $data = oci_fetch_array($stid);
  $category = $data['TRADER_CATEGORY'];

//  total number of shop
$verified = 'verified';
$sql = "SELECT COUNT(*) as shop_total FROM SHOP WHERE FK1_TRADER_ID=:id AND STATUS = :verified";

$stida = oci_parse($conn, $sql);
oci_bind_by_name($stida, ':id', $_SESSION['trader_id']);
oci_bind_by_name($stida, ':verified', $verified);

oci_execute($stida);
$sh = oci_fetch_assoc($stida);
$totalUsers = $sh['SHOP_TOTAL'];

// echo $uid;

$queryProducts = "SELECT COUNT(*) as total_products FROM PRODUCT WHERE PRODUCT_CATEGORY= :category";
$stmtProducts = oci_parse($conn, $queryProducts);
oci_bind_by_name($stmtProducts, ':category',  $category);
oci_execute($stmtProducts);
$rowProducts = oci_fetch_assoc($stmtProducts);
$totalProducts = $rowProducts['TOTAL_PRODUCTS'];

//  total number of orders

         $sql = "SELECT op.*,p.*
         FROM PRODUCT_ORDER op 
         JOIN PRODUCT p ON op.PRODUCT_ID = p.PRODUCT_ID
         JOIN SHOP s ON p.FK2_SHOP_ID = s.SHOP_ID
         JOIN TRADER tr ON s.FK1_TRADER_ID = tr.TRADER_ID
         WHERE  tr.TRADER_ID = :trader_id";

        $result = oci_parse($conn, $sql);
        oci_bind_by_name($result ,":trader_id",$_SESSION['trader_id']);
        oci_execute($result);
        $Count=0;
        while ($row = oci_fetch_assoc($result)) {
            $Count=$Count+1;
        }
      
        

  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Dashboard</title>
    <link rel="stylesheet" href="../admin/admins.css">
</head>
<body>
  <div class="container">
      <div class="sidebar">
        <div class="logo">
          <img src="../images/CHFXLogo.png" alt="Logo">
          <h2>Trader Dashboard</h2>
        </div>
        <ul class="menu">
          <li><a href="trader.php" class="">Dashboard</a></li>
          <li><a href="EditProfile.php">Profile</a></li>
          <li><a href="addShop.php">Add Shops</a></li>
          <li><a href="shop.php">Shops List</a></li>
          <li><a href="add-new.php">Add Products</a></li>
          <li><a href="productlist.php">Products List</a></li>
          <li><a href="order.php">Orders</a></li>
          <li><a href="http://localhost:8080/apex/f?p=105:LOGIN_DESKTOP:2667570840115::::::">Report</a></li>
          <li><a href="../logout.php" class="logout-btn">Logout</a></li>
        </ul>
      </div>

        <div class="main-content" >
            <h1>Dashboard</h1>
            <p>Welcome back, <?php echo $data['FIRST_NAME'];?>!</p>
            <div class="cards">

                 <div class="card"  onclick="window.location.href='shop.php'">
                    <h3>Total Shop</h3> 
                   
                     <p><?php echo $totalUsers; ?></p>
                </div> 

                <div class="card" onclick="window.location.href='productlist.php'">
                    <h3>Total Products</h3>
                   
                    <p><?php echo $totalProducts; ?></p>
                </div>

                <div class="card" onclick="window.location.href='order.php'">
                    <h3>Total Orders</h3><!-- <p>200</p> -->
                    <p><?php echo $Count; ?></p>
                </div>
            </div>
        
      </div>
    </div>
</body>
</html>