<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.html");
    exit();
}

$userInfo = null;

if (isset($_SESSION['username'])) {
    $conn = new mysqli("localhost", "root", "", "aqi");

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Prepare query using the correct column name "Name"
    $stmt = $conn->prepare("SELECT Name, Email, DOB, Gender, Opinion FROM user WHERE Name = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $userInfo = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }
        .profile-container {
            width: 450px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        p {
            font-size: 18px;
            margin: 12px 0;
        }
        .back-button {
            text-align: center;
            margin-top: 20px;
        }
        .back-button a {
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .back-button a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>User Profile</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($userInfo['Name'] ?? 'N/A') ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($userInfo['Email'] ?? 'N/A') ?></p>
    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($userInfo['DOB'] ?? 'N/A') ?></p>
    <p><strong>Gender:</strong> <?= htmlspecialchars($userInfo['Gender'] ?? 'N/A') ?></p>
    <p><strong>Opinion:</strong> <?= htmlspecialchars($userInfo['Opinion'] ?? 'N/A') ?></p>

    <div class="back-button">
        <a href="requestAQI.php">⬅ Back</a>
    </div>
</div>

</body>
</html>
