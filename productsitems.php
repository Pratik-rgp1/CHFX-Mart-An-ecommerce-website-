<?php
    include('connection.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
	<link rel="shortcut icon" href="../images/CHFXMartLogo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="css/productitem.css">
</head>
<body>
<body>

<?php
    include('nav.php');
?>

    <!--Products-->
    <div class="products" id="Products">
    
    <div id="categories">
            <div id="buttons">
                <button class="button-value" onclick='filterall()'>All</button>
            <?php 
                $sql = "SELECT * FROM CATEGORY";
                $stid = oci_parse($conn,$sql);
                oci_execute($stid);
                while($row = oci_fetch_array($stid)){
                    $category_id = $row['CATEGORY_ID'];
                    $category_name = $row['CATEGORY_NAME'];
                    echo "<button class='button-value' onclick='filterCategory($category_id)'>$category_name</button>";
                }
            ?>  
            </div>

        </div>
        <h1>Products</h1>

        <div class="box">

        <?php
                if(isset($_GET['p_name'])){
                    $name=strtolower(trim($_GET['p_name']));
                   $sql= "SELECT * FROM PRODUCT WHERE PRODUCT_NAME LIKE '%'||:p_name||'%'";
                    $stid = oci_parse($conn,$sql);
                    oci_bind_by_name($stid, ":p_name" , $name);
                }
                else if(isset($_GET['cat_id'])){
                    $sql = "SELECT * FROM PRODUCT WHERE FK1_CATEGORY_ID = :cat_id";
                    $stid = oci_parse($conn,$sql);
                    oci_bind_by_name($stid, ":cat_id" , $_GET['cat_id']);
                }
                else{
                    $sql = "SELECT * FROM PRODUCT";
                    $stid = oci_parse($conn,$sql);
                }
                
                
                oci_execute($stid);
                while($row=oci_fetch_array($stid,OCI_ASSOC))
                {  
                    $product_id = $row['PRODUCT_ID'];
                    $number_of_prod=$row['PRODUCT_INSTOCK'];
            ?>
            <div class="card">

                <div class="small_card">
                <?php
                     if(isset($_SESSION['cust_id'])){

                        if( $number_of_prod==0)
                        {
                            echo "<i class='fa-solid fa-heart' onclick='' ></i>";

                        }
                        else
                        {
                        echo "<i class='fa-solid fa-heart' onclick='addtowishlist($product_id)' ></i>";

                        }
                     }
                     else{
                        echo "<i class='fa-solid fa-heart' onclick='login()'></i>";
                     }
                    ?>
                  
                </div>

                <div class="image">
                <img src="upload/products/<?php echo $row['PRODUCT_IMG'];?>" alt="<?php echo ucfirst($row['PRODUCT_NAME']); ?>" onclick="viewproduct(<?php echo $product_id; ?>)">
                </div>

                <div class="products_text">
                <h2><?php echo ucfirst($row['PRODUCT_NAME']); ?>  </h2>
                    <p>
                    <?php echo substr($row['PRODUCT_DESC'],0,35); ?> 
                    </p>
                    <?php
                    $price = $row['PRODUCT_PRICE'];
                    if(!empty($row['PRODUCT_DSCTDPRICE'])){
                        $disprice = (int)$price - (int)$row['PRODUCT_DSCTDPRICE'] ;
                        echo 
                        "<div class='product-price'>
                            <h3><del id='red'>&pound;  ".$row['PRODUCT_PRICE'] ."</del></h3>                    
                            <h3>&pound; ". $disprice ."</h3>                    
                        </div>";
                    }
                    else{
                        echo 
                        "<div class='product-price'>
                            <h3>&pound;  ".$row['PRODUCT_PRICE'] ."</h3>                    
                        </div>";
                    }
                    ?>
                    <h6>Stock <?php echo $row['PRODUCT_INSTOCK']; ?></h6>
                    <?php
                     if(isset($_SESSION['cust_id'])){
                        
                        if( $row['PRODUCT_INSTOCK']==0){
                            echo"<button class='btn'>Out Of Stock</button>";
                        }
                        else{
                            echo "<div class='btn' onclick='addtocart($product_id,1)'>Add To Cart</div>";
                            }
                        
                        // <!-- echo "<div class='btn' onclick='addtocart($product_id,1)'>Add To Cart</div>"; -->
                        }
                        else{
                            echo "<div class='btn' onclick='login()'>Add To Cart</div>";
                        }
                    ?>
                </div>

            </div>

            <?php
                }
            ?>
    </div>
</div>    

<br>
<br>
<?php   
        include('footer.php');
    ?>
<script>

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

    
        function addtowishlist(product_id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
            alert(this.responseText); 
            }
        };
        xmlhttp.open(
            "GET",
            "cartwishlist.php?action=addwishlist&id=" + product_id,
            true
        );
        xmlhttp.send();
        }


        function login(){
            document.location.href = "login.php";
        }

        function viewproduct(pid){
            document.location.href = "productview.php?pid="+pid;
        }
        
            function filterCategory(category_id){
                // alert('Category_id' + category_id);
                document.location.href = 'productsitems.php?cat_id='+category_id;
            }

            function filterall(){
                document.location.href = 'productsitems.php';
            }
      
    </script>
</body>
</html>
