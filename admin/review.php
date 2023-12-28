<!DOCTYPE html>
<html>
<head>
    <title>View Reviews</title>
     <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="reviews.css">
    <link rel="stylesheet" href="../Trader/styles.css">
    <script type="text/javascript" src="../Trader/script.js"></script>
    <style>
     #imgs{
      width:120px;
      height:100px
    }
  </style>
</head>
<body>
<nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; <b> Admin  Menu </b></button>
    </div>
  <i class='bx bxs-user'></i>Admin | Review
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





    <h1>View Reviews</h1>
    <?php
   include("../connection.php");

   if (isset($_GET['review_id'])) {
    $sql = "DELETE FROM REVIEW WHERE REVIEW_ID = :review_id";
    $stid = oci_parse($conn, $sql);
    oci_bind_by_name($stid, ":review_id", $_GET['review_id']);
    oci_execute($stid);
}

    // Query to retrieve reviews from the database
    $count = 0;
    $query = "SELECT r.*,c.* ,p.*
    FROM REVIEW r
    JOIN CUSTOMER c ON r.FK1_CUST_ID = c.CUST_ID
    JOIN PRODUCT p ON r.FK2_PRODUCT_ID = p.PRODUCT_ID";

    // Execute the query
    $statement = oci_parse($conn, $query);
    oci_execute($statement);
    
    // Display the reviews in a table
    echo "<table>";
    echo "<tr>
    <th>S.No</th>
    <th>User Name</th>
    <th>Product Name</th>
    <th>Product Image</th>
    <th>Rating</th>
    <th>Review</th>
    <th>Action</th>

    </tr>";
    
    while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
        $id=$row['REVIEW_ID'];
        $count +=1;
        echo "<tr>";
        echo "<td>" . $count. "</td>";
        echo "<td>" . $row['FIRST_NAME'] ." ". $row['LAST_NAME']. "</td>";
        echo "<td>" . ucfirst($row['PRODUCT_NAME']) . "</td>";
        echo "<td>";    
        echo "<img id='imgs' src=\"../upload/products/".$row['PRODUCT_IMG']."\" alt=".$row['PRODUCT_NAME']."> </td>";
        
        if(!empty($row['RATING'] )){
            echo "<td>" . $row['RATING'] . "</td>";
        }
        else{
            echo "<td></td>";
        }
        echo "<td>" . $row['REVIEWS'] . "</td>";
        echo "<td><a href='review.php?review_id=$id' >Delete</a></td>";
        
        echo "</tr>";
    }
    
    echo "</table>";

   
    ?>
</body>
</html>
