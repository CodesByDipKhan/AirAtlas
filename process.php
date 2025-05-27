<?php
session_start();
$message = "";
$showData = false;
$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['cancel'])) {
       
        unset($_SESSION['form_data']);
        header("Location: index.html");
        exit();
    }

    if (isset($_POST['confirm'])) {
       
        if (!isset($_SESSION['form_data'])) {
            $error = "Session expired or no data to confirm.";
        } else {
            $form = $_SESSION['form_data'];
            $name = $form['uName'];
            $email = $form['uEmail'];
            $raw_password = $form['uPass'];
            $dob = $form['uDOB'];
            $gender = $form['uGender'];
            $opinion = $form['uTxtArea'];
            $color = $form['uColor'];

            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            } elseif (strlen($raw_password) < 8) {
                $error = "Password must be at least 8 characters.";
            }
                if (empty($error)) {
    
                $password = password_hash($raw_password, PASSWORD_DEFAULT);

   
                $cookieName = 'bgcol_' . md5(strtolower($email));
                setcookie($cookieName, $color, time() + 86400 * 30, '/');



               
                $conn = new mysqli("localhost", "root", "", "aqi");
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


            $sql = "SELECT 1 FROM User WHERE Email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                 $error = "Email already registered.";
            } else {
     
             $sql = "INSERT INTO User (Name, Email, Password, DOB, Gender, Opinion) VALUES (?, ?, ?, ?, ?, ?)";
             $stmt = $conn->prepare($sql);
             $stmt->bind_param("ssssss", $name, $email, $password, $dob, $gender, $opinion);

            if ($stmt->execute()) {
             $_SESSION['temp_user'] = ['name' => $name, 'email' => $email];
             unset($_SESSION['form_data']); // Clear session data
             echo "<script>alert('Registration successful!'); window.location.href = 'index.html';</script>";
             exit();
           } else {
             $error = "Registration failed. Please try again.";
         }
        }

        $stmt->close();
        $conn->close();
            }
        }
    } else {
        
        $name = htmlspecialchars(trim($_POST['uName'] ?? ''));
        $email = filter_var(trim($_POST['uEmail'] ?? ''), FILTER_SANITIZE_EMAIL);
        $raw_password = $_POST['uPass'] ?? '';
        $dob = $_POST['uDOB'] ?? '';
        $country = htmlspecialchars($_POST['uCountry'] ?? '');
        $gender = htmlspecialchars($_POST['uGender'] ?? '');
        $color = $_POST['uColor'] ?? '#ffffff';
        $opinion = htmlspecialchars(trim($_POST['uTxtArea'] ?? ''));

        
        $_SESSION['form_data'] = [
            'uName' => $name,
            'uEmail' => $email,
            'uPass' => $raw_password,
            'uDOB' => $dob,
            'uCountry' => $country,
            'uGender' => $gender,
            'uColor' => $color,
            'uTxtArea' => $opinion
        ];

        $showData = true;
    }
} else {
    $error = "No data submitted.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration Confirmation</title>
    <link rel="stylesheet" href="process.css">
</head>
<body>
    <h3>Registration Information</h3><hr>

    <?php if (!empty($error)): ?>
        <div class="error-message" style="color:red; font-weight:bold;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($showData && isset($_SESSION['form_data'])):
        $form = $_SESSION['form_data']; ?>
        <table>
            <tr><th>Name</th><td><?= htmlspecialchars($form['uName']) ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($form['uEmail']) ?></td></tr>
            <tr><th>Date of Birth</th><td><?= htmlspecialchars($form['uDOB']) ?></td></tr>
            <tr><th>Country</th><td><?= htmlspecialchars($form['uCountry']) ?></td></tr>
            <tr><th>Gender</th><td><?= htmlspecialchars($form['uGender']) ?></td></tr>
            <tr><th>Opinion</th><td><?= nl2br(htmlspecialchars($form['uTxtArea'])) ?></td></tr>
        </table>

        <form method="post">
          
            <button type="submit" name="cancel">Cancel</button>
            <button type="submit" name="confirm">CONFIRM</button>
        </form>
    <?php else: ?>
        <p>No Data Submitted</p>
    <?php endif; ?>
</body>
</html>