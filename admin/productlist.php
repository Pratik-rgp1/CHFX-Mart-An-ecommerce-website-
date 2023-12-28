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
 <!-- Bootstrap CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
   <link rel="stylesheet" href="../Trader/styles.css">
  <script type="text/javascript" src="../Trader/script.js"></script>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <link rel="stylesheet" href="style.css">

  <style>
    #imgs{
      width:120px;
      height:100px
    }
  </style>
  <title>Admin  Dashboard</title>
</head>

<body>
<nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; <b> Admin  Menu </b></button>
    </div>
  <i class='bx bxs-user'></i>Admin | Product List
  </nav>
  <div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  
  <a href="admin.php" class="">Dashboard</a>
          <a href="traderlist.php">Traders</a>
          <a href="shoplist.php">Shops</a>
          <a href="customerlist.php">Users</a>
          <a href="productlist.php">Products</a>
          <a href="order.php">Orders</a>
          <a href="review.php">Review</a>
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
          
        </tr>
      </thead>
      <tbody>
       
      <?php

        $sql = "SELECT * FROM PRODUCT";
        $stid = oci_parse($conn,$sql);
        oci_execute($stid);
        while($row = oci_fetch_array($stid,OCI_ASSOC)){
            
            echo "<tr>";
                echo "<th scope='col'>";
                // echo "<div class='image'";
                
                echo "<img id='imgs' src=\"../upload/products/".$row['PRODUCT_IMG']."\" alt=".$row['PRODUCT_NAME']." ></th>"; 
                // echo "</div>";
                echo "<th scope='col'>".$row['PRODUCT_ID']."</th>
                <th scope='col'>".$row['PRODUCT_NAME']."</th>
                <th scope='col'>&pound; ".$row['PRODUCT_PRICE']."</th>
                <th scope='col'>".$row['PRODUCT_CATEGORY']."</th>
                <th scope='col'>".$row['PRODUCT_INSTOCK']."</th>";
            
               
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