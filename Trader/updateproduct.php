<?php
include('db_conn.php');

if(isset($_GET['id']) && isset($_GET['action'])){
   $id = $_GET['id'];
}
$pname=$pcategory=$pdesc=$pallergy="";
$product_error=$category_error=$price_error=$discount_error=$description_error=$image_error=$allergies_error=$stock_error="";
$errcount=0;


$sql = "SELECT * FROM PRODUCT WHERE PRODUCT_ID = :id";
$stid1 = oci_parse($conn, $sql);
oci_bind_by_name($stid1,':id' ,$id);
oci_execute($stid1);
    
while($row = oci_fetch_array($stid1,OCI_ASSOC)){
    $pid = $row['PRODUCT_ID'];
    $pname = $row['PRODUCT_NAME'];
    $pdesc = $row['PRODUCT_DESC'];
    $pprice = $row['PRODUCT_PRICE'];
   
    $pstock = $row['PRODUCT_INSTOCK'];
    $pallergy = $row['ALLERGIES'];
    $pimg = $row['PRODUCT_IMG'];
    $pcategory = $row['PRODUCT_CATEGORY'];
    }

    if(isset($_POST['submit']))
    {
            $uid = $_POST['product_id'];
            $name = $_POST['product_name'];
            $category =  $_POST['product_category'];
            $description = $_POST['product_description'];
            $price = $_POST['product_price'];
           
            $stock =   $_POST['product_stock'];
            $previous = $_POST['previous_image'];
            $allergy=$_POST['allergies'];
            $image = $_FILES["product_image"]["name"];
            $utype = $_FILES['product_image']['type'];
            $utmpname = $_FILES['product_image']['tmp_name'];
            $usize = $_FILES['product_image']['size'];
            $ulocation = "../upload/products/".$image;
  
            if(!empty($image)){     
              
              $sql = "UPDATE PRODUCT SET PRODUCT_CATEGORY= :category, PRODUCT_NAME= :name, PRODUCT_PRICE= :price, PRODUCT_DESC= :description, PRODUCT_INSTOCK= :stock,ALLERGIES= :allergy ,PRODUCT_IMG= :image WHERE PRODUCT_ID= :pid ";
  
              $stid = oci_parse($conn, $sql);
      
              oci_bind_by_name($stid, ':pid', $uid);
              oci_bind_by_name($stid ,':category',$category);
              oci_bind_by_name($stid ,':name',$name);
              oci_bind_by_name($stid ,':price',$price);
              oci_bind_by_name($stid ,':description',$description);
              oci_bind_by_name($stid ,':stock',$stock);
              oci_bind_by_name($stid ,':allergy',$allergy);
              oci_bind_by_name($stid ,':image',$image);
  
              if (unlink("../upload/products/" . $previous)) {
                  if (move_uploaded_file($utmpname, $ulocation)) {
                      if (oci_execute($stid)) {
                        header('location:productlist.php');
                      }
                  }
              }
            }
            else{
              $sql = "UPDATE PRODUCT SET PRODUCT_CATEGORY= :category, PRODUCT_NAME= :name,  PRODUCT_PRICE= :price, PRODUCT_DESC= :description, PRODUCT_INSTOCK= :stock,ALLERGIES= :allergy, PRODUCT_IMG= :previous WHERE PRODUCT_ID= :pid ";
  
              $stid = oci_parse($conn, $sql);
      
              oci_bind_by_name($stid, ':pid', $uid);
              oci_bind_by_name($stid ,':name',$name);
              oci_bind_by_name($stid ,':price',$price);
              oci_bind_by_name($stid ,':category',$category);
              oci_bind_by_name($stid ,':description',$description);
              oci_bind_by_name($stid ,':stock',$stock);
              oci_bind_by_name($stid ,':allergy',$allergy);
              oci_bind_by_name($stid ,':previous',$previous);
              $result = oci_execute($stid);
              
              if($result){
                header('location:productlist.php');
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
  <i class='bx bxs-user'></i>Trader | update Products 
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
         <h3>Update  Product</h3>
         <p class="text-muted">Complete the form below to update product</p>
      </div>

      <div class="container d-flex justify-content-center">
         <form action="" method="post" style="width:50vw; min-width:300px;">
            <div class="row mb-3">
               <div class="col">
                  <label class="form-label">Product Name:</label>
                  <input type="text" class="form-control" name="product_name" value="<?php echo $pname; ?>" >
                  <input type="hidden" class="form-control" name="product_id" value="<?php echo $pid; ?>" >
                  <p class="error password-error"><?php echo $product_error;?>
               </div>

               <div class="col">
                  <label class="form-label">Product Category:</label>
                  <input type="text" class="form-control" name="product_category" value="<?php echo $pcategory; ?>" >
                  <p class="error password-error"><?php echo $category_error;?>
               </div>
         


            <div class="row mb-3">
               <div class="col">
                  <label class="form-label">Product Price:</label>
                  <input type="number" class="form-control" name="product_price" value="<?php echo $pprice; ?>" >
                  <p class="error password-error"><?php echo $price_error;?>
               </div>

               <!-- <div class="col">
                  <label class="form-label">Discounted Price:</label>
                  <input type="number" class="form-control" name="discounted_price" value="<?php echo $pdprice; ?>" >
                  <p class="error password-error"><?php echo $price_error;?>
               </div> -->
        


            <div class="mb-3">
               <label class="form-label">Product Description: </label>
               <input type="text" class="form-control" name="product_description"value="<?php echo $pdesc; ?>"  >
               <p class="error password-error"><?php echo $description_error;?>
            </div>


               <div class="mb-3">
               <label class="form-label">Product Image: </label>
               <input type="file" id="myFile" name="product_image" value="<?php echo $pimg; ?>" > 
               <input type="hidden" id="myFile" name="previous_image" value="<?php echo $pimg; ?>" > 
               <p class="error password-error"><?php echo $image_error;?>
            </div>

            <div class="mb-3">
               <label class="form-label">Allergies: </label>
               <input type="text" class="form-control" name="allergies"value="<?php echo $pallergy; ?>"  >
               <p class="error password-error"><?php echo $allergies_error;?>
            </div>

            <div class="mb-3">
               <label class="form-label">Stock Level: </label>
               <input type="number" class="form-control" name="product_stock" value="<?php echo $pstock; ?>" >
               <p class="error password-error"><?php echo $stock_error;?>
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