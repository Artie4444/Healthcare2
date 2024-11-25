<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting...</title>
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

        p {
            font-size: 18px;
            margin-bottom: 20px;
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
    </style>
</head>
<body>
    <div id="container">
        <p>Logging Out in 5 Seconds</p>
        <meta http-equiv="refresh" content="5;url=logout_meta.php">
        <p>If it doesn't, <a href="logout_meta.php">Click Here</a>.</p>
    </div>
</body>
</html>
