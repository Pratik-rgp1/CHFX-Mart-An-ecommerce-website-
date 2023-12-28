<?php
session_start();
include("connection.php");
include('paypal/payment.php');

$sql = "SELECT * FROM CUSTOMER WHERE CUST_ID = :user_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":user_id" , $_SESSION['cust_id']);
oci_execute($stmt);
while($row = oci_fetch_array($stmt)){
	$fullname = $row['FIRST_NAME'] . " " . $row['LAST_NAME'];
	$email = $row['CUST_EMAIL'];
	$phone = $row['PHONE']; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" type="text/css" href="css/invoice.css"> 
</head>
<body>
    <div class="container">
		<h1>Invoice</h1>
		<h2>Order ID: <?php echo $_SESSION['order_id']; ?></h2>

	

		<p>Bill To: </p> 
		<p>Name: <?php echo $fullname; ?></p>
		<p>Phone: <?php echo $phone; ?></p>
		<p>Email: <?php echo $email; ?></p>
		
		
	<table>
			
				<tr>
					<th>S.N</th>
                    <th>Item Name</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Total</th>
				</tr>
			
		
				<?php
				$price =0;
				$totalprice = 0;
				$count = 0;
				$sql = "SELECT p.*, op.*
					FROM PRODUCT p
					JOIN PRODUCT_ORDER op ON p.PRODUCT_ID = op.PRODUCT_ID
					JOIN ORDERS ot ON op.ORDER_ID = ot.ORDER_ID 
					WHERE ot.ORDER_ID = :order_id";
				

				$stmt = oci_parse($conn, $sql);
				oci_bind_by_name($stmt , ":order_id" , $_SESSION['order_id']);
				oci_execute($stmt);
				while($row = oci_fetch_array($stmt)){
					$count += 1;
					$product_price = $row['PRODUCT_PRICE'];
					$price = $row['PRODUCT_PRICE'];
					if(!empty($row['PRODUCT_DSCTDPRICE'])){
						$disprice = (int)$price - (int)$row['PRODUCT_DSCTDPRICE'] ;
						$product_price = $disprice;
					}
	
					$price = (int)$row['ORDER_QUANTITY'] * $product_price;
					$totalprice += (int)$row['ORDER_QUANTITY'] * $product_price;
					echo "
					<tr>
						<td> ".$count."</td>
						<td> ".$row['PRODUCT_NAME']."</td>
						<td>&pound; ".$product_price."</td>
						<td>".$row['ORDER_QUANTITY']."</td>
						<td>&pound; ".$price."</td>
					</tr>";
				}


				?>
				
				
		</table>
		
		<div class="total">
			Sub Total: <?php echo number_format($totalprice,2); ?>
		</div>
        
        <div class="tax">
			Tax Amount(13%): <?php 
				$taxamount = $totalprice * 0.13;
				echo number_format($taxamount,2); 
				?>
		</div>
		<div class="discount">
			Total Price: <?php 
				unset($_SESSION['totalamount']);
				$finalprice = $taxamount + $totalprice ;
				$_SESSION['totalamount'] = $finalprice;
				echo number_format($finalprice,2); 
				?>
		</div>

		<form action="<?php echo PAYPAL_URL; ?>" method='post'>
        <div class="place-btn">
            
            <input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">

            <input type="hidden" name="amount" value="<?php echo $finalprice; ?>">
            
            <input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
           <!-- Specify a Buy Now button. -->
            <input type="hidden" name="cmd" value="_xclick">
            <!-- Specify URLs -->
            <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
            <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">

            <input type="submit" name="submit" value="Payment By Paypal" />
        </div>
        </form>

		<div class="welcome"><p>Thank You For Shopping And Visit Again</p></div>

	</div>

	
</body>
</html>