<?php
include("../connection.php");
session_start();
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
  <link rel="stylesheet" href="../admin/style.css">
  <script type="text/javascript" src="script.js"></script>
 <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="styles.css">

   <!-- Font Awesome -->
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <title>Trader Dashboard</title>
</head>

<body>


<nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; <b> Trader Menu </b></button>
    </div>
  <i class='bx bxs-user'></i>Trader | Product Lists
  </nav>
  <div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  
   <a href="trader.php" class="">Dashboard</a>
         <a href="EditProfile.php">Profile</a>
          <a href="shop.php">Shops List</a>
          <a href="addShop.php">Add Shops</a>
          <a href="productlist.php">Products List</a>
          <a href="add-new.php">Add Products</a>
          <a href="order.php">Orders</a>
          <li><a href="http://localhost:8080/apex/f?p=105:LOGIN_DESKTOP:2667570840115::::::">Report</a></li>
          <a href="../logout.php" class="logout-btn">Logout</a>

</div>

    <table class="table table-hover text-center">
      <thead class="table-dark">
      <tr>
        <th scope="col">Image</th>
        <th scope="col">Product id</th>
          <th scope="col">Product Name</th>
          <th scope="col">Product Price</th>
          <th scope="col">Product Category</th>
          <th scope="col">Stock</th>
          <th scope="col">Update</th>
          <th scope="col">Delete</th>
        </tr>
      </thead>
      <tbody>
       
      <?php

        if(isset($_GET['shop_id'])){
          $sql = "SELECT * FROM PRODUCT WHERE FK2_SHOP_ID = :shopid";
          $stid = oci_parse($conn,$sql);
          oci_bind_by_name($stid, ':shopid', $_GET['shop_id']);
        }
        else{

       
          $sql = "SELECT p.*
          FROM PRODUCT p
          JOIN TRADER t ON t.TRADER_CATEGORY = p.PRODUCT_CATEGORY
          WHERE t.TRADER_ID = :trader_id";

          $stid = oci_parse($conn,$sql);
          oci_bind_by_name($stid, ':trader_id', $_SESSION['trader_id']);
        }
        

        oci_execute($stid);
        while($row = oci_fetch_array($stid,OCI_ASSOC)){
            $pid=$row['PRODUCT_ID'];
        //    $_SESSION['email'] = $row['TRADER_EMAIL'];
           
            echo "<tr>";
                echo "<th scope='col'>";
                // echo "<div class='image'";
                echo "<img src=\"../upload/products/".$row['PRODUCT_IMG']."\" alt=".$row['PRODUCT_NAME']." </th>"; 
                // echo "</div>";
                echo "<th scope='col'>".$row['PRODUCT_ID']."</th>
                <th scope='col'>".$row['PRODUCT_NAME']."</th>
                <th scope='col'>&pound; ".$row['PRODUCT_PRICE']."</th>
                <th scope='col'>".$row['PRODUCT_CATEGORY']."</th>
                <th scope='col'>".$row['PRODUCT_INSTOCK']."</th>";
            
               
                      
                echo " <th scope='col'><a href='updateproduct.php?cat=EditProduct&id=$pid&action=edit' id='edit'>Update</a></th>";
                echo "<th scope='col'> <a href='delete.php?&id=$pid&action=product' id='delete' >Delete</a></th>";
                echo "</th>";
            echo "</tr>";
        }
      ?>
      
        
      </tbody>
    </table>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>