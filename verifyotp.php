<?php
session_start();
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
               
                echo "<script>alert('You are successfylly verified')</script>";
                
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
    
    header('location:verifyotp.php');
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    
<form action="" method='post'>

<div class="mb-3">
  <label for="formGroupExampleInput" class="form-label">Verification OTP</label>
  <input type="number"  name='otpnumber' class="form-control" id="formGroupExampleInput" placeholder="Example input placeholder">
</div>

    <div class="modal-footer">
        <button type="submit" name='resendotp' class="btn btn-secondary" data-bs-dismiss="modal">Resend</button>
        <button type="submit" name='okotp' class="btn btn-primary">OK</button>
    </div>

</form>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>
</html>