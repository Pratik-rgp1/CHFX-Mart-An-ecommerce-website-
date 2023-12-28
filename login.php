<?php
session_start();
include('connection.php');
$t_email=$t_password=$t_role= " ";
$email_error=$password_error=$role_error=$err=" ";


// Process login form submission
if (isset($_POST['submitlogin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate the form data
    $email_error = $password_error = $role_error = $err= "";
    $valid = true;

    if (empty($email)) {
        $email_error = "Email is required";
        $valid = false;
    }

    if (empty($password)) {
        $password_error = "Password is required";
        $valid = false;
    }

    if (empty($role)) {
        $role_error = "Role is required";
        $valid = false;
    }

    // SQL query to validate user credentials
    else{

    $password=md5($password);

    $verify = "verified";
        if($role == 'customer'){
        $sql = "SELECT * FROM CUSTOMER WHERE CUST_EMAIL= :email AND CUST_PASS = :password AND STATUS= :verify ";
        }
        else if($role == 'trader'){
        $sql = "SELECT * FROM TRADER WHERE TRADER_EMAIL= :email AND TRADER_PASS = :password AND STATUS= :verify ";
        }
        else if($role == 'admin'){
        $sql = "SELECT * FROM USERS WHERE ADMIN_EMAIL = :email AND ADMIN_PASS = :password ";
        }

        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':email', $email);
        oci_bind_by_name($stid, ':password', $password);
        if($role != 'admin'){
            oci_bind_by_name($stid, ':verify', $verify);
        }
        oci_execute($stid);
        


        // If user credentials are valid, redirect to dashboard
        if ($data=oci_fetch_array($stid,OCI_ASSOC)) {
            if($role === 'customer'){
                $_SESSION['cust_id']=$data['CUST_ID'];
                $cust_id = $_SESSION['cust_id'];
                
                $sql = "SELECT * FROM USERS WHERE CUST_ID = '$cust_id' ";
                $stid = oci_parse($conn,$sql);
                oci_execute($stid);
                $row=oci_fetch_array($stid,OCI_ASSOC);
                $user_id = $row['USER_ID'];
                $_SESSION['user_id'] = $user_id;

                if(isset($_GET['pid']))
                    {
                        $product_id=$_GET['pid'];
                        header("location:productview.php?pid=$product_id");
                       
                    }
                else
                {

                    header('location:home.php');
                }
            }
            else if($role === 'trader'){
                $_SESSION['trader_id']=$data['TRADER_ID'];
                $_SESSION['category_type'] = $data['TRADER_CATEGORY'];
                $trader_id = $_SESSION['trader_id'];

                $sql = "SELECT * FROM USERS WHERE TRADER_ID = '$trader_id' ";
                $stid = oci_parse($conn,$sql);
                oci_execute($stid);
                $row=oci_fetch_array($stid,OCI_ASSOC);
                $user_id = $row['USER_ID'];
                $_SESSION['user_id'] = $user_id;
                header('location:Trader/trader.php');
            }
            else if($role === 'admin'){
                $_SESSION['admin_id']=$data['USER_ID'];
                header('location:admin/admin.php');
            }
        } else {
            $err = "Invalid login credentials.";
        }
    }
}
?>


<!DOCTYPE html>     
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">



    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    

    
    <form action="" method="POST">
        <div class="login-container">
           <h2>Login</h2> 
        </div>
        <p class="error password-error"><?php echo $err;?>
        <div>
            <label>Email</label><br>
            <input type="email" name="email">
            <p class="error password-error"><?php echo $email_error;?>
        </div>

        <div>
            <label>Password</label><br>
            <input type="password" name="password">
            <p class="error password-error"><?php echo $password_error;?>
        </div>

        <label>Role</label>
         <select name='role' class="role" >
                    <option value=''>Select Role</option>
                     <option value='admin' >Admin</option>
                    <option value='trader' >Trader</option>
                    <option value='customer'>Customer</option>
                </select><br><br>
                <p class="error password-error"><?php echo $role_error;?>

        <label>Remember me</label>
        <input type="checkbox" checked="checked" name="remember"> 
        </label>

        <div>
            <input type="submit" name="submitlogin" value="Login">
            <p class="forgot-password"><a href="forget.php">Forgot Password?</a></p>
            </div>
           
        </div>

       <span>
           Not yet registered as customer?<a href="customersignup.php">Create an account</a>
       </span>
       <span>
           Not yet registered as trader?<a href="tradersignup.php">Create an account</a>
       </span>
    </form>

</body>
</html>