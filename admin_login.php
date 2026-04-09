<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['user'] == "admin" && $_POST['pass'] == "kerala") {
        $_SESSION['admin_active'] = true;
        header("Location: admin_dashboard.php");
    } else { $error = "Wrong login!"; }
}
?>
<body style="background:#2d6a4f; font-family:sans-serif; display:flex; justify-content:center; align-items:center; height:100vh;">
    <form method="POST" style="background:white; padding:30px; border-radius:10px;">
        <h2>Admin Portal</h2>
        <input type="text" name="user" placeholder="Username" style="display:block; margin-bottom:10px; width:100%;">
        <input type="password" name="pass" placeholder="Password" style="display:block; margin-bottom:10px; width:100%;">
        <button type="submit" style="width:100%; background:#2d6a4f; color:white; border:none; padding:10px; cursor:pointer;">Login</button>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </form>
</body>

