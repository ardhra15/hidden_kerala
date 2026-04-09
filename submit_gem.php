<?php
session_start();
include 'db.php';

// Only allow logged-in users to submit
if (!isset($_SESSION['user_id'])) {
    die("Please login first to submit a hidden gem and earn points!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['place_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $dist = $_POST['district_id'];
    $uid = $_SESSION['user_id']; // This tracks who to give points to later

    // Insert with 'Checking...' status so it doesn't show on index.php yet
    $sql = "INSERT INTO hidden_places (name, description, district_id, user_id, review_status) 
            VALUES ('$name', '$desc', '$dist', '$uid', 'Checking...')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Submission successful! It is now being reviewed by our team.'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch districts for the dropdown menu
$districts = mysqli_query($conn, "SELECT * FROM districts");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit a Hidden Gem</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 50px; }
        .form-container { background: white; padding: 30px; border-radius: 10px; max-width: 500px; margin: 0 auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #2d6a4f; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #1b4332; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 style="color: #2d6a4f; text-align: center;">Suggest a Hidden Gem</h2>
        <p style="text-align: center; color: #666; font-size: 14px;">Earn 50 points once approved!</p>
        
        <form method="POST">
            <input type="text" name="place_name" placeholder="Name of the place" required>
            
            <select name="district_id" required>
                <option value="">Select District</option>
                <?php while($d = mysqli_fetch_assoc($districts)) { ?>
                    <option value="<?php echo $d['district_id']; ?>"><?php echo $d['district_name']; ?></option>
                <?php } ?>
            </select>
            
            <textarea name="description" placeholder="Why is this place special?" rows="5" required></textarea>
            
            <button type="submit">Submit for Review</button>
        </form>
        <p style="text-align: center;"><a href="index.php" style="color: #666; text-decoration: none;">← Back to Home</a></p>
    </div>
</body>
</html>

