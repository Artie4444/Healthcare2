<?php
session_start();

include 'conn.php';
if (!isset($_SESSION['temp_user'])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];
    $stored_otp = $_SESSION['temp_user']['otp'];
    $user_id = $_SESSION['temp_user']['id'];

    $sql = "SELECT * FROM users WHERE id='$user_id' AND otp='$user_otp'";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($query);

    if ($data) {
        $otp_expiry = strtotime($data['otp_expiry']);
        if ($otp_expiry >= time()) {
            $_SESSION['user_id'] = $data['id'];
            unset($_SESSION['temp_user']);
            header("Location: dashboard.php");
            exit();
        } else {
            ?>
                <script>
    alert("OTP has expired. Please try again.");
    function navigateToPage() {
        window.location.href = 'index.php';
    }
    window.onload = function() {
        navigateToPage();
    }
</script>
            <?php 
        }
    } else {
        echo "<script> alert('Invalid OTP. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>2FA - Web3 Healthcare</title>
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

        form {
            margin-top: 20px;
            text-align: center;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 20px;
            font-weight: bold;
        }

        input[type="number"] {
            width: 290px;
            padding: 10px;
            margin-top: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: orange;
            border: 1px solid orange;
            width: 100px;
            padding: 9px;
            margin-top: 20px;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>Two-Step Verification</h1>
        <p>Enter the 6 Digit OTP Code that has been sent to your email address: <?php echo $_SESSION['email']; ?></p>
        <form method="post" action="otp_verification.php">
            <label style="font-weight: bold; font-size: 18px;" for="otp">Enter OTP Code:</label><br>
            <input type="number" name="otp" pattern="\d{6}" placeholder="Six-Digit OTP" required><br>
            <button type="submit">Verify OTP</button>
        </form>
    </div>
</body>
</html>