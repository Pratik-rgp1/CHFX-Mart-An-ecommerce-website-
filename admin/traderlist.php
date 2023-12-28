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
   <link rel="stylesheet" href="../Trader/styles.css">
  <script type="text/javascript" src="../Trader/script.js"></script>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <title>Admin Dashboard</title>
</head>

<body>

<nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; <b> Admin Menu </b></button>
    </div>
  <i class='bx bxs-user'></i>Admin | Trader list
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
          <th scope="col">ID</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Category</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
       
      <?php
        $sql = "SELECT * FROM TRADER";
        $stid = oci_parse($conn,$sql);
        oci_execute($stid);
        while($row = oci_fetch_array($stid,OCI_ASSOC)){
            $_SESSION['email'] = $row['TRADER_EMAIL'];
            $id = $row['TRADER_ID'];
            echo "
            <tr>
                <th scope='col'>".$row['TRADER_ID']."</th>
                <th scope='col'>".$row['FIRST_NAME']." ".$row['LAST_NAME']."</th>
                <th scope='col'>".$row['TRADER_EMAIL']."</th>
                <th scope='col'>".$row['TRADER_CATEGORY']."</th>
                <th scope='col'>".$row['STATUS']."</th>
                <th scope='col' class='d-flex justify-content-center gap-2'>";
                    if($row['STATUS'] === 'pending'){
                        echo "<a href='updatetrader.php?id=$id&status=verified' >Approve</a>";
                        echo "<a href='updatetrader.php?id=$id&action=delete' >Decline</a>";
                    }
                    else{
                        echo "<a href='updatetrader.php?id=$id&status=pending' >Deactivate</a>";
                    }   
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