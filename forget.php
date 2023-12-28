<?php
session_start();
include('connection.php');

$error_message = "";

if(isset($_POST['emailsend'])){
	$email = $_POST['email'];
	$role = $_POST['role'];

	if($role == 'customer'){
        $sql = "SELECT * FROM CUSTOMER WHERE CUST_EMAIL= :email ";
        }
    if($role == 'trader'){
        $sql = "SELECT * FROM TRADER WHERE TRADER_EMAIL= :email ";
        }

		$stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':email', $email);
        

		unset($_SESSION['otp']);

		$_SESSION['email'] = $email;
		$otp = random_int(100000,999999);
		$_SESSION['otp'] = $otp;
		$sendemail = $email;
		$subj="Password Reset Notification";
		$body ="DEAR $firstname $lastname, \nYour verification code is :  $otp ";
		include('email.php');

		if(oci_execute($stid)){
			header("location:OTPinterface.php?role=reset&user=$role");
		}
		else{
			$error_message = "Provide your verified email.";
		}

	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="css/forgets.css">
</head>
<body>
    <div class="container">
		<form method='post' action=''>
			<h2>Forgot Password</h2>
			<p>Please enter your email address and we'll send you instructions on how to reset your password.</p>
			<label>Select your role
			<select name='role' class="role" >
                    <option value=''>Select Role</option>
                    <option value='trader'>Trader</option>
                    <option value='customer' >Customer</option>
                </select>
			</label>
         		
			<label>Email:</label>
			<input type="text" placeholder="Enter your email address" name="email" required>
			<button type="submit" name='emailsend' >Submit</button>
		</form>
	</div>
    
</body>
</html> 
