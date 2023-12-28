<?php
include('connection.php');
 


    if(isset($_GET['P_id']) && isset($_GET['action']) )
    {
        $pid = $_GET['P_id'];
        $cid=$_GET['cid'];
        if($_GET['action']=='plus'){
 

          $query = 'SELECT *FROM PROD_CART WHERE CART_ID= :cid AND PRODUCT_ID= :id';
          $upps = oci_parse($conn,$query);
          oci_bind_by_name($upps,':id',$pid);
          oci_bind_by_name($upps,':cid',$cid);
          oci_execute($upps);
          while($row = oci_fetch_array($upps,OCI_ASSOC)){
              $number_of_peoduc=$row['CART_QUANTITY'];
          }
          $add_q=(int)$number_of_peoduc+1;
         
          $sql = 'UPDATE  PROD_CART SET  CART_QUANTITY= :increase WHERE PRODUCT_ID = :id AND CART_ID=:cid';
          $upp = oci_parse($conn,$sql);
          oci_bind_by_name($upp,':increase',  $add_q);
          oci_bind_by_name($upp,':id',  $pid);
          oci_bind_by_name($upp,':cid',  $cid);

          if(oci_execute($upp))
          {
          header('location:cart.php');
          }
        }
        if($_GET['action']=='minus'){
          $query = 'SELECT *FROM PROD_CART  WHERE CART_ID= :cid AND PRODUCT_ID= :id';
          $upps = oci_parse($conn,$query);
          oci_bind_by_name($upps,':id',$pid);
          oci_bind_by_name($upps,':cid',$cid);
          oci_execute($upps);
          while($row = oci_fetch_array($upps,OCI_ASSOC)){
              $number_of_peoduc=$row['CART_QUANTITY'];
          }
          $add_q=(int)$number_of_peoduc-1;
         
          $sql = 'UPDATE  PROD_CART SET  CART_QUANTITY= :increase WHERE PRODUCT_ID = :id AND CART_ID=:cid';
          $upp = oci_parse($conn,$sql);
          oci_bind_by_name($upp,':increase',  $add_q);
          oci_bind_by_name($upp,':id',  $pid);
          oci_bind_by_name($upp,':cid',  $cid);

          if(oci_execute($upp))
          {
          header('location:cart.php');
          }
      }
       
    
    }
?>