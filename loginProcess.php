<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['loginEmail'] ?? '';
    $password = $_POST['loginPassword'] ?? '';

    $conn = mysqli_connect("localhost", "root", "", "aqi");
    if (!$conn) {
        die("Connection failed.");
    }

    $email = strtolower(trim($email));
    $query = "SELECT UserID, Name, Password FROM User WHERE LOWER(Email) = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['Password'])) {
            $_SESSION['user_id'] = $row['UserID'];
            $_SESSION['user_name'] = $row['Name'];
            $_SESSION['user_email'] = $email;
            $_SESSION['logged_in'] = true;

            header("Location: requestAQI.php");
            exit();
        } else {
            echo "Wrong password.";
        }
    } else {
        echo "Email not found.";
    }

    mysqli_close($conn);
}
?>
