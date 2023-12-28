<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" type="text/css" href="css/navs.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <header class="header">
        <div class="logo">
            <img src="images/CHFXLogo.png" alt=" " />
        </div>
        <!-- search -->

        <div class="wrapper">
            <input type="text" class="input" id='searchitem' placeholder="Search Here..">
           <button type="submit" onclick='searchterm()' id='search-box'> <i class="fas fa-search"></i></button>
        </div>

        <script>
            function searchterm(){
                var itemname = document.getElementById('searchitem').value;
                document.location.href= "productsitems.php?p_name="+itemname;
            }
        </script>

        <nav class="navbar" id="navbar">
            <a href="home.php">Home</a>
            <a href="productsitems.php">Products</a>
            <!-- <a href="categories.php">Category</a> -->

            <div class="vertical"></div>
            
            <?php

                if(isset($_SESSION['cust_id'])){
                    echo "
                    <div class='icons'>
                    <i class='fa fa-heart'></i>
                    <a href='wishlist.php'>Wishlist</a>
                    </div>
        
                    <div class='icons'>
                        <i class='fa fa-shopping-cart'></i>
                        <a href='cart.php'>Cart</a>
                    </div>


                    <div class='icons'>
                    <a href='Customer/EditProfile.php'><i class='fa fa-user'></i></a>
                    </div>

                    <div class='icons'>
                    <a href='logout.php'>Logout</a>
                    </div>";
                }
                else{
                   echo "
                   <div class='icons'>
                        <i class='fa fa-heart'></i>
                        <a href='login.php'>Wishlist</a>
                    </div>
        
                    <div class='icons'>
                        <i class='fa fa-shopping-cart'></i>
                        <a href='login.php'>Cart</a>
                    </div>
                   
                   <div class='icons'>
                    <i class='fa fa-user'></i>
                    <a href='login.php'>Login</a>
                    </div>";
                }
            ?>
            
        </nav>

        <div class="menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
            <span class="material-symbols-outlined">
                menu
                </span>
        </div>
    </header>


    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">

        <nav class="navoff">
            <a href="Home.php">Home</a>
            <a href="productsitems.php">Products</a>
            <a href="category.php">Category</a>
            
            <div class="icons">
                <i class="fas fa-search"></i>
                <a href="wishlist.php">Wishlist</a>
            </div>
            <div class="icons">
                <i class="fas fa-search"></i>
                <a href="cart.php">cart</a>
            </div>

            <?php
                if(isset($_SESSION['cust_id'])){
                    echo "<a href='Customer/EditProfile.php'>Profile</a>";
                    echo "<a href='logout.php'>Logout</a>";
                }
                else{
                   echo "<a href='login.php'>Login</a>";
                }
            ?>
            
  
        </nav>

    </div>
    </div>

    <!-- Script -->
    
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>

</html>