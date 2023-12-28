<?php
session_start();

include('../connection.php');
$password_error="";
$errcount=0;
$dfname=$dlname=$demail=$dgender=$dphone="";
      
$uid = $_SESSION['cust_id'];
$sql = "SELECT * FROM CUSTOMER WHERE CUST_ID = :id";
$stid1 = oci_parse($conn, $sql);
oci_bind_by_name($stid1,':id' ,$uid);
oci_execute($stid1);
    
while($row = oci_fetch_array($stid1,OCI_ASSOC)){
    $demail = $row['CUST_EMAIL'];
    $did = $row['CUST_ID'];
    $dfname = $row['FIRST_NAME'];
    $dlname = $row['LAST_NAME'];
    $dgender = $row['GENDER'];
    $dphone = $row['PHONE'];

     }

     if(isset($_POST['update_password']))
     {
        if(empty($_POST['currentpassword'])){
            $password_error="Current password is required";
        }
        if(empty($_POST['confirmpassword'])){
            $password_error="Confirm password is required";  
        }
        if(empty($_POST['newpassword'])){
            $password_error="New password is required";  
        }
        else{

            $cupass = $_POST['currentpassword'];
            $copass = $_POST['confirmpassword'];
            $npass = $_POST['newpassword'];
            $id = $_POST['id'];

            if($npass == $copass)
            {
                    $uppercase = preg_match('@[A-Z]@', $npass);
                    $lowercase = preg_match('@[a-z]@', $npass);
                    $number    = preg_match('@[0-9]@', $npass);
                    $specialChars = preg_match('@[^\w]@', $npass);
                        
                    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($npass) < 8) {
                        $errcount+=1;
                        $password_error= 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
                     }

                     $curpass = md5($cupass);

                    $sql = "SELECT * FROM CUSTOMER WHERE CUST_ID = :id ";
                    $stid= oci_parse($conn,$sql);

                    oci_bind_by_name($stid, ':id' , $_SESSION['cust_id'] );
                    
                    oci_execute($stid);

                    $dbpassword='';

                    while($row = oci_fetch_array($stid,OCI_ASSOC)){
                        $dbpassword = $row['CUST_PASS'];
                    }

                    if($curpass != $dbpassword){
                        $errcount+=1;
                        $password_error="Current password do not match.";  
                    }
                    if($errcount == 0){
                            
                        $t_password = md5($npass);

                        $sql = "UPDATE CUSTOMER SET CUST_PASS=:pass WHERE CUST_ID= :id ";

                        $stid= oci_parse($conn,$sql);                
                        oci_bind_by_name($stid, ':id', $id );
                        oci_bind_by_name($stid, ':pass', $t_password);

                        if(oci_execute($stid)){
                            // header("location:profile.php?cat=profile&role=customer");      
                            echo "Updated";
                            echo "<script>alert('Your Password is Successfully Updated!!')</script>";
                        }
                    
                    }
            }
        
        }

     }

if(isset($_POST['update'])){
   
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $id = $_POST['id'];
    

                    
$sql = "UPDATE CUSTOMER SET FIRST_NAME= :fname,LAST_NAME= :lname,PHONE= :phone,CUST_EMAIL = :email WHERE CUST_ID= :id ";
$stid= oci_parse($conn,$sql);
                        
oci_bind_by_name($stid, ':id', $id );
oci_bind_by_name($stid, ':fname', $fname);
oci_bind_by_name($stid, ':lname', $lname);
oci_bind_by_name($stid, ':phone', $phone);
oci_bind_by_name($stid, ':email', $email);


    if(oci_execute($stid)){
        echo "Updated";
}

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">


    <title>Customer Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="profile.css">
</head>
<style>
.btns {
    margin-left: 1rem;
}
.error{
    color:red;
    padding-left:2rem;
}
</style>

<body>
    <div class="container">
        <div class="row gutters">
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">

                        <div class="account-settings">
                            <div class="user-profile">
                                <div class="user-avatar">
                                    <img src="profileimg.png" alt="Maxwell Admin">
                                </div>
                                <h5 class="user-name"><?php echo $dfname." ". $dlname;?></h5>
                                <h6 class="user-email"><a><?php echo $demail; ?></a></h6>
                            </div>
                            <div class="about">
                                <h5>Customer Information</h5>
                                <p>Customer of CHFX Mart..</p>
                                <p>Prior Email: <?php echo $demail; ?></p>
                                <p>Prior Gender: <?php echo $dgender ?></p>
                                <p>Prior Phone number: <?php echo $dphone; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-2 text-primary">Customer Personal Details</h6>
                                   
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="firstName">First Name</label>
                                        <input type="text" class="form-control" name="fname"
                                            placeholder="Enter First name" value="<?php echo $dfname; ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="lastName">Last Name</label>
                                        <input type="text" class="form-control" name="lname"
                                            placeholder="Enter Last name" value="<?php echo $dlname; ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="eMail">Email</label>
                                        <input type="email" class="form-control" name="email" placeholder="Enter email "
                                            value="<?php echo $demail; ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="number" class="form-control" name="phone"
                                            placeholder="Enter phone number" value="<?php echo $dphone; ?>">
                                    </div>
                                </div>

                                <br>
                                <input type="hidden" class="form-control" name="id" value="<?php echo $did;?>">
                                <div class="row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="text-right btns">

                                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                type="submit" name="change_password" class="btn btn-primary">Change
                                                Password</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Change Password</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method='post' action=''>
                        <div class='error'><?php echo $password_error; ?></div>
                        <div class="modal-body">

                            
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="formGroupExampleInput" name="currentpassword" placeholder="">
                            </div>

                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="formGroupExampleInput"  name="newpassword" placeholder="">
                            </div>

                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="formGroupExampleInput" name="confirmpassword" placeholder="">
                            </div>

                        </div>
                        <input type="hidden" class="form-control" name="id" value="<?php echo $did;?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="update_password" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript">

        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
            integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"
            integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous">
        </script>
</body>

</html>