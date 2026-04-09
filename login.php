<?php
ob_start();
session_start();
include "db.php"; // Connects to your existing hidden_places_db
$error = "";//this clears any old error messages

if(isset($_POST['login'])){

    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn,$query);

    if(!$result){
        die(mysqli_error($conn));
    }

    if(mysqli_num_rows($result) > 0){

        $user_data = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user_data['user_id'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['role'] = $user_data['role'];

        if($_SESSION['role'] == 'admin'){
            header("Location: admin_dashboard.php");
            exit;
        } else {
            header("Location: index.php");
            exit;
        }

    } else {

        $error = "Invalid username or password!";

    }
}

?>

    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Kerala Hidden Gems</title>
    <style>
        body { font-family: 'Segoe UI'; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        h2 { color: #2d6a4f; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-login { background-color: #ffb703; color: #333; border: none; padding: 12px; width: 100%; border-radius: 25px; font-weight: bold; cursor: pointer; font-size: 16px; }
        .btn-login:hover { background-color: #fb8500; }
        .error { color: #d9534f; margin-bottom: 15px; font-size: 14px; }
        .success-msg {
    color: #2d6a4f;
    background-color: #d8f3dc;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 14px;
    border: 1px solid #b7e4c7;
    text-align: center; /* Optional: centers the text */
}
    </style>
</head>
<body>
    <div class="login-container">
        
    <?php 
    if(isset($_GET['msg'])): ?>
        <div class="success-msg">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>
    
    
        <h2>Login</h2>
        <?php if(!empty($error)) echo "<div class='error'>$error
        </div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn-login">Login</button>
        </form>
        <p style="margin-top: 20px; font-size: 14px;">Don't have an account? <a href="register.php" style="color: #2d6a4f; font-weight: bold;">Register</a></p>
    </div>
</body>
</html>
