<?php
    include("../connection.php");

    if(isset($_GET['id']) && isset($_GET['status'])){
        $id = $_GET['id'];
        $status = $_GET['status'];

        $sqlq = "SELECT * FROM TRADER WHERE TRADER_ID= :id"; // selecting the all data from the user
        $stmt = oci_parse($conn,$sqlq);
        oci_bind_by_name($stmt, ":id" ,$id);
        // exeucuting the query
        oci_execute($stmt);
        while($row=oci_fetch_array($stmt,OCI_ASSOC)){
            $fname = $row['FIRST_NAME'];
            $lname=$row['LAST_NAME'];
            $email = $row['TRADER_EMAIL'];
        }

        $username = $fname." ".$lname;

        $sql = "UPDATE TRADER SET STATUS = :verify WHERE TRADER_ID= :id";
        $stid = oci_parse($conn,$sql);
        oci_bind_by_name($stid, ':verify' ,$status);
        oci_bind_by_name($stid, ':id' ,$id);
        
        $sendemail= $email;
        if($_GET['status'] == 'verified'){
            // insert trader id in users table
            $check = "SELECT * FROM USERS WHERE TRADER_ID= '$id'";
            $checkstid = oci_parse($conn,$check);
            oci_execute($checkstid);
            if(!oci_fetch_array($checkstid)){
                $insertsql= "INSERT INTO USERS (TRADER_ID) VALUES ('$id')";
                $insertstid = oci_parse($conn, $insertsql);
                oci_execute($insertstid);
            }   
            $subj ="Notification from chfxmart";
            $body="Dear ".$username.",\nYou have been successfully added as a trader to our online store..\n Now you can sign in and do business..";
        }
        else if ($_GET['status'] == 'pending'){    
            $sub ="Notification form chfxmart";
            $body="Dear ".$username.",\nDue to non-activation, your trader account has been deactivated. \nRespond to this email with the appropriate details to activate your account..";   
        }
        include_once('../email.php');

        if(oci_execute($stid)){
            header('location:traderlist.php');
        }
    }

    if(isset($_GET['id']) && isset($_GET['action'])){
        $id = $_GET['id'];

        $sqlq = "SELECT * FROM TRADER WHERE TRADER_ID= :id"; // selecting the all data from the user
        $stmt = oci_parse($conn,$sqlq);
        oci_bind_by_name($stmt, ":id" ,$id);
        // exeucuting the query
        oci_execute($stmt);
        while($row=oci_fetch_array($stmt,OCI_ASSOC)){
            $fname = $row['FIRST_NAME'];
            $lname=$row['LAST_NAME'];
            $email = $row['TRADER_EMAIL'];
        }

        $username = $fname." ".$lname;
        
        $sendemail =$email;

        $subj ="Notification from chfxmart";
        $body="Dear ".$username.",\nSorry!! For this time You are not verified as trader. Try again Later..";
    
        include_once('../email.php');

        $sql = "DELETE FROM TRADER WHERE TRADER_ID = :id";
        $stid = oci_parse($conn,$sql);
        oci_bind_by_name($stid, ':id' ,$id);
        
        if(oci_execute($stid)){
            header('location:traderlist.php');
        }
    }
?>