<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit();
}

$success_message = '';
$error_message = '';

if ($_POST) {
    $username = trim(string: $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email = trim(string: $_POST['email'] ?? '');
    
    if (empty($username) || empty($password) || empty($email)) {
        $error_message = 'All fields are required!';
    } elseif (strlen(string: $username) < 3) {
        $error_message = 'Username must be at least 3 characters long!';
    } elseif (strlen(string: $password) < 6) {
        $error_message = 'Password must be at least 6 characters long!';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match!';
    } elseif (!filter_var(value: $email, filter: FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address!';
    } else {
        $users_file = 'users.txt';
        $user_exists = false;
        
        if (file_exists($users_file)) {
            $users = file($users_file, FILE_IGNORE_NEW_LINES);
            foreach ($users as $user_line) {
                $user_data = explode('|', $user_line);
                if ($user_data[0] === $username) {
                    $user_exists = true;
                    break;
                }
            }
        }
        
        if ($user_exists) {
            $error_message = 'Username already exists! Please choose a different one.';
        } else {
            $hashed_password = password_hash(password: $password, algo: PASSWORD_DEFAULT);

            $user_record = $username . '|' . $hashed_password . '|' . $email . "\n";
            
            if (file_put_contents(filename: $users_file, data: $user_record, flags: FILE_APPEND | LOCK_EX)) {
                $success_message = 'Account created successfully! You can now login.';
            } else {
                $error_message = 'Error creating account. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CatMusic - Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="signin.css">
</head>
<body>
    <div class="signup-container">
        <form class="signup-form" method="POST" action="">
            <h2>Join CatMusic</h2>
            
            <?php if ($error_message): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Choose a username" 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                <div class="password-requirements">At least 3 characters</div>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Create a password" required>
                <div class="password-requirements">At least 6 characters</div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            
            <button type="submit" class="signup-btn">Create Account</button>
            
            <p>Already have an account? <a href="login.php" class="login-link">Login here</a></p>
        </form>
    </div>
</body>
</html>