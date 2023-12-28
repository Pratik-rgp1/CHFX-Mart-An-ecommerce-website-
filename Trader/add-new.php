<?php
session_start();

include('db_conn.php');

// define variables and set to empty values

$product_error=$category_error=$shop_error=$price_error=$discount_error=$description_error=$image_error=$allergies_error=$stock_error="";
$errcount=0;



$id=$_SESSION['trader_id'];

unset($_SESSION['category']);

$sql = "SELECT * FROM TRADER WHERE TRADER_ID= '$id'" ;
$stid = oci_parse($conn, $sql);

oci_execute($stid);

while($row = oci_fetch_array($stid, OCI_ASSOC))
{
    $_SESSION['category'] =$category_name = $row['TRADER_CATEGORY'];
}


if(isset($_POST['submit']))
{
    
            
            if(empty($_POST['product_name'])){
               $errcount += 1;
                $product_error="Product name is required";
            }
            if(empty($_POST['product_category'])){
               $errcount += 1;
                $category_error="Category is required";
            }
         //    if(empty($_POST['product_price'])){
         //       $errcount += 1;
         //       $price_error="Price is required";
         //   }

            if(empty($_POST['product_description'])){
               $errcount += 1;
                $description_error="Product description is required";
            }
            if(empty($_FILES['product_image'] ["name"])){
               $errcount += 1;
               $image_error="Please choose  image";
           }
            if(empty($_POST['allergies'])){
               $errcount += 1;
                $allergies_error="Allergies is required";
            }
            if(empty($_POST['stock_level'])){
               $errcount += 1;
                $stock_error="Stock level is required";
            }
           
            else{
                $product=trim($_POST['product_name']);
                $category_id=trim($_POST['product_category']);
                
                $category= $_SESSION['category'];
                
                $price=trim($_POST['product_price']);

                    if(empty($_POST['product_price'])){
               
                     $description=="-";
                     }
                     else
                     {
                         $discount_id= $_POST['discounted_price'];
                     }
               //  $discount_id= $_POST['discounted_price'];
                $description=trim($_POST['product_description']);
                $allergies=trim($_POST['allergies']);
                $stock=trim($_POST['stock_level']); 

                $shop_id = $_POST['shop_id'];
                
                $image = $_FILES["product_image"]["name"];
                $utype = $_FILES['product_image']['type'];
                $usize = $_FILES['product_image']['size'];
                $utmpname = $_FILES['product_image']['tmp_name'];
                $ulocation = "../upload/products/".$image;

                if(!preg_match("/^[a-zA-z]*$/", $product))
                {   
                    $errcount += 1;
                    $product_error="Please enter correct product name";
                }

                if(!preg_match("/^[a-zA-z]*$/", $category))
                {
                    $errcount += 1;
                    $category_error="Please enter Category ";
                }
                  $price = (int)$price;
                  if(empty($_POST['product_price'])){
               
                     $description==$price;
                     }
                     else
                     {
                        $discount_per=0;
                        $product_dsctdprice =0;
                        $sql = "SELECT * FROM OFFER WHERE OFFER_ID = :offer_id";
                        $stid = oci_parse($conn,$sql);
                        oci_bind_by_name($stid, ':offer_id',  $discount_id);
                        oci_execute($stid);
                        while($data = oci_fetch_array($stid,OCI_ASSOC)){
      
                           $discount_per= (int)$data['DISCOUNT_PERC'];                   
                        }
      
                        $product_dsctdprice =$price- ($price - ($price *($discount_per/100)));
                     }
                 
                           if($errcount==0)
                           {
                              $sql = 'INSERT INTO PRODUCT (PRODUCT_NAME,PRODUCT_DESC,PRODUCT_PRICE,PRODUCT_CATEGORY,PRODUCT_DSCTDPRICE,PRODUCT_INSTOCK,ALLERGIES,PRODUCT_IMG,FK1_CATEGORY_ID,FK2_SHOP_ID)
                              VALUES (:product_name,:product_desc,:product_price,:product_category,:product_dsctdprice,:product_instock,:allergies,:product_img,:c_id,:s_id)';
                              $stid = oci_parse($conn,$sql);
                              
                              oci_bind_by_name($stid, ':product_name', $product);
                              oci_bind_by_name($stid, ':product_desc', $description);
                              oci_bind_by_name($stid, ':product_price',$price);
                              oci_bind_by_name($stid, ':product_category', $category);
                              oci_bind_by_name($stid, ':product_dsctdprice', $product_dsctdprice);
                              oci_bind_by_name($stid, ':product_instock',  $stock);
                              oci_bind_by_name($stid, ':allergies', $allergies);
                              oci_bind_by_name($stid, ':product_img', $image);
                              oci_bind_by_name($stid, ':c_id', $category_id);
                              oci_bind_by_name($stid, ':s_id',$shop_id);
                             
                              if(oci_execute($stid)){
                                  
                                  if(move_uploaded_file($utmpname,$ulocation)){
                                   echo "<script>window.alert('Product is Successfully Added!')</script>";
                                  
                               }
                             
                              }
                           }
                           
                        }          
                }
?>

<!DOCTYPE html>
<html lang="">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!-- Bootstrap -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
   <link rel="stylesheet" href="styles.css">
  <script type="text/javascript" src="script.js"></script>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
   <link rel="stylesheet" href="addproduct.css">


   <title>Trader | Add Products</title>
</head>

<style>
   .error
{
   
    color: rgb(255, 0, 0);
    display: block;

}
   </style>
   <body>
  <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    <div id="main">
    <button class="openbtn" onclick="openNav()">&#9776; <b> Trader Menu </b></button>
    </div>
  <i class='bx bxs-user'></i>Trader | Add Products 
  </nav>
  <div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  
   <a href="trader.php" class="">Dashboard</a>
         <a href="EditProfile.php">Profile</a>
          <a href="shop.php">Shops List</a>
          <a href="addShop.php">Add Shops</a>
          <a href="productlist.php">Products List</a>
          <a href="add-new.php">Add Products</a>
          <a href="order.php">Orders</a>
          <li><a href="http://localhost:8080/apex/f?p=105:LOGIN_DESKTOP:2667570840115::::::">Report</a></li>
          <a href="../logout.php" class="logout-btn">Logout</a>

</div>

<form action="#" method="POST" enctype="multipart/form-data">
   <div class="container">
      <div class="text-center mb-4">
         <h3>Add New Product</h3>
         <p class="text-muted">Complete the form below to add a new product</p>
      </div>

      <div class="container d-flex justify-content-center">
         <form action="" method="post" style="width:50vw; min-width:300px;">
            <div class="rowmb-3">
               <div class="col">
                  <label class="form-label">Product Name:</label>
                  <input type="text" class="form-control" name="product_name" >
                  <p class="error password-error"><?php echo $product_error;?>
               </div>

               <div class="col">
               <label class="form-label">Product Category:</label>
                  
                  <select name="product_category" id="" class="form-control">
                            <option value='' disabled>Select category</option>
                            <?php   

                                $sql = "SELECT * FROM CATEGORY WHERE CATEGORY_NAME = '$category_name'";
                                $stid = oci_parse($conn,$sql);
                                oci_execute($stid);
                                while($data = oci_fetch_array($stid,OCI_ASSOC)){
                                    echo "<option value='".$data['CATEGORY_ID']."'>".$data['CATEGORY_NAME']."</option>";
                                }
                            ?>
                        </select>
                  <p class="error password-error"><?php echo $category_error;?>
               </div>
         


            <div class="row mb-3">
               <div class="col">
                  <label class="form-label">Product Price:</label>
                  <input type="number" class="form-control" name="product_price" >
                  <p class="error password-error"><?php echo $price_error;?>
               </div>

               <div class="col">
                  <label class="form-label">Discount Type:</label>
                  
                  <select name="discounted_price"  id="" class="form-control">
                            <option value=''>Select Discount</option>
                            <?php   

                                $sql = "SELECT * FROM OFFER ";
                                $stid = oci_parse($conn,$sql);
                                oci_execute($stid);
                                while($data = oci_fetch_array($stid,OCI_ASSOC)){
                                    echo "<option value='".$data['OFFER_ID']."'>(".$data['DISCOUNT_PERC']."%)</option>";
                                }
                            ?>
                        </select>
                  <p class="error password-error"><?php echo $price_error;?>
               </div>
        


            <div class="mb-3">
               <label class="form-label">Product Description: </label>
               <input type="text" class="form-control" name="product_description" >
               <p class="error password-error"><?php echo $description_error;?>
            </div>


               <div class="mb-3">
               <label class="form-label">Product Image: </label>
               <input type="file" id="myFile" name="product_image"> <br/>
               <p class="error password-error"><?php echo $image_error;?>
            </div>

            <div class="mb-3">
               <label class="form-label">Allergies: </label>
               <input type="text" class="form-control" name="allergies" >
               <p class="error password-error"><?php echo $allergies_error;?>
            </div>

            <div class="mb-3">
               <label class="form-label">Stock Level: </label>
               <input type="number" class="form-control" name="stock_level" >
               <p class="error password-error"><?php echo $stock_error;?>
            </div>

            <div class="mb-3">
               <label class="form-label">Shop: </label>
              
               <select name="shop_id" id="" class="form-control">
                            <option value=''>Select Shop</option>
                            <?php
                                $user_id = $_SESSION['trader_id'];
                                $sql = "SELECT * FROM SHOP WHERE FK1_TRADER_ID = '$user_id'";
                                $stid = oci_parse($conn,$sql);
                                oci_execute($stid);
                                while($data = oci_fetch_array($stid,OCI_ASSOC)){
                                    echo "<option value='".$data['SHOP_ID']."'>".$data['SHOP_NAME']."</option>";
                                }
                            ?>
                        </select>

             
            </div>

            <div>
               <button type="submit" class="btn btn-success" name="submit">Save</button>
               <a href="trader.php" class="btn btn-danger">Cancel</a>
            </div>
         </form>
      </div>
   </div>
</form>
   <!-- Bootstrap -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>