<?php
session_start();
include "db_conn.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="styles.css">
  <script type="text/javascript" src="script.js"></script>

  <title>Trader Dashboard</title>
</head>

<body>

<nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; <b> Trader Menu </b></button>
    </div>
  <i class='bx bxs-user'></i>Trader | Orders
  </nav>
  <div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  
   <a href="trader.php" class="">Dashboard</a>
         <a href="EditProfile.php">Profile</a>
         <a href="addShop.php">Add Shops</a>
          <a href="shop.php">Shops List</a>
          <a href="add-new.php">Add Products</a>
          <a href="productlist.php">Products List</a>
          <a href="order.php">Orders</a>
          <li><a href="http://localhost:8080/apex/f?p=105:LOGIN_DESKTOP:2667570840115::::::">Report</a></li>
          <a href="../logout.php" class="logout-btn">Logout</a>

</div>

  <div class="container">
   

    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; Trader Menu</button>
    </div>

    <h3> Orders Made: </h3>

    <table class="table table-hover text-center">
      <thead class="table-dark">
        <tr>
        <th scope="col">Order ID</th>
        <th scope="col">Customer Name</th>
          <th scope="col">Products Ordered</th>
          <th scope="col">Order Quantity</th>
          <th scope="col">Price</th>
        </tr>
      </thead>
      <tbody>
      <?php
         $sql = "SELECT op.*,p.*
         FROM PRODUCT_ORDER op 
         JOIN PRODUCT p ON op.PRODUCT_ID = p.PRODUCT_ID
         JOIN SHOP s ON p.FK2_SHOP_ID = s.SHOP_ID
         JOIN TRADER tr ON s.FK1_TRADER_ID = tr.TRADER_ID
         WHERE  tr.TRADER_ID = :trader_id";

        $result = oci_parse($conn, $sql);
        oci_bind_by_name($result ,":trader_id",$_SESSION['trader_id']);
        oci_execute($result);
        while ($row = oci_fetch_assoc($result)) {
          $order_id = $row['ORDER_ID'];
          $sql1 = "SELECT c.* 
          FROM CUSTOMER c 
          JOIN ORDERS o ON o.FK1_CUST_ID = c.CUST_ID
          WHERE o.ORDER_ID = :order_id ";

          $stid = oci_parse($conn,$sql1);
          oci_bind_by_name($stid,":order_id", $order_id);
          oci_execute($stid);
          $data=oci_fetch_array($stid);
          $user_name = $data['FIRST_NAME']. " ".$data['LAST_NAME'];
          
        
        ?>
          <tr>
            <td><?php echo $row["ORDER_ID"]; ?></td>
            <td><?php echo $user_name; ?></td>
            <td><?php echo ucfirst($row['PRODUCT_NAME']) ;?></td>
            <td><?php echo $row["ORDER_QUANTITY"]; ?></td>
            <td>&pound;<?php echo $row["ORDER_QUANTITY"] * $row['PRODUCT_PRICE']; ?></td>           
          </tr>
        <?php
            }
           
        ?>

      </tbody>
    </table>

  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>