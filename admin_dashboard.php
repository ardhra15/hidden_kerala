<?php
session_start();
include 'db.php'; 

// 1. Admin Security Check
if (!isset($_SESSION['admin_active'])) { 
    header("Location: admin_login.php"); 
    exit(); 
}

// 2. DELETE LOGIC - Fixes Issue 2 (Ensures buttons do what they say)
if (isset($_GET['delete_id'])) {
    $pid = mysqli_real_escape_string($conn, $_GET['delete_id']);
    
    $del_sql = "DELETE FROM hidden_places WHERE place_id = '$pid'";
    
    if(mysqli_query($conn, $del_sql)) {
        header("Location: admin_dashboard.php?msg=deleted");
        exit();
    } else {
        die("Error deleting: " . mysqli_error($conn));
    }
}

// 3. APPROVE LOGIC - Fixes Issue 2 (Now strictly an UPDATE, not a DELETE)
if (isset($_GET['approve_id'])) {
    $pid = mysqli_real_escape_string($conn, $_GET['approve_id']);
    
    // Get user_id to award points
    $res = mysqli_query($conn, "SELECT user_id FROM hidden_places WHERE place_id = '$pid'");
    $row = mysqli_fetch_assoc($res);
    
    if($row) {
        $uid = $row['user_id'];
        // Fix: This updates the status to 'Live on Site' so index.php can show it
        mysqli_query($conn, "UPDATE hidden_places SET review_status = 'Live on Site' WHERE place_id = '$pid'");
        // AWARD POINTS
        mysqli_query($conn, "UPDATE users SET points = points + 50 WHERE user_id = '$uid'");
    }
    header("Location: admin_dashboard.php?msg=approved");
    exit();
}

// 4. Fetch ONLY PENDING items - Fixes Issue 3 (Hides official/already approved places)
// By adding the WHERE clause, your dashboard stays clean.
$result = mysqli_query($conn, "SELECT * FROM hidden_places WHERE review_status = 'Checking...' ORDER BY place_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Review Portal</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 40px; }
        .card { background: white; padding: 25px; margin-bottom: 20px; border-radius: 12px; border-left: 6px solid #ffb703; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .btn-post { background: #2d6a4f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; }
        .btn-del { color: #cc0000; text-decoration: none; margin-left: 20px; font-weight: bold; border: 1px solid #cc0000; padding: 9px 19px; border-radius: 5px; display: inline-block; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <h1>Admin Review Portal</h1>
    <p><a href="index.php">Main Website</a> | <a href="logout.php">Logout</a></p>
    <hr>

    <?php 
    // Success Messages
    if (isset($_GET['msg'])) {
        $m = ($_GET['msg'] == 'approved') ? "Approval" : "Deletion";
        echo "<div class='msg'>" . $m . " successful!</div>";
    }

    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $pid = $row['place_id']; 
            $name = htmlspecialchars($row['name']);
            $desc = htmlspecialchars($row['description']);
            
            echo "
            <div class='card'>
                <h3>$name</h3>
                <p>$desc</p>
                <p><strong>Status:</strong> " . htmlspecialchars($row['review_status']) . "</p>
                <div style='margin-top: 20px;'>
                    <a href='admin_dashboard.php?approve_id=$pid' class='btn-post'>✅ Post to Website</a>
                    <a href='admin_dashboard.php?delete_id=$pid' class='btn-del' onclick=\"return confirm('Confirm Delete?');\">❌ Delete</a>
                </div>
            </div>";
        }
    } else {
        echo "<div style='background: white; padding: 50px; text-align: center; border-radius: 10px;'>
                <h3>No new submissions to review.</h3>
                <p style='color: #666;'>All user suggestions have been processed!</p>
              </div>";
    }
    ?>
</body>
</html>