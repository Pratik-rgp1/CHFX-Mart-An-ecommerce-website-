<?php
    include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHFX Mart | Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" type="text/css" href="css/home.css">
    <link rel="stylesheet" type="text/css" href="css/navs.css">
    <link rel="stylesheet" type="text/css" href="css/footers.css">

</head>

<body>
    <!-- include navbar -->
    <?php
   include ('nav.php');
   ?>



    <div class="home-container">
        <div class="section">
            <div class="home">
                <h3 class="left-large-content"><br>Welcoming you to<br> Cleckhudderfax greatest<br> online grocer
                    shopping<br> experience</h3>
                <img src="images/vegetables.png" class="vegimg" alt="">
            </div>
        </div>
        <!-- <div class="section"> -->

        <div class="about-us-section">
            <h2 class="about-heading">Shop By Category</h2>
            <div class="main-about">
                <div class="about-left-content">

                    <?php
                             $sql = "SELECT * FROM CATEGORY";
                             $stid = oci_parse($conn,$sql);
                             oci_execute($stid);
                             while($row = oci_fetch_array($stid)){
                                 $category_id = $row['CATEGORY_ID'];
                                 $category_name = $row['CATEGORY_NAME'];
                                 $category_image = $row['CATEGORY_IMAGE'];

                                 echo "
                                 <div>
                                    <img src='images/$category_image' onclick='filterCategory($category_id)' id='link-image'>
                                    <p>$category_name</p>
                                </div>";
                             }
                        ?>

                </div>
                <div class="fruits">

                    <img src="images/fruits.jpg" alt="" class="fruits">
                    <p class="para">Genuine and fresh items farmed locally from the beautiful<br> fields in
                        Cleckhudderfax</p>
                </div>

            </div>
        </div>
    </div>

    <!-- include footer -->

    <?php   
        include('footer.php');
    ?>

    <script>
    function filterCategory(category_id) {
        document.location.href = 'productsitems.php?cat_id=' + category_id;
    }
    </script>



</body>

</html>