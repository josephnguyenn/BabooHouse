<?php
session_start();
require 'config/database.php';  // Ensure your database connection path is correct

// Initialize an error message variable
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($conn !== null && !$conn->connect_error) {
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");  // Redirect to the dashboard if login is successful
            exit();
        } else {
            $error = 'Login failed! Username or password is incorrect.';
        }
        $stmt->close();
    } else {
        $error = 'Database connection is not available.';
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">  <!-- Ensure the path to your CSS is correct -->
</head>
<body>
    <div class="login-container">
        <h2>Login to Your Account</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
