<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header(header: 'Location: index.php');
    exit();
}

$error_message = '';

if ($_POST) {
    $username = trim(string: $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error_message = 'Please enter both username and password!';
    } else {
        if ($username === 'admin' && $password === 'password123') {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header(header: 'Location: index.php');
            exit();
        }
        
        $users_file = 'users.txt';
        $login_successful = false;
        
        if (file_exists(filename: $users_file)) {
            $users = file(filename: $users_file, flags: FILE_IGNORE_NEW_LINES);
            foreach ($users as $user_line) {
                $user_data = explode(separator: '|', string: $user_line);
                if (count(value: $user_data) >= 2 && $user_data[0] === $username) {
                    if (password_verify(password: $password, hash: $user_data[1])) {
                        $login_successful = true;
                        break;
                    }
                }
            }
        }
        
        if ($login_successful) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            
            header(header: 'Location: index.php');
            exit();
        } else {
            $error_message = 'Invalid username or password!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CatMusic - Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" method="POST" action="">
            <h2>Login to Cat-A-Log</h2>
            
            <?php if ($error_message): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars(string: $error_message); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="login-btn">Login</button>
            
            <p style="margin-top: 15px;">Don't have an account? <a href="signup.php" style="color: #A64D79; text-decoration: none; font-weight: bold;">Sign up here</a></p>
            
        </form>
    </div>
</body>
</html>