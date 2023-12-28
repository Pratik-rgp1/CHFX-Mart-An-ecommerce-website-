<?php
  include('connection.php');
  if(isset($_GET['pid'])){
    $sql = "SELECT * FROM PRODUCT WHERE PRODUCT_ID = :pid";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt,":pid",$_GET['pid']);
    oci_execute($stmt);
    $row = oci_fetch_array($stmt);
  }

  $err = '';

  if(isset($_POST['subreview'])){
    if(empty($_POST['review'])){
      $err = "Review form should not be empty";
    }
    else{
      $review = $_POST['review'];
      $product_id = $_POST['product_id'];
      $cust_id = $_POST['cust_id'];

       $sql = "SELECT * FROM REVIEW WHERE FK1_CUST_ID= :cust_id AND FK2_PRODUCT_ID = :product_id";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ":cust_id", $cust_id);
            oci_bind_by_name($stmt, ":product_id", $product_id);
            oci_execute($stmt);
            while ($data = oci_fetch_array($stmt)) {
                if (isset($data['REVIEW_ID'])) {
                    $review_id = $data['REVIEW_ID'];
                }
            }

            if (!empty($review_id)) {
                $sql = "UPDATE REVIEW SET REVIEWS = :review WHERE REVIEW_ID = :review_id";
                $stid = oci_parse($conn, $sql);
                oci_bind_by_name($stid, ":review_id", $review_id);
                oci_bind_by_name($stid, ":review", $review);

                if (oci_execute($stid)) {
                   header("location:productview.php?pid=$product_id");
                  }
           } 
           else {

                $sql = "INSERT INTO REVIEW (FK1_CUST_ID,FK2_PRODUCT_ID, REVIEWS) VALUES (:cust_id, :product_id, :review)";
                $stid = oci_parse($conn, $sql);
                oci_bind_by_name($stid, ":cust_id", $cust_id);
                oci_bind_by_name($stid, ":product_id", $product_id);
                oci_bind_by_name($stid, ":review", $review);

                if (oci_execute($stid)) {
                  header("location:productview.php?pid=$product_id");
                }
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
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/productview.css">
</head>

<body>
    <?php
    include('nav.php');
?>

    <div class="container">
        <div class="product-image">
            <img src="upload/products/<?php echo  $row['PRODUCT_IMG']; ?>" alt="">
        </div>
        <div class="product-details">
            <h1><?php echo $row['PRODUCT_NAME']; ?></h1>

            <div class="products_star">
              <?php
                $product_id = $row['PRODUCT_ID'];

                if(isset($_SESSION['cust_id'])){
                  $user_id = $_SESSION['cust_id'];
                  echo "
                  <i class='fa-solid fa-star' onclick='rating(1,$product_id,$user_id)'></i>
                  <i class='fa-solid fa-star' onclick='rating(2,$product_id,$user_id)'></i>
                  <i class='fa-solid fa-star' onclick='rating(3,$product_id,$user_id)'></i>
                  <i class='fa-solid fa-star' onclick='rating(4,$product_id,$user_id)'></i>
                  <i class='fa-solid fa-star-half-stroke' onclick='rating(5,$product_id,$user_id)'></i>";
                }
                else{
                  echo "
                  <i class='fa-solid fa-star' ></i>
                  <i class='fa-solid fa-star'></i>
                  <i class='fa-solid fa-star'></i>
                  <i class='fa-solid fa-star'></i>
                  <i class='fa-solid fa-star-half-stroke'></i>";
                }
                $rating=1;
                $count =$ratecount= 0;
                $sql = "SELECT R.*
                            FROM REVIEW R
                            JOIN CUSTOMER U ON R.FK1_CUST_ID = U.CUST_ID
                            WHERE R.FK2_PRODUCT_ID = :product_id";
                    $stid = oci_parse($conn, $sql);
                    oci_bind_by_name($stid, ":product_id", $product_id);
                    oci_execute($stid);
                    while ($data = oci_fetch_array($stid)) {
                      $count += 1;
                        if(!empty($data['RATING'])){
                          $rating = (int)$data['RATING'];
                        }
                        $ratecount += $rating;
                    }
                    echo "\t($ratecount/$count)";
                    
                
              ?>
            </div>

            <p class="description"><?php echo $row['PRODUCT_DESC']; ?></p>
            <p class="description"><b> Allergy Information:</b> <?php echo $row['ALLERGIES']; ?></p>
            
           <?php
                    $price = $row['PRODUCT_PRICE'];
                    if(!empty($row['PRODUCT_DSCTDPRICE'])){
                        $disprice = (int)$price - (int)$row['PRODUCT_DSCTDPRICE'] ;
                        echo 
                        "<div class='product-price'>
                            <h3>Price : <del id='red'> &pound;  ".$row['PRODUCT_PRICE'] ."</del></h3>                    
                            <h3>&pound; ". $disprice ."</h3>                    
                        </div>";
                    }
                    else{
                        echo 
                        "<div class='product-price'>
                            <h3>Price :&pound;  ".$row['PRODUCT_PRICE'] ."</h3>                    
                        </div>";
                    }
                    ?>

            <p class="description"><b> Stock : <?php echo $row['PRODUCT_INSTOCK'] ?></b></p>
            <div class="form">
                <input type="hidden" id="product_id" value="<?php echo $row['PRODUCT_ID']; ?>">
                <label for="quantity">Quantity: </label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $row['PRODUCT_INSTOCK'];?>"
                    >
            </div>
            <?php
           $product_id= $_GET['pid'];
                    if( $row['PRODUCT_INSTOCK']==0){
                      echo"<button class='add-to-cart'>Out Of Stock</button>";
                    }
                    else
                    {
                    // echo "<button class='add-to-cart' onclick='cartadd()'>Add to Cart</button>";

                      if(isset($_SESSION['cust_id']))
                     {
                       
                         echo "<button class='add-to-cart' onclick='cartadd()'>Add to Cart</button>";
                    }
 
                    else{
                      
                      echo "<button class='add-to-cart' onclick='login($product_id)'>Add to Cart</button>";
                      //  echo "<i class='fa-solid fa-heart' onclick='login()'></i>";
                    }
                    }
                    ?>
            <!-- <button class="add-to-cart" onclick="cartadd()">Add to Cart</button> -->

            
            <form action="" method='post'>
              <div class="form-group">
                  <label for="review">Review:</label>
                  <!-- show review -->
                  <?php
                    $sql = "SELECT R.*,U.*
                            FROM REVIEW R
                            JOIN CUSTOMER U ON R.FK1_CUST_ID = U.CUST_ID
                            WHERE R.FK2_PRODUCT_ID = :product_id";
                    $stid = oci_parse($conn, $sql);
                    oci_bind_by_name($stid, ":product_id", $product_id);
                    oci_execute($stid);
                    while ($row = oci_fetch_array($stid)) {
                        $review = $row['REVIEWS'];
                        $username = $row['FIRST_NAME']." ".$row['LAST_NAME'];
                        echo " <p><b>$username </b>: <span>$review</span></p>";
                    }
                  ?>
                  <?php
                    echo "<span>".$err."</span>";
                    $cust_id=0;
                    if(isset($_SESSION['cust_id'])){
                      $cust_id = $_SESSION['cust_id'];
                    }
                  ?>
                  <input type="hidden"  name='cust_id'  value="<?php echo $cust_id; ?>">
                  <input type="hidden"  name='product_id'  value="<?php echo $_GET['pid']; ?>">
                  <textarea id="review" name="review" ></textarea>
              </div>

              <?php
                if(isset($_SESSION['cust_id'])){
                  echo "<button type='submit' name='subreview' >Submit Review</button>";
                }
                else
                {
                  echo "<button type='submit'  >Submit Review</button>";
                }
              ?>

            </form>
        </div>

    </div>

    <br>
    <br>
    <?php   
        include('footer.php');
    ?>

    <script>

        function login(pid){
            document.location.href = "login.php?pid="+pid;
        }

    function cartadd() {
        const product_id = document.getElementById("product_id").value;
        const quantity = document.getElementById("quantity").value;
        if(quantity>=1){
          addtocart(product_id, quantity);
        }
        
    }

    function addtocart(product_id, quantity) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
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


    function rating(num,product_id,user_id){
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            alert(this.responseText);
                        }
                    };
                    xmlhttp.open(
                        "GET",
                        "addrating.php?rating=" + num + "&product_id=" + product_id  + "&user_id=" + user_id,
                        true
                    );
                    xmlhttp.send();
                  }
    </script>
</body>

</html>