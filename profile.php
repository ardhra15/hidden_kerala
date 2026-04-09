
<?php
// 1. Start session and include your database connection
session_start();
include('db.php'); // Replace with your actual database connection file

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// 2. Fetch User Data (Example Query)
// This assumes you have a 'users' table with 'points' and 'join_date'
$query = "SELECT points FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);

$points = $user_data['points'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Explorer Profile | Kerala's Hidden Treasures</title>
      <style>
        :root {
            --primary-green: #1a3c34;
            --accent-gold: #d4af37;
            --bg-light: #f4f7f6;
        }

        body { font-family: 'Segoe UI', sans-serif; background-color: var(--bg-light); margin: 0; color: #333; }
        
        /* Green Banner */
        .header-banner { 
        
            background-size: cover;
            background-position: center;
            height: 200px; 
        }

        .profile-card { 
            max-width: 600px; 
            margin: -80px auto 40px; 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            text-align: center;
            position: relative;
        }
  

        

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }

        .stat-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 12px;
            border-bottom: 3px solid var(--primary-green);
        }

        .stat-item h2 { margin: 5px 0; color: var(--primary-green); }
        .stat-item p { margin: 0; font-size: 0.8rem; color: #777; text-transform: uppercase; letter-spacing: 1px; }

        .btn-action {
            display: inline-block;
            padding: 12px 25px;
            background: var(--primary-green);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(26, 60, 52, 0.3); }
        
        .back-link { display: block; margin-top: 20px; color: #888; text-decoration: none; font-size: 0.9rem; }
        .back-link:hover { color: var(--primary-green); }
    </style>
</head>
<body>

<div class="header-banner"></div>

<div class="profile-card">
    <div class="profile-img">
        <i class="fas fa-user-ninja"></i>
    </div>

    <h1 style="margin-bottom: 5px;">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <p style="color: #666; margin-top: 0;">Kerala Treasure Hunter</p>

    <div class="stats-grid">
        <div class="stat-item">
            <p>Contribution Points</p>
            <h2><?php echo $points; ?> <span style="font-size: 1.2rem;">🏆</span></h2>
        </div>
        <div class="stat-item">
            <p>Rank</p>
            <h2><?php echo ($points > 10) ? 'Master Scout' : 'Novice'; ?></h2>
        </div>
    </div>

    <p style="font-style: italic; color: #888; margin-bottom: 30px;">
        "The world is a book, and those who do not travel read only a page."
    </p>

    <a href="index.php" class="btn-action">Explore More Places</a>
    <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Home</a>
</div>

</body>
</html>


