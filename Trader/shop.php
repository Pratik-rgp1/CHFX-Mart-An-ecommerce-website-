<?php
	session_start();
	include('../connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/shop.css">

	 <!-- Bootstrap CSS -->
	 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
   <link rel="stylesheet" href="styles.css">
  <script type="text/javascript" src="script.js"></script>

   <!-- Font Awesome -->
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>



</head>
<body>
	
<nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; <b> Trader Menu </b></button>
    </div>
  <i class='bx bxs-user'></i>Trader | Shop Lists
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



<main>
		<h2>Registered Shops</h2>
		<div class="shop-container">

		<?php 
		$status = 'verified';
			 $sql = "SELECT * FROM SHOP WHERE FK1_TRADER_ID= :us_id AND STATUS = :verify";
			 $stid = oci_parse($conn,$sql);
			 oci_bind_by_name($stid, ':us_id',  $_SESSION['trader_id']);
			 oci_bind_by_name($stid, ':verify', $status);

			 oci_execute($stid);
			 while($row = oci_fetch_array($stid,OCI_ASSOC)){
				$shop_id=$row['SHOP_ID'];
				echo "
				<div class='shop-box'>
				<a href='productlist.php?shop_id=$shop_id' class='' >
					<h3>".$row['SHOP_NAME']."</h3>
					<p>".$row['SHOP_DESC']."</p>
					
				</a>
				<div class='d-flex gap-4 pb-3 btns'>
					<a href='editshop.php?id=$shop_id&action=delete'>Update</a>
					<a href='delete.php?id=$shop_id&action=shop' id='delete' >Delete</a>
				</div>
				</div>

				";
			 }

		?>
		</div>
            
</main>
    
   <!-- Bootstrap -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>