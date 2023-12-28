<?php
include('connection.php');

    // remove from cart
    if (isset($_GET['product_id'])) {
        $sql = "DELETE FROM PROD_CART WHERE PRODUCT_ID = :product_id";
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

            <h1>My Cart</h1>

        </header>

        <div class="container">

        <?php
            $price = 0;
            $total = 0;
            $sql = "SELECT p.*,wp.*
			FROM PRODUCT p
			JOIN PROD_CART wp ON p.PRODUCT_ID = wp.PRODUCT_ID
			JOIN CART w ON wp.CART_ID= w.CART_ID
			WHERE w.FK1_CUST_ID = :cust_id";
			$stid = oci_parse($conn, $sql);
			oci_bind_by_name($stid , ":cust_id" , $_SESSION['cust_id']);
			
			oci_execute($stid);
            $count=0;
			while($row= oci_fetch_array($stid)){
                $cart_quantity = $row['CART_QUANTITY'];
                $product_price = $row['PRODUCT_PRICE'];
				$product_id = $row['PRODUCT_ID'];
				$product_image = $row['PRODUCT_IMG'];
                $price = $row['PRODUCT_PRICE'];
                if(!empty($row['PRODUCT_DSCTDPRICE'])){
                    $disprice = (int)$price - (int)$row['PRODUCT_DSCTDPRICE'] ;
                    $product_price = $disprice;
                }
                $count=$count+1;

                $price = $cart_quantity *$product_price;
                $total += $cart_quantity * $product_price;

                $cart_id=$_SESSION['cust_id'];
                echo "
                <div class='product'>
                <div class='productinfo'>
                    <img src='upload/products/$product_image' alt=''>
                    <div class='price'>
                        <h2>".$row['PRODUCT_NAME']."</h2>
                        <p>Stock : ".$row['PRODUCT_INSTOCK']."</p>
                        <button onclick='removecart($product_id)'>Remove</button>
                    </div>
                </div>";
            echo "<p> &pound; ".$product_price."</p>";
            // echo  "<p>  Qunatity: "
            //     .$cart_quantity."</p>";
            
                
            echo  "<p>  Qunatity: ";
                if($cart_quantity>1)
                {
                echo "<button id=plus><a href='cartquantity.php?&P_id=$product_id&cid=$cart_id&action=minus' id='single'>-</button></a>";
                }
              
                echo $cart_quantity;
               
                
                if($row['PRODUCT_INSTOCK']>$cart_quantity)
                {
                
                echo "<button id=plus><a href='cartquantity.php?&P_id=$product_id&cid=$cart_id&action=plus' id='single'>+</button></a>"; 
                
                }
                echo "</p>";
            echo   "<p> &pound;".$price."</p>
            </div>
                ";
            }
        ?>
        
            
          

        </div>
    </div><br>
    


    <div class="total-price">
        <p>Total Product:  <?php echo $count; ?></p>
    </div>

    <br>

	<div class="total-price">
        <p>Total Price : &pound<?php echo $total; ?></p>
    </div>

    
    <div class="button">
        <?php
        if($total != 0){
            echo "<input type='submit' value='Checkout' name='button' onclick='checkout()'>";
        }
        else{
            echo "<input  type='submit' value='Checkout'>";
        }
     
        ?>
    
    </div>


    <?php   
        include('footer.php');
    ?>
    <script>
        function removecart(product_id){
			document.location.href = "cart.php?product_id="+product_id;
		}

        function checkout(){
			document.location.href = "Customer/collectionslot.php";
		}
    </script>
</body>

</html>