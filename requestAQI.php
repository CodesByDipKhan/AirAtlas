<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.html");
    exit();
}

$cities = [
    "Lahore", "Cairo", "Chengdu", "Dubai", "Ho Chi Minh City",
    "Jakarta", "Shanghai", "Kuwait City", "Santiago", "Kinshasa",
    "Johannesburg", "Dakar", "Delhi", "Kathmandu", "Riyadh",
    "Jerusalem", "Wuhan", "Medan", "Kolkata", "Dhaka"
];

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel'])) {
        header("Location: index.html");
        exit();
    }

    if (!isset($_POST['cities']) || count($_POST['cities']) < 1 || count($_POST['cities']) > 10) {
        $error = "Please select between 1 and 10 cities.";
    } else {
        $selected = array_intersect($cities, $_POST['cities']);
        if (count($selected) < 1 || count($selected) > 10) {
            $error = "Invalid city selection.";
        } else {
            $_SESSION['selected_cities'] = $selected;
            header("Location: showAQI.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Select Cities - AQI Request</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 700px;
            margin: 60px auto;
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }
        h2 {
            text-align: center;
            color: #444;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin: 10px 0;
            padding: 10px 15px;
            border-radius: 8px;
            background: #f0f0f0;
            transition: background 0.3s ease;
            cursor: pointer;
        }
        label:hover {
            background: #e2e2ff;
        }
        input[type="checkbox"] {
            margin-right: 12px;
            transform: scale(1.2);
        }
        .buttons {
            text-align: center;
            margin-top: 30px;
        }
        button {
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 16px;
            background-color: #807bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #5c57d1;
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
            }
            label {
                font-size: 14px;
            }
            button {
                width: 100%;
                margin: 10px 0;
            }
        }

        /* Profile button style */
        .profile-box {
            position: absolute;
            top: 20px;
            right: 30px;
        }
        .profile-box a {
            text-decoration: none;
            background: #333;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .profile-box a:hover {
            background-color: #555;
        }

    </style>
    <script>
        function updateSubmitButton() {
            const checkboxes = document.querySelectorAll('input[name="cities[]"]');
            const checked = document.querySelectorAll('input[name="cities[]"]:checked').length;
            const submitBtn = document.getElementById('submitBtn');

            submitBtn.disabled = (checked < 1 || checked > 10);

            checkboxes.forEach(box => {
                if (checked >= 10 && !box.checked) {
                    box.disabled = true;
                } else {
                    box.disabled = false;
                }
            });
        }

        window.addEventListener('DOMContentLoaded', () => {
            updateSubmitButton();
            document.querySelectorAll('input[name="cities[]"]').forEach(input => {
                input.addEventListener('change', updateSubmitButton);
            });
        });
    </script>
</head>
<body>

    <div style="position: absolute; top: 20px; right: 30px;">
    <button onclick="window.location.href='profile.php'">👤 Profile</button>
    </div>


    <div class="form-container">
        <h2>Select Exactly 10 Cities for AQI</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <?php foreach ($cities as $city): ?>
                <label>
                    <input type="checkbox" name="cities[]" value="<?= htmlspecialchars($city) ?>">
                    <?= htmlspecialchars($city) ?>
                </label>
            <?php endforeach; ?>

            <div class="buttons">
                <button type="submit" name="submit" id="submitBtn" disabled>Submit</button>
                <button type="submit" name="cancel">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
