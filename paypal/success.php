<?php
session_start();

include("../connection.php");
include_once ('payment.php');

if(isset($_GET['PayerID'])){
    $sqlorder = "SELECT op.*, p.*
    FROM PRODUCT_ORDER op
    JOIN PRODUCT p ON op.PRODUCT_ID = p.PRODUCT_ID
    WHERE op.ORDER_ID = :order_id";

    $order_stid = oci_parse($conn, $sqlorder);
    oci_bind_by_name($order_stid , ":order_id",$_SESSION['order_id'] );
    oci_execute($order_stid);
    while($row = oci_fetch_array($order_stid)){
        $order_qty = (int)$row['ORDER_QUANTITY'];
        $product_id = $row['PRODUCT_ID'];
        $prodct_qty = (int)$row['PRODUCT_INSTOCK'];

        $quantity = $prodct_qty - $order_qty;

        $update = "UPDATE PRODUCT SET PRODUCT_INSTOCK = :quantity WHERE PRODUCT_ID = :product_id";
        $stidupdate = oci_parse($conn,$update);
        oci_bind_by_name($stidupdate, ":quantity" ,$quantity);
        oci_bind_by_name($stidupdate , ":product_id" , $product_id);
        oci_execute($stidupdate);
    }

$payment_detail = "completed";
    
$sql = "INSERT INTO PAYMENT (TOTAL_AMOUNT,FK1_ORDER_ID) VALUES (:total_amount,:order_id)";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":total_amount", $_SESSION['totalamount']);
oci_bind_by_name($stmt, ":order_id", $_SESSION['order_id']);
   
oci_execute($stmt);

$sqlpayment = "SELECT t.*,op.*
FROM PAYMENT p
JOIN PRODUCT_ORDER op ON p.FK1_ORDER_ID = op.ORDER_ID
JOIN PRODUCT pr ON op.PRODUCT_ID = pr.PRODUCT_ID
JOIN SHOP s ON pr.FK2_SHOP_ID = s.SHOP_ID
JOIN TRADER t ON s.FK1_TRADER_ID = t.TRADER_ID
WHERE p.FK1_ORDER_ID = :order_id";

$stmtpayment = oci_parse($conn, $sqlpayment);
oci_bind_by_name($stmtpayment, ":order_id", $_SESSION['order_id']);
oci_execute($stmtpayment);

while ($row = oci_fetch_array($stmtpayment)) {

$trader_id = $row['TRADER_ID'];

// inserting in report of payment details
$insertsql = "INSERT INTO REPORT (FK1_TRADER_ID,FK2_ORDER_ID) VALUES (:trader_id,:order_id)";
$stmtinsert = oci_parse($conn, $insertsql);
oci_bind_by_name($stmtinsert, ":trader_id", $trader_id);
oci_bind_by_name($stmtinsert, ":order_id",  $_SESSION['order_id']);
oci_execute($stmtinsert);
}

header('location:http://localhost/CHFXMART13/home.php');

}
