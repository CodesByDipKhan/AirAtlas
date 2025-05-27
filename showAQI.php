<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.html");
    exit();
}

if (!isset($_SESSION['selected_cities']) || !is_array($_SESSION['selected_cities']) || count($_SESSION['selected_cities']) === 0) {
    echo "No cities selected.";
    exit();
}

$conn = new mysqli("localhost", "root", "", "aqi");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$selectedCities = $_SESSION['selected_cities'];
$placeholders = implode(',', array_fill(0, count($selectedCities), '?'));
$stmt = $conn->prepare("SELECT City, Country, Aqi FROM info WHERE City IN ($placeholders)");

$types = str_repeat('s', count($selectedCities));
$stmt->bind_param($types, ...$selectedCities);
$stmt->execute();
$result = $stmt->get_result();

$cookieName = "bgcol_" . md5(strtolower($_SESSION['user_email'] ?? ''));
$bgcolor = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : '#ffffff';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Selected Cities AQI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: <?= htmlspecialchars($bgcolor) ?>;
            padding: 20px;
        }
        .table-container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #807bff;
            color: white;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .actions {
            text-align: center;
            margin-top: 20px;
        }
        .actions a {
            display: inline-block;
            margin: 0 10px;
            padding: 8px 14px;
            background-color: #807bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="table-container">
    <h2>Air Quality Index (AQI) for Selected Cities</h2>
    <?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>City</th>
            <th>Country</th>
            <th>AQI</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['City']) ?></td>
                <td><?= htmlspecialchars($row['Country']) ?></td>
                <td><?= htmlspecialchars($row['Aqi']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>No AQI data found for selected cities.</p>
    <?php endif; ?>
    <div class="actions">
        <a href="requestAQI.php">Back to Selection</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>