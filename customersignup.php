<?php
session_start();

 include('connection.php');
            
            $gender_error=$firstname_error=$lastname_error=$email_error=$password_error=$confirmpass_error=$phonenumber_error=$terms_error="";
           $errcount=0; 

           $verify = "pending";
           $sql = "DELETE FROM CUSTOMER WHERE STATUS = '$verify'";
           $stmt = oci_parse($conn,$sql);
        
           oci_execute($stmt);
    if(isset($_POST['register']))
    {
   
	       
            if(empty($_POST['firstname'])){
                $firstname_error="Firstname is required";
            }
            if(empty($_POST['lastname'])){
                $lastname_error="lastname is required";
            }
            if(empty($_POST['email'])){
                $email_error="Email is required";
            }
            if(empty($_POST['password'])){
                $password_error="Password is required";
            }
            if(empty($_POST['confirmpassword'])){
                $confirmpass_error="Confirm password is required";
            }
            if(empty($_POST['genders'])){
                $gender_error="Gender is required";
            }
            if(empty($_POST['phonenumber'])){
                $phonenumber_error="Phone number is required";
            }
            if(empty( $_POST['terms-and-conditions'])){
                $terms_error="Terms and condition is required";
            }
            else{
                $firstname=trim($_POST['firstname']);
                $lastname=trim($_POST['lastname']);
                $email=trim($_POST['email']);
                $phonenumber=trim($_POST['phonenumber']);
                $password= trim($_POST['password']);
                $confirmpass=trim($_POST['confirmpassword']);
                $gender = $_POST['genders'];

                $semail = filter_var($email,FILTER_SANITIZE_EMAIL);
                
                if(!preg_match("/^[a-zA-z]*$/", $firstname))
                {   
                    $errcount += 1;
                    $firstname_error="please enter correct first  name";
                }

                if(!preg_match("/^[a-zA-z]*$/", $lastname))
                {
                    $errcount += 1;
                    $lastname_error="please enter correct name";
                }

                if (!filter_var($semail, FILTER_VALIDATE_EMAIL)) 
                    {
                        $errcount += 1;
                        $email_error = "Invalid email format";
                    }
                
                    
                    $sql = "SELECT * FROM CUSTOMER WHERE CUST_EMAIL= :email OR PHONE = :phone ";
                    $stid1 = oci_parse($conn, $sql);
    
                    oci_bind_by_name($stid1,':email' ,$semail);
                    oci_bind_by_name($stid1,':phone' ,$phonenumber);
                    oci_execute($stid1);
                    
                    $demail=$dphone='';
    
                    while($row = oci_fetch_array($stid1,OCI_ASSOC)){
                        $demail = $row['CUST_EMAIL'];
                        $dphone = $row['PHONE'];
                    }
                    
    
                if($semail == $demail)
                    {
                        $errcount += 1;
                        $email_error = "Email already exists";
                    }
                    if($phonenumber == $dphone)
                    {
                        $errcount += 1;
                        $phonenumber_error = "Phone number already exists";
                    }
                    // phone number validation
                   if(strlen($phonenumber) < 10 || strlen($phonenumber) >10){
                        $errcount+=1;
                        $phonenumber_error = "Phone number length should be 10";
                    }

                if(!preg_match("/^9[0-9]{9}$/", $phonenumber)){
                    $errcount+=1;
                    $phonenumber_error = "Phone number is not valid. Please enter a valid Phone number";
                }
                    
                if($password == $confirmpass)
                {
                        $uppercase = preg_match('@[A-Z]@', $password);
                        $lowercase = preg_match('@[a-z]@', $password);
                        $number    = preg_match('@[0-9]@', $password);
                        $specialChars = preg_match('@[^\w]@', $password);
                        
                        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                            $errcount+=1;
                            $password_error= 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
                        }
                        if($errcount == 0){
                            
                            $t_password = md5($password);
                            $phone=(int)$phonenumber;
                            $verify = 'pending';
                            $sql = 'INSERT INTO CUSTOMER (FIRST_NAME,LAST_NAME,GENDER,PHONE,CUST_EMAIL,CUST_PASS,STATUS)
                            VALUES (:first_name,:last_name,:gender, :phone,:cust_email, :cust_pass,:verify)';
                            

                            $stid = oci_parse($conn,$sql);
                            oci_bind_by_name($stid, ':user_id', $t_id);
                            oci_bind_by_name($stid, ':first_name', $firstname);
                            oci_bind_by_name($stid, ':last_name', $lastname);
                            oci_bind_by_name($stid, ':gender',$gender);
                            oci_bind_by_name($stid, ':cust_email', $semail);
                            oci_bind_by_name($stid, ':cust_pass', $t_password);
                            oci_bind_by_name($stid, ':phone',  $phone);
                            oci_bind_by_name($stid, ':verify',  $verify);

                            unset($_SESSION['otp']);
                            unset($_SESSION['email']);
                            $_SESSION['email'] = $semail;
                            $otp = random_int(100000,999999);
                            $_SESSION['otp'] = $otp;
                            $sendemail = $semail;
                            $subj="Verification Notification";
                            $body ="DEAR $firstname $lastname, \nYour verification code is :  $otp ";
                            include('email.php');

                            if(oci_execute($stid)){
                                
                                header('location:OTPinterface.php?role=customer');
                            }
                        }          
                }
                else{
                    $confirmpassword_error="please enter your same  password";
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
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/customersignups.css">
</head>
<body>
<div class="container">
        <div class="title">
           Customer Registration
        </div>
        <form action="#" method="POST">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">First Name</span>
                    <input type="text" placeholder="Enter your name" name="firstname">
                    <p class="error password-error"><?php echo $firstname_error;?></p>
                    
                </div>

                <div class="input-box">
                    <span class="details">Last Name</span>
                    <input type="text" placeholder="Enter your name" name="lastname">
                    <p class="error password-error"><?php echo $lastname_error;?></p>
                    
                </div>
                
                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="text" placeholder="Enter your email" name="email">
                    <p class="error password-error"><?php echo $email_error;?></p>
                </div>
                <div class="input-box">
                    <span class="details">Phone Number</span>
                    <input type="text" placeholder="Enter your number" name="phonenumber">
                    <p class="error password-error"><?php echo $phonenumber_error;?></p>
                </div>
                <div class="input-box">
                    <span class="details">Password</span>
                    <input type="password" placeholder="Enter your password" name="password">
                    <p class="error password-error"><?php echo $password_error;?></p>
                </div>
                <div class="input-box">
                    <span class="details">Confirm Password</span>
                    <input type="password" placeholder="Enter your password" name="confirmpassword">
                    <p class="error password-error"><?php echo $confirmpass_error;?></p>
                </div>
                <div class="gender-details">
                    <input type="radio" name="genders" id="dot-1" value="male">
                    <input type="radio" name="genders" id="dot-2" value="female">
                    <input type="radio" name="genders" id="dot-3" value="others">
                    <span class="gender-title">Gender</span>
                    <div class="category">
                        <label for="dot-1">
                            <span class="dot one"></span>
                            <span class="gender">Male</span>
                        </label>
                        <label for="dot-2">
                            <span class="dot two"></span>
                            <span class="gender">Female</span>
                        </label>
                        <label for="dot-3">
                            <span class="dot three"></span>
                            <span class="gender">Other Options</span>
                        </label>
                        <p class="error password-error"><?php echo $gender_error;?></p>
                    </div>

                    <div class="form-group terms-and-conditions">
                        <input type="checkbox" id="terms-and-conditions" name="terms-and-conditions">
                        <label for="terms-and-conditions">I agree to the <a href="terms.php" target="_blank">terms and conditions</a></label>
                        <p class="error password-error"><?php echo $terms_error;?></p>
                    </div>

                </div>
                <div class="button">
                    <input type="Submit" value="Register" name="register">
                    <span>Already have an account? <a href="login.php">Login</a></span>  

                </div> 
                
        </form>
    </div>
</body>
</html>

