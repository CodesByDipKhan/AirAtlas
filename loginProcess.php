<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['loginEmail'] ?? '';
    $password = $_POST['loginPassword'] ?? '';

    $conn = new mysqli("localhost", "root", "", "AQI");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT Name FROM User WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($name);
        $stmt->fetch();

        header("Location: index.html");
        exit();
    } else {

        echo "<script> alert('Invalid email or password!');
        window.location.href = 'index.html';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
