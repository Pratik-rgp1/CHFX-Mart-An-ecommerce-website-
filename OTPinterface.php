<?php
session_start();
include('connection.php');
$errotp ='';
$errcount=0;

if(isset($_POST['okotp'])){   
    $otpnum = $_POST['otpnumber'];
    $otpnumber= (int)$otpnum;
   
    if(empty($_POST['otpnumber'])){
        $errotp="Input field is required";
    }
    else{
        if($otpnumber != $_SESSION['otp']){
            $errcount+=1;
            $errotp="OTP is INVALID";
        }
        if($errcount == 0 ){
                if($_GET['role'] == 'customer'){
                    $verified = 'verified';

                    $sql1 = "UPDATE CUSTOMER SET STATUS = :verify WHERE CUST_EMAIL = :uemail";
                    $stid1 = oci_parse($conn,$sql1);
                    oci_bind_by_name($stid1, ':uemail', $_SESSION['email']);
                    oci_bind_by_name($stid1, ':verify', $verified);
                    unset($_SESSION['otp']);
					if(oci_execute($stid1)){
                        // insert customer id in user table
                        $custsql = "SELECT * FROM CUSTOMER WHERE CUST_EMAIL = :uemail ";
                        $stmt = oci_parse($conn, $custsql);
                        oci_bind_by_name($stmt , ':uemail', $_SESSION['email']);
                       
                        oci_execute($stmt);
                        
                        $data = oci_fetch_array($stmt);
                        $cust_id = $data['CUST_ID'];                        
                       
                        $sql= "INSERT INTO USERS (CUST_ID) VALUES ('$cust_id')";
                        $stid = oci_parse($conn, $sql);
                        oci_execute($stid);

                        $cartsql= "INSERT INTO CART (FK1_CUST_ID) VALUES ('$cust_id')";
                        $cartstid = oci_parse($conn, $cartsql);
                        oci_execute($cartstid);

                        $wishsql= "INSERT INTO WISHLIST (FK1_CUST_ID) VALUES ('$cust_id')";
                        $wishstid = oci_parse($conn, $wishsql);
                        oci_execute($wishstid);
                       
                        // echo "success";
						header("location:login.php");
					}
                // echo "<script>alert('You are successfylly verified')</script>";
                }
                else if($_GET['role'] == 'trader'){
					$verified = 'pending';

                    $sql1 = "UPDATE TRADER SET STATUS = :verify WHERE TRADER_EMAIL = :uemail";
                    $stid1 = oci_parse($conn,$sql1);
                    oci_bind_by_name($stid1, ':uemail', $_SESSION['email']);
                    oci_bind_by_name($stid1, ':verify', $verified);
                    // oci_bind_by_name($stid1, ':urole', $role);
					if(oci_execute($stid1))
                    {
                        header("location:login.php");
                    }
				
        	    }
                else if($_GET['role'] == 'reset'){
                    $_SESSION['user_type'] = $_GET['user'];
                    header("location:passwordreset.php");
                }
    	}
	}
}

if(isset($_POST['resendotp'])){
    unset($_SESSION['otp']);
    
    $otp_number = rand(100000,999999);
    $sendemail = $_SESSION['email'];
    $subj ="Please Verify Your Email address";
    $body="Dear User, \nYour Verification Code is: $otp_number";            
    include_once('email.php');

    $_SESSION['otp'] =$otp_number;
    // header("location:verifyotp.php?page=$role");
    header('location:verifyotp.php?role='.$_GET['role'].'');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>OTP interface</title>
	<link rel="stylesheet" href="css/otpinterfaces.css">
</head>
<body>

<form class="otp-form" action="#" method="post">
		<?php
			if(isset($_GET['role'])){
				if($_GET['role'] == 'customer'){
					echo "<a href='customersignup.php'>&#10006;</a>";
				}
				else if($_GET['role'] == 'trader'){
					echo "<a href='tradersignup.php'>&#10006;</a>";
				}
                else if($_GET['role'] == 'reset'){
                    echo "<a href='login.php'>&#10006;</a>";
                }
			}
		?>
		<h1>Enter your OTP</h1>
		<p>Enter your confirmation code below:</p>
	    <div class="otp-box">
	      <input class="otp-input" type="number" name='otpnumber' maxlength="6" required />
	    </div>
	    <button class="verify-btn" type="submit" name='okotp'>Verify</button>
	    <button class="resend-btn" type="submit" name='resendotp' >Resend OTP</button>
	</form>
	
</body>
</html>