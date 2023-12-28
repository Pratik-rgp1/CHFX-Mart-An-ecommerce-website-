<?php
session_start();

 include('connection.php');
           
    $gender_error=$firstname_error=$lastname_error=$email_error=$password_error=$confirmpassword_error=$shopname_error=$category_error=$terms_error=$image_error=$password=$confirmpass="";
           
    $errcount=0; 

    $verify = "not";
    $sql = "DELETE FROM TRADER WHERE STATUS = :verify";
    $stmt = oci_parse($conn,$sql);
    oci_bind_by_name($stmt , ":verify" , $verify);
    oci_execute($stmt);
            
    if(isset($_POST['submittrader']))
    {
    
    
            if(empty($_POST['firstname'])){
                $errcount += 1;
                $firstname_error="Firstname is required";
            }
            if(empty($_POST['lastname'])){
                $errcount += 1;
                $lastname_error="lastname is required";
            }
            if(empty($_POST['email'])){
                $errcount += 1;
                $email_error="Email is required";
            }
            if(empty($_POST['password'])){
                $errcount += 1;
                $password_error="Password is required";
            }
            if(empty($_POST['confirmpassword'])){
                $errcount += 1;
                $confirmpassword_error="Confirm password is required";
            }
            if(empty($_POST['firstname'])){
                $errcount += 1;
                $category_error="category is required";
            }
        
            if(empty($_POST['genders'])){
                $errcount += 1;
                $gender_error="Gender is required";
            }
            if(empty( $_POST['terms-and-conditions'])){
                $errcount += 1;
                $terms_error="Terms and condition is required";
            }

            if(empty($_FILES['product_image'] ["name"])){
                $image_error="Please choose  image";
            }
            if($errcount ==0){
                
                $firstname=trim($_POST['firstname']);
                $lastname=trim($_POST['lastname']);
                $email=trim($_POST['email']);
                $password= trim($_POST['password']);
                $confirmpass=trim($_POST['confirmpassword']);
                $gender = $_POST['genders'];
                $catogory=trim($_POST['Trader_shop']);

                $image = $_FILES["product_image"]["name"];
                $utype = $_FILES['product_image']['type'];
                $usize = $_FILES['product_image']['size'];
                $utmpname = $_FILES['product_image']['tmp_name'];
                $ulocation = "../upload/products/".$image;

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

                if(!preg_match("/^[a-zA-z]*$/", $catogory))
                {
                    $errcount += 1;
                    $lastname_error="please enter correct Trader catogory";
                }

                if (!filter_var($semail, FILTER_VALIDATE_EMAIL)) 
                    {
                        $errcount += 1;
                        $email_error = "Invalid email format";
                    }


                    $sql_query = 'SELECT COUNT(*) AS NUMBER_OF_ROWS FROM "TRADER" WHERE TRADER_CATEGORY=:s_name';

                    $data_qu = oci_parse($conn, $sql_query);
                    oci_bind_by_name($data_qu, ':s_name', ucfirst($catogory));
                    oci_define_by_name($data_qu, 'NUMBER_OF_ROWS', $number_of_rows);
                    oci_execute($data_qu);
                    oci_fetch($data_qu);
                    if ($number_of_rows>=1)
                    {
                        $errcount += 1;
                        $category_error = "Category already exists";
                    }
                    else
                    {
                        $Con_catogory=$catogory;
                    }

                    $sql_query = 'SELECT COUNT(*) AS NUMBER_OF_ROWS FROM "TRADER" WHERE  TRADER_EMAIL=:email';

                    $data_que = oci_parse($conn, $sql_query);
                    oci_bind_by_name($data_que, ':email',$email);

                    oci_define_by_name($data_que, 'NUMBER_OF_ROWS', $number_of);

                    oci_execute($data_que);

                    oci_fetch($data_que);
                    
                    if ($number_of>1)
                    {
                        $errcount += 1;
                        $email_error = "Email already exists";
                    }

                    

                }
                if($password == $confirmpass)
                {
                        $uppercase = preg_match('@[A-Z]@', $password);
                        $lowercase = preg_match('@[a-z]@', $password);
                        $number    = preg_match('@[0-9]@', $password);
                        $specialChars = preg_match('@[^\w]@', $password);
                        
                       
                        if($errcount == 0)
                        {

                            // encrypting the password
                            $t_password = md5($password);
                            $status = 'not';
                            $sql = 'INSERT INTO TRADER (FIRST_NAME,LAST_NAME,GENDER,TRADER_EMAIL,TRADER_PASS,TRADER_CATEGORY,STATUS)
                            VALUES (:first_name,:last_name,:gender, :trader_email, :trader_pass,:trader_category,:status)';

                            
                            $stid = oci_parse($conn,$sql);
                            
                           
                            oci_bind_by_name($stid, ':first_name', $firstname);
                            oci_bind_by_name($stid, ':last_name', $lastname);
                            oci_bind_by_name($stid, ':gender',$gender);
                            oci_bind_by_name($stid, ':trader_email', $semail);
                            oci_bind_by_name($stid, ':trader_pass', $t_password);
                            oci_bind_by_name($stid, ':trader_category',$Con_catogory);
                            oci_bind_by_name($stid, ':status', $status);
                           
                            unset($_SESSION['otp']);
                            $_SESSION['email'] = $semail;
                            $otp = random_int(100000,999999);
                            $_SESSION['otp'] = $otp;
                            $sendemail = $semail;
                            $subj="Verification Notification";
                            $body ="DEAR $firstname $lastname, \nYour verification code is :  $otp ";
                            include('email.php');

                            if(oci_execute($stid)){
                                $_SESSION['category'] = $category;

                                $sql = 'INSERT INTO CATEGORY (CATEGORY_NAME,CATEGORY_IMAGE)
                                VALUES (:category_name,:category_image)';
                                $stids = oci_parse($conn,$sql);
                                oci_bind_by_name($stids, ':category_name', $catogory);
                                oci_bind_by_name($stids, ':category_image',$image );
                                oci_execute($stids);
                                header('location:OTPinterface.php?role=trader');
                                echo "<script>alert('You have successfully registered');</script>";
                            }

                           
                            

                        }
                            
                }
                else{
                    $confirmpassword_error="please enter your same  password";
                }
                
            }    
                     
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader SignUp</title>
    <link rel="stylesheet" type="text/css" href="css/tradersignups.css">
</head>
<body>

    <div class="container">
        <div class="title">Trader SignUp</div>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="user-details">

            <div class="input-box">
                    <span class="details">Firstname</span>
                    <input type="text" placeholder="Enter firstname" name="firstname" >
                    <p class="error password-error">
                        <?php echo $firstname_error;?></p>
                </div>

                <div class="input-box">
                    <span class="details">Lastname</span>
                    <input type="text" placeholder="Enter lastname"  name="lastname">
                    <p class="error password-error"><?php echo $lastname_error;?>
                </div>
                
                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="text" placeholder="Enter your email"  name="email">
                    <p class="error password-error"><?php echo $email_error;?>
                </div>
               
                <div class="input-box">
                    <span class="details">Password</span>
                    <input type="password" placeholder="Enter your password" name="password">
                    <p class="error password-error"><?php echo $password_error;?>
                </div>
                <div class="input-box">
                    <span class="details">Confirm Password</span>
                    <input type="password" placeholder="Enter your password" name="confirmpassword">
                    <p class="error password-error"><?php echo $confirmpassword_error;?>
                </div>

                <div class="gender-details">
                    <input type="radio" name="genders" id="dot-1" value="Male" >
                    <input type="radio" name="genders" id="dot-2" value="Female" >
                    <input type="radio" name="genders" id="dot-3" value="Other" >
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
                            <span class="gender">Other </span>
                        </label>
                        
                    </div>
                    <p class="error password-error"><?php echo $gender_error;?>
                    

                <div class="input-box">
                <span class="details">Category for </span>
                    <input type="text" placeholder="Shop for" name="Trader_shop">
                        <p class="error password-error"><?php echo $category_error;?> 
                </div>

                <div class="mb-3">
               <label class="form-label">Product Image: </label>
               <input type="file" id="myFile" name="product_image"> <br/>
               <p class="error password-error"><?php echo $image_error;?>
            </div>

               
                    <div class="form-group terms-and-conditions">
                        <input type="checkbox" id="terms-and-conditions" name="terms-and-conditions">
                        <label for="terms-and-conditions">I agree to the <a href="terms.php" target="_blank">Terms and Conditions</a></label>
                        <p class="error password-error"><?php echo $terms_error;?> 
                    </div>

                </div>

                <div class="button">
                    <input type="Submit" value="Register" name ="submittrader">
                    <span>Already have an account? <a href="login.php">Login</a></span>  

                </div> 
                
        </form>
    </div>
</body>
</html>