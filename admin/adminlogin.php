<?php

$firstname="chfx";
$lastname="mart";
$gender="female";
$semail="chfxmart13@gmail.com";
$t_password="Admin@21";
$cpass=md5($t_password);
$role="admin";
 include('../connection.php');
          
           
                            $sql = 'INSERT INTO USERS (FIRST_NAME,LAST_NAME,GENDER,ADMIN_EMAIL,ADMIN_PASS,ROLE)
                            VALUES (:first_name,:last_name,:gender, :admin_email, :admin_pass,:role)';
                        

                            $stid = oci_parse($conn,$sql);
                            oci_bind_by_name($stid, ':first_name', $firstname);
                            oci_bind_by_name($stid, ':last_name', $lastname);
                            oci_bind_by_name($stid, ':gender',$gender);
                            oci_bind_by_name($stid, ':admin_email', $semail);
                            oci_bind_by_name($stid, ':admin_pass', $cpass);
                            oci_bind_by_name($stid, ':role',  $role);


                            if(oci_execute($stid)){
                                header('location:../login.php');
                            }
                         

?>