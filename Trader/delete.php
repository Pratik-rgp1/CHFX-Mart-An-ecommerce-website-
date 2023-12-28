<?php
  include('../connection.php');

    if(isset($_GET['id']) && isset($_GET['action'])){
        $delid = $_GET['id'];

        if($_GET['action'] == "product"){
            $sql = 'DELETE  FROM  "PRODUCT" WHERE PRODUCT_ID = :pid';
            $stid = oci_parse($conn,$sql);
            oci_bind_by_name($stid,':pid', $delid);
            if(oci_execute($stid)){
                header("location:productlist.php");
            }
        }
        else if($_GET['action'] == 'shop'){
            $sql = 'DELETE  FROM  "PRODUCT" WHERE FK2_SHOP_ID= :pid';
            $stid = oci_parse($conn,$sql);
            oci_bind_by_name($stid,':pid', $delid);
            oci_execute($stid);

            $sql = 'DELETE  FROM  "SHOP" WHERE SHOP_ID = :pid';
            $stid = oci_parse($conn,$sql);
            oci_bind_by_name($stid,':pid', $delid);
            if(oci_execute($stid)){
                header("location:shop.php");
            }
        }
       
    }
?>