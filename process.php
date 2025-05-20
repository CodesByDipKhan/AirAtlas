<?php
$message = "";
$showData = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['cancel'])) {
        header("Location: index.html");
        exit();
    }

    if (isset($_POST['confirm'])) {
        $name = $_POST['uName'] ?? '';
        $email = $_POST['uEmail'] ?? '';
        $password = $_POST['uPass'] ?? '';
        $dob = $_POST['uDOB'] ?? '';
        $gender = $_POST['uGender'] ?? '';
        $opinion = $_POST['uTxtArea'] ?? '';
        $color = $_POST['uColor'] ?? '';
        $cookieName = "bgcol_" . md5($email);

        setcookie($cookieName, $color, time() + (86400 * 30), "/");

        $conn = new mysqli("localhost", "root", "", "AQI");
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        $stmt = $conn->prepare("INSERT INTO User (Name, Email, Password, DOB, Gender, Opinion) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $password, $dob, $gender, $opinion);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        $message = "Data saved and cookie set!";
        echo "<script>
            alert('$message');
            window.location.href = 'index.html';
        </script>";
        exit();
    }

    $name = $_POST['uName'] ?? '';
    $email = $_POST['uEmail'] ?? '';
    $password = $_POST['uPass'] ?? '';
    $dob = $_POST['uDOB'] ?? '';
    $country = $_POST['uCountry'] ?? '';
    $gender = $_POST['uGender'] ?? '';
    $color = $_POST['uColor'] ?? '';
    $opinion = $_POST['uTxtArea'] ?? '';

    $showData = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Server Site</title>
    <link rel="stylesheet" href="process.css">
</head>
<body>
    <h3>Registration Information</h3><hr>

    <?php if ($showData): ?>
    <table>
        <tr><th>Name</th><td><?= htmlspecialchars($name) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($email) ?></td></tr>
        <tr><th>Date of Birth</th><td><?= htmlspecialchars($dob) ?></td></tr>
        <tr><th>Country</th><td><?= htmlspecialchars($country) ?></td></tr>
        <tr><th>Gender</th><td><?= htmlspecialchars($gender) ?></td></tr>
        <tr><th>Opinion</th><td><?= nl2br(htmlspecialchars($opinion)) ?></td></tr>
    </table>

    <form method="post">
        <input type="hidden" name="uName" value="<?= htmlspecialchars($name) ?>">
        <input type="hidden" name="uEmail" value="<?= htmlspecialchars($email) ?>">
        <input type="hidden" name="uPass" value="<?= htmlspecialchars($password) ?>">
        <input type="hidden" name="uDOB" value="<?= htmlspecialchars($dob) ?>">
        <input type="hidden" name="uCountry" value="<?= htmlspecialchars($country) ?>">
        <input type="hidden" name="uGender" value="<?= htmlspecialchars($gender) ?>">
        <input type="hidden" name="uColor" value="<?= htmlspecialchars($color) ?>">
        <input type="hidden" name="uTxtArea" value="<?= htmlspecialchars($opinion) ?>">

        <button type="submit" name="cancel">Cancel</button>
        <button type="submit" name="confirm">CONFIRM</button>
    </form>
    <?php else: ?>
        <p>No Data Submitted</p>
    <?php endif; ?>
</body>
</html>
