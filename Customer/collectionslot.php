<?php
session_start();
include('../connection.php');
$err = '';

if(isset($_POST['placeorder'])){
  if(empty($_POST['collection_slot'])){
    $err = "Input field is required";
  }
  else{
    unset($_SESSION['slot_id']);

    $slot_id = $_POST['collection_slot'];
    $_SESSION['slot_id'] = $slot_id;
   
    $sql = "SELECT * FROM COLLECTION_SLOT WHERE SLOT_ID = :slot_id";
    $stid = oci_parse($conn,$sql);
    oci_bind_by_name($stid, ":slot_id" ,$_SESSION['slot_id']);
    oci_execute($stid);
    $data = oci_fetch_array($stid);
    $num = (int)$data['NO_OF_ORDERS'];

    $order_num = $num-1;

    $updatesql="UPDATE COLLECTION_SLOT SET NO_OF_ORDERS = :order_num WHERE SLOT_ID = :slot_id";
    $stmt = oci_parse($conn,$updatesql);
    oci_bind_by_name($stmt , ":order_num" , $order_num);
    oci_bind_by_name($stmt, ":slot_id" , $_SESSION['slot_id']);
    oci_execute($stmt);

    $ordersql ="INSERT INTO ORDERS (FK1_CUST_ID , FK2_SLOT_ID) VALUES(:cust_id, :slot_id)";
    $orderstmt = oci_parse($conn,$ordersql);
    oci_bind_by_name($orderstmt , ":cust_id", $_SESSION['cust_id']);
    oci_bind_by_name($orderstmt , ":slot_id" ,$_SESSION['slot_id']);
    
    if(oci_execute($orderstmt)){

      unset($_SESSION['order_id']);

      $extsql = "SELECT * FROM ORDERS WHERE FK1_CUST_ID = :cust_id AND FK2_SLOT_ID= :slot_id";
      $extstmt = oci_parse($conn, $extsql);
      oci_bind_by_name($extstmt , ":cust_id", $_SESSION['cust_id']);
      oci_bind_by_name($extstmt , ":slot_id" ,$_SESSION['slot_id']);
      oci_execute($extstmt);

      while($data = oci_fetch_array($extstmt)){
        $order_id = $data['ORDER_ID'];
      }
      
      $_SESSION['order_id'] = $order_id;

      $cartsql = "SELECT p.*
      FROM PROD_CART p
      JOIN CART s ON p.CART_ID = s.CART_ID
      WHERE s.FK1_CUST_ID = :user_id";

      $cartstmt = oci_parse($conn,$cartsql);
      oci_bind_by_name( $cartstmt , ":user_id", $_SESSION['cust_id']);
      oci_execute($cartstmt);

      while($row = oci_fetch_array($cartstmt)){
        $cart_id = $row['CART_ID'];
        $product_id = $row['PRODUCT_ID'];
        $quantity = $row['CART_QUANTITY'];
       
        $insertsql ="INSERT INTO PRODUCT_ORDER (ORDER_ID , PRODUCT_ID , ORDER_QUANTITY) VALUES(:order_id , :product_id, :quantity)";
        $insertstmt = oci_parse($conn , $insertsql);
        oci_bind_by_name($insertstmt, ":order_id" , $_SESSION['order_id']);
        oci_bind_by_name($insertstmt, ":product_id" , $product_id);
        oci_bind_by_name($insertstmt , ":quantity" , $quantity);
        
        oci_execute($insertstmt);
      }

      $delsql = "DELETE FROM PROD_CART WHERE CART_ID = :cart_id";
      $delstmt = oci_parse($conn, $delsql);
      oci_bind_by_name($delstmt , ":cart_id" , $cart_id);
      oci_execute($delstmt);
        
      header('location:../invoice.php');
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Slot</title>
    <link rel="stylesheet" href="collectionslots.css">
</head>
<body>
<div class="container">
    <h2>Collection Slot:</h2>
    <form method='post'>
      
      <label for="time">Select a time:</label>
      <select id="time" name="collection_slot">
        <option value="" >Choose your Collection Slot</option>
      
        <?php
            $sql = "SELECT * FROM COLLECTION_SLOT WHERE NO_OF_ORDERS > 0";
            $stid = oci_parse($conn, $sql);
            oci_execute($stid);
            while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
              echo "<option value=" . $row['SLOT_ID'] . ">" . $row['SLOT_TIME'] . " (" . $row['SLOT_DAY'] . ")</option>";
            }

          ?>

  
      </select>
      
      <button type="submit" name='placeorder'>Place Order</button>

    </form>
  </div>


</body>
</html>