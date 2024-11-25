<?php
session_start();
include 'conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email']=$email;

    $sql = "SELECT * FROM users WHERE email='$email'";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($query);

    if ($data && password_verify($password, $data['password'])) {
        $otp = rand(100000, 999999);
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+3 minute"));
        $subject= "Your OTP for Login";
        $message="Your OTP is: $otp";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
            );      
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'web3healthcaregroupnineteen@gmail.com'; //host email 
        $mail->Password = 'udrdjpcxeesgqlpc'; // app password of your host email
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->isHTML(true);
        $mail->setFrom('web3healthcaregroupnineteen@gmail.com', 'Web3 Healthcare');//Sender's Email & Name
        $mail->addAddress($email,$name); //Receiver's Email and Name
        $mail->Subject = ("$subject");
        $mail->Body = $message;
        $mail->send();

        $sql1 = "UPDATE users SET otp='$otp', otp_expiry='$otp_expiry' WHERE id=".$data['id'];
        $query1 = mysqli_query($conn, $sql1);

        $_SESSION['temp_user'] = ['id' => $data['id'], 'otp' => $otp];
        header("Location: otp_verification.php");
        exit();
    } else {
        ?>
        <script>
           alert("Invalid Email or Password. Please try again.");
                function navigateToPage() {
                    window.location.href = 'index.php';
                }
                window.onload = function() {
                    navigateToPage();
                }
        </script>
        <?php 
    
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Web3 Healthcare</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-image: url('/bgimage.jpg');
            background-size: cover;
        }

        #container {
            border: 1px solid #ccc;
            background-color: #fff;
            width: 450px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            height: 40px;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        label {
            font-size: 18px;
            font-weight: bold;
        }

        form {
            text-align: left;
        }

        a {
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            color: #3498db;
        }

        a:hover {
            color: #2980b9;
        }

        input[type="submit"] {
            width: 100%;
            background-color: Orange;
            border: 1px solid Orange;
            color: white;
            font-weight: bold;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div id="container">
        <form method="post" action="index.php">
            <label for="email">Email</label><br>
            <input type="text" name="email" placeholder="Enter Your Email" required><br>

            <label for="password">Password</label><br>
            <input type="password" name="password" placeholder="Enter Your Password" required><br>

            <input type="submit" name="login" value="Login">
            <label>Don't have an account? </label><a href="registration.php">Sign Up</a>
        </form>
    </div>
</body>
</html>