<?php
session_start();
    include("../connection.php");

    if(isset($_GET['id']) && isset($_GET['status'])){
        $id = $_GET['id'];
        $status = $_GET['status'];
        // echo $status;

        $sqlq = "SELECT * FROM TRADER WHERE TRADER_ID= :id"; // selecting the all data from the user
        $stmt = oci_parse($conn,$sqlq);
        oci_bind_by_name($stmt, ":id" , $_SESSION['trader_id']);
        // exeucuting the query
        oci_execute($stmt);
        while($row=oci_fetch_array($stmt,OCI_ASSOC)){
            $fname = $row['FIRST_NAME'];
            $lname=$row['LAST_NAME'];
            $email = $row['TRADER_EMAIL'];
        }
        $username = $fname." ".$lname;

      
        
        $sql = "UPDATE SHOP SET STATUS = :verify WHERE SHOP_ID= :id";
        $stid = oci_parse($conn,$sql);
        oci_bind_by_name($stid, ':verify' ,$status);
        oci_bind_by_name($stid, ':id' ,$id);
        if(oci_execute($stid)){


            if($status=='verified')
            {
                
                $sendemail= $email;
            $subj ="Notification from chfxmart";
                $body="Dear ".$username.",\nYour shop  have been successfully approve by admin..\n Now you can sign in and do business..";
            }
            if($status=='panding')
            {
                $sendemail= $email;
                $subj ="Notification from chfxmart";
                $body="Dear ".$username.",\n sorry you have been deleted ..\n Now you can sign in and do business..";
            }
            include_once('../email.php');

            header('location:shoplist.php');
        }
    }

    if(isset($_GET['id']) && isset($_GET['action'])){
        $id = $_GET['id'];
        $sql = "DELETE FROM SHOP WHERE SHOP_ID = :id";
        $stid = oci_parse($conn,$sql);
        oci_bind_by_name($stid, ':id' ,$id);
        
        if(oci_execute($stid)){
            header('location:shoplist.php');
        }
    }



?>