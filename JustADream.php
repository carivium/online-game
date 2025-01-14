<?php
// Simple Authentication
session_start();

$adminUsername = "digan"; // Replace with your desired username
$adminPassword = "Gurung123"; // Replace with your desired password

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $adminUsername && $password === $adminPassword) {
        $_SESSION['isAdmin'] = true;
    } else {
        $error = "Invalid username or password.";
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MP3 Download Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        main {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
        }
        .upload-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .upload-section form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .upload-section button {
            padding: 8px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .upload-section button:hover {
            background-color: #0056b3;
        }
        .downloads ul {
            list-style-type: none;
            padding: 0;
        }
        .downloads li {
            padding: 10px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .downloads a {
            color: #007bff;
            text-decoration: none;
        }
        .downloads a:hover {
            text-decoration: underline;
        }
        .login-form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .login-form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-form button {
            width: 100%;
            padding: 8px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #0056b3;
        }
        .logout-form {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Download Your Favorite MP3s</h1>
    </header>
    <main>
        <?php if (!isset($_SESSION['isAdmin'])): ?>
            <!-- Login Form -->
            <div class="login-form">
                <h2>Admin Login</h2>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?= $error ?></p>
                <?php endif; ?>
                <form action="" method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
            </div>
        <?php else: ?>
            <!-- Admin Upload Section -->
            <div class="upload-section">
                <h2>Admin Upload Panel</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="file" name="mp3file" accept=".mp3" required>
                    <button type="submit" name="upload">Upload MP3</button>
                </form>
                <?php
                // Handle admin uploads
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                if (isset($_POST['upload'])) {
                    $file = $_FILES['mp3file'];
                    $fileName = basename($file['name']);
                    $targetFilePath = $uploadDir . $fileName;

                    if (pathinfo($targetFilePath, PATHINFO_EXTENSION) === 'mp3') {
                        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                            echo "<p style='color:green;'>File uploaded successfully: $fileName</p>";
                        } else {
                            echo "<p style='color:red;'>Error uploading file.</p>";
                        }
                    } else {
                        echo "<p style='color:red;'>Only MP3 files are allowed.</p>";
                    }
                }
                ?>
                <form action="" method="POST" class="logout-form">
                    <button type="submit" name="logout">Logout</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Available Downloads -->
        <div class="downloads">
            <h2>Available Downloads</h2>
            <ul>
                <?php
                $files = array_diff(scandir($uploadDir), array('.', '..'));
                foreach ($files as $file) {
                    echo "<li>
                            <span>$file</span>
                            <a href='$uploadDir$file' download>Download</a>
                          </li>";
                }
                ?>
            </ul>
        </div>
    </main>
</body>
</html>
