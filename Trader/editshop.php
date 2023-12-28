<?php
session_start();
include('../connection.php');

$errcount = 0; 
$shopname = $shopdescription = "";
$shopname_error = $shopdescription_error = "";

if(isset($_GET['id']) && isset($_GET['action'])){
    $sql = "SELECT * FROM SHOP WHERE SHOP_ID = :s_id";
    $stid1 = oci_parse($conn, $sql);
    oci_bind_by_name($stid1,':s_id' ,$_GET['id']);
    oci_execute($stid1);
}
while($row = oci_fetch_array($stid1)){
    $shop_id = $row['SHOP_ID'];
    $shopname = $row['SHOP_NAME'];
    $shopdescription = $row['SHOP_DESC'];
    // $shoplocation = $row['SHOP_LOCATION'];
}

    


if(isset($_POST['updateshop'])) {
    $shop_id = $_POST['shop_id'];
    $shopname = $_POST['shopname'];
    $shopdescription = $_POST['shopdescription'];
    // $shoplocation = $_POST['shoplocation'];

    $sql = 'UPDATE SHOP SET SHOP_NAME =  :shop_name, SHOP_DESC = :shop_desc WHERE SHOP_ID = :shop_id';
    $stid = oci_parse($conn,$sql);
    
    oci_bind_by_name($stid, ":shop_name", $shopname);
    oci_bind_by_name($stid, ":shop_desc", $shopdescription);
    // oci_bind_by_name($stid, ":shop_location", $shoplocation);
    oci_bind_by_name($stid, ":shop_id", $shop_id);

    if(oci_execute($stid)) {
            header('location:shop.php');
    } 
}

?>


 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Shop</title> 
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
   <link rel="stylesheet" href="styles.css">
  <script type="text/javascript" src="script.js"></script>

   <!-- Font Awesome -->
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <link rel="stylesheet" href="../css/addShop.css">
</head>
<style>
    .error
{
	
    color: rgb(255, 0, 0);
    display: block;

}

</style>
<body>

<nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; <b> Trader Menu </b></button>
    </div>
  <i class='bx bxs-user'></i>Trader | Update Shop
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

<div class="container">
    <h1 class="text-center mt-3">Update Shop</h1>
    <form method="POST" action="" class="mt-5">
        <div class="form-group">
            <label for="name">Shop Name:</label>
            <input type="hidden" name="shop_id" id="name" class="form-control" value='<?php echo $shop_id;?>' >
            <input type="text" name="shopname" id="name" class="form-control" value='<?php echo $shopname;?>' >
			<p class="error password-error"><?php echo $shopname_error;?></p>
        </div>

        <div class="form-group">
            <label for="description">Shop description:</label>
            <input type="text" name="shopdescription" id="description" class="form-control" value='<?php echo $shopdescription;?>' >
			<p class="error password-error"><?php echo $shopdescription_error;?></p> 
        </div>

        

        <div class="text-center mt-5">
            <input type="submit" name="updateshop" value="Submit" class="btn btn-primary">
        </div>
    </form>
</div> 

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

   <!-- Bootstrap -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>

