<?php
include "../connection.php";
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
  <link rel="stylesheet" href="../Trader/styles.css">
  <script type="text/javascript" src="../Trader/script.js"></script>

  <title>Admin Dashboard</title>
</head>

<body>
<nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; <b> Admin  Menu </b></button>
    </div>
  <i class='bx bxs-user'></i>Admin | Orders
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


  <div class="container">
   

    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; Trader Menu</button>
    </div>

    <h3> Orders Made: </h3>

    <table class="table table-hover text-center">
      <thead class="table-dark">
        <tr>
          <th scope="col">Order ID</th>
          <th scope="col">Customer</th>
          <th scope="col">Products Ordered</th>
          <th scope="col">Order Quantity</th>
          <th scope="col">Price</th>
        </tr>
      </thead>
      <tbody>
        
        <?php
        // $sql = "SELECT p.*,po.*,c.*
        // FROM PRODUCT_ORDER po 
        // JOIN PRODUCT p ON po.PRODUCT_ID = p.PRODUCT_ID
        // JOIN ORDERS o ON o.ORDER_ID = po.ORDER_ID
        // JOIN CUSTOMER c ON c.CUST_ID = o.FK1_CUST_ID";
        $sql="SELECT * FROM ORDERS";
        $result = oci_parse($conn, $sql);
        oci_execute($result);
        while ($row = oci_fetch_assoc($result)) {
            $o_id = $row['ORDER_ID'];
            $user_id=$row['FK1_CUST_ID'];

            $sql="SELECT * FROM CUSTOMER WHERE CUST_ID=:cust_id";
            $process = oci_parse($conn, $sql);
            oci_bind_by_name($process, ':cust_id', $user_id);
            // oci_bind_by_name($stid, ':user_id', $t_id);
            oci_execute($process);
            while ($name = oci_fetch_assoc($process)) 
            {
            $sql="SELECT * FROM PRODUCT_ORDER WHERE ORDER_ID=:order_id";
            $res = oci_parse($conn, $sql);
            oci_bind_by_name($res, ':order_id', $o_id);
            // oci_bind_by_name($stid, ':user_id', $t_id);
            oci_execute($res);
            while ($rows = oci_fetch_assoc($res)) 
            {
              $p_id = $rows['PRODUCT_ID'];
              $order_id = $rows['ORDER_QUANTITY'];
              $sql="SELECT * FROM PRODUCT WHERE PRODUCT_ID=:p_id";
              $que = oci_parse($conn, $sql);
              oci_bind_by_name($que, ':p_id', $p_id);
              oci_execute($que);
              while ($data = oci_fetch_assoc($que)) 
              {
                $p_id = $data['PRODUCT_ID'];
        ?>
          <tr>
            <td><?php echo $row["ORDER_ID"]; ?></td>
            <td><?php echo $name['FIRST_NAME'] ." " .$name['LAST_NAME']; ?></td>
            <td><?php echo ucfirst($data['PRODUCT_NAME']);?></td>
            <td><?php echo $rows["ORDER_QUANTITY"]; ?></td>
            <td>&pound;<?php echo $rows["ORDER_QUANTITY"] * $data['PRODUCT_PRICE'] ?></td>           
          </tr>
        <?php
              }
              }
            }
          }
        ?>

      </tbody>
    </table>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>