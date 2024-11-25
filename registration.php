<?php
session_start();
include 'conn.php';

function checkPasswordStrength($password) {
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChar = preg_match('@[^\w]@', $password);
    
    $minLength = 8;
    
    if ($uppercase && $lowercase && $number && $specialChar && strlen($password) >= $minLength) {
        return true;
    } else {
        return false;
    }
}

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$strengthMessage = "";
$registrationMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!checkPasswordStrength($password)) {
        $strengthMessage = "Password must meet strength requirements with at least 8 characters, a mix of upper and lower characters, and at least one symbol.";
    } else {
        $checkUsernameQuery = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $checkUsernameQuery);

        if (mysqli_num_rows($result) > 0) {
            $registrationMessage = "Username already exists. Please choose a different username.";
        } else {
            // password_hash does both salting and hashing in the sql database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
            $query = mysqli_query($conn, $sql);

            if ($query) {
                ?>
                <script>
                    alert("Registration Successful.");
                    function navigateToPage() {
                        window.location.href = 'index.php';
                    }
                    window.onload = function() {
                        navigateToPage();
                    }
                </script>
                <?php
            } else {
                echo "<script>alert('Registration Failed. Try Again');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration - Web3 Healthcare</title>
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
            text-align: left;
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

        .password-message,
        .registration-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div id="container">
        <form method="post" action="registration.php">
            <label for="username">Username:</label><br>
            <input type="text" name="username" placeholder="Enter Username" required><br><br>

            <label for="email">Email:</label><br>
            <input type="text" name="email" placeholder="Enter Your Email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" placeholder="Enter Password" required>
            <div class="password-message"><?php echo $strengthMessage; ?></div>
            <div class="registration-message"><?php echo $registrationMessage; ?></div>
            <br><br>

            <input type="submit" name="register" value="Register"><br><br>
            <label>Already have an account? </label><a href="index.php">Login</a>
        </form>
    </div>
</body>
</html>