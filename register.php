
<?php
include "db.php"; 
$error = ""; 

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $contact = mysqli_real_escape_string($conn, $_POST['email_phone']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $conf_pass = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // 1. Check if passwords match
    if ($pass !== $conf_pass) {
        $error = "Passwords do not match!";
    } else {
        // 2. Check if username is already taken
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$name'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username already exists!";
        } else {
            // 3. Insert new user with 0 points
            $sql = "INSERT INTO users (username, email_phone, password, role, points) 
                    VALUES ('$name', '$contact', '$pass', 'user', 0)";
            
            if (mysqli_query($conn, $sql)) {
                header("Location: login.php?msg=Registration successful!");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Kerala Hidden Gems</title>
    <style>
        /* Using the same CSS style from your login page */
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .register-container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); width: 100%; max-width: 350px; text-align: center; }
        h2 { color: #2d6a4f; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-reg { background-color: #ffb703; color: #333; border: none; padding: 12px; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; }
        .error { color: #d9534f; font-size: 14px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Join the Journey</h2>
        
        <?php if($error != "") echo "<div class='error'>$error</div>"; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Full Name" required>
            <input type="text" name="email_phone" placeholder="Email or Phone Number" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            
            <button type="submit" name="register" class="btn-reg">Sign Up</button>
        </form>
        
        <p style="margin-top: 20px; font-size: 14px;">
            Already a member? <a href="login.php" style="color: #2d6a4f; text-decoration: none; font-weight: bold;">Login</a>
        </p>
    </div>
</body>
</html>
