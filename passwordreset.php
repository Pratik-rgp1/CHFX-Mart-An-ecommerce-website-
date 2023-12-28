<?php
    session_start();
    include('connection.php'); 

    $errotp ='';
    $errcount=0;
    $stid="";


    if(isset($_POST['resetpassword'])){
        if(empty($_POST['password'])){
            $errotp ="Password Should not be empty";
        }
        if(empty($_POST['repassword'])){
            $errotp ="Confirm Password Should not be empty";
        }
        else{

            $newpass = $_POST['password'];
            $repass = $_POST['repassword'];

            $uppercase = preg_match('@[A-Z]@',$newpass);
            $lowercase = preg_match('@[a-z]@',$newpass);
            $number = preg_match('@[0-9]@',$newpass);
            $specialChars = preg_match('@[^\w]@',$newpass);

            if($newpass == $repass){
                if(!$uppercase){
                    $errcount+=1;
                    $errpassword="Password should include at least one upper case letter.";
                }
                if(!$lowercase){
                    $errcount+=1;
                    $errpassword="Password should include at least one lower case letter.";
                }
                if(!$specialChars){
                    $errcount+=1;
                    $errpassword="Password should include at least one special character.";
                }
                if(!$number){
                    $errcount+=1;
                    $errpassword="Password should include at least one number.";
                }
                if($errcount==0){
                    $password = md5($newpass);

                    if($_SESSION['user_type']  == 'customer'){
                        $sql = "UPDATE CUSTOMER SET CUST_PASS= :upassword WHERE CUST_EMAIL= :email";
                        $stid = oci_parse($conn,$sql);
                        oci_bind_by_name($stid , ':email' ,$_SESSION['email']);
                    }
                    if($_SESSION['user_type']  == 'trader'){
                        $sql = "UPDATE TRADER SET TRADER_PASS= :upassword WHERE TRADER_EMAIL= :email";
                        $stid = oci_parse($conn,$sql);
                        oci_bind_by_name($stid , ':email' ,$_SESSION['email']);
                    }
                    
                    oci_bind_by_name($stid , ':upassword' ,$password);

                    if(oci_execute($stid)){
                        header('location:login.php');
                    }
                }
            }
            else{
                $errotp = "Password doesnot match";
            }
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
    <link rel="stylesheet" href="css/passwordresets.css">
</head>
<body>
    <div class="container">
		<form method='post' action=''>
            <h2>Reset Password</h2>
            <label>New Password:</label>
			<input type="password" placeholder="Enter your password" name="password" required>
   		
			<label>Confirm Password:</label>
			<input type="password" placeholder="Re enter your Password" name="repassword" required>
        
			<button type="submit" name='resetpassword' >Submit</button>
		</form>
	</div>
    
</body>
</html> 

 