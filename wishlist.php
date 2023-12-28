<?php

include("connection.php");

if(isset($_GET['product_id'])){
	   // remove from wishlist
        $sql = "DELETE FROM PROD_WISH WHERE PRODUCT_ID = :product_id";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ":product_id", $_GET['product_id']);
        oci_execute($stid);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="css/wishlistss.css">
</head>

<body>

<?php   
        include('nav.php');
    ?>
	<div class="productcontainer">
		<header>
			
				<h1>My Wishlist</h1>
			
		</header>

		<div class="container">

		<?php
			$sql = "SELECT p.*
			FROM PRODUCT p
			JOIN PROD_WISH wp ON p.PRODUCT_ID = wp.PRODUCT_ID
			JOIN WISHLIST w ON wp.WISHLIST_ID= w.WISHLIST_ID
			WHERE w.FK1_CUST_ID = :cust_id";
			$stid = oci_parse($conn, $sql);
			oci_bind_by_name($stid , ":cust_id" , $_SESSION['cust_id']);
			
			oci_execute($stid);
			while($row= oci_fetch_array($stid)){
				$product_id = $row['PRODUCT_ID'];
				$product_image = $row['PRODUCT_IMG'];
				$product_price = $row['PRODUCT_PRICE'];

				echo "
				<div class='product'>
				<div class='productinfo'>
					<img src='upload/products/$product_image' alt=''>
					<div class='price'>
						<h2>".$row['PRODUCT_NAME']."</h2>";
						$price = $row['PRODUCT_PRICE'];
						if(!empty($row['PRODUCT_DSCTDPRICE'])){
							$disprice = (int)$price - (int)$row['PRODUCT_DSCTDPRICE'] ;
							$product_price = $disprice;
						}
						echo "<p>&pound; ".$product_price."</p>";	
						echo "<button onclick='removwishlist($product_id)'>Remove</button>
					</div>
				</div>
				<button class='add-to-cart' onclick='addtocart($product_id,1)'>Add to Cart</button>

			</div>
				";
			}

		?>
			

		</div>
	</div>
    <?php   
        include('footer.php');
    ?>

	<script>
		function removwishlist(product_id){
			document.location.href = "wishlist.php?product_id="+product_id;
		}

		function addtocart(product_id, quantity) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
				alert(this.responseText);
				}
			};
			xmlhttp.open(
				"GET",
				"cartwishlist.php?action=addcart&quantity=" + quantity + "&id=" + product_id,
				true
			);
			xmlhttp.send();
        }
	</script>
</body>
</html>



 