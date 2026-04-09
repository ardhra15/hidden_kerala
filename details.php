<?php
include "db.php";
session_start();

// 1. DATABASE SYNC: Catch the 'id' passed from index.php
// We use place_id to ensure the database link is stable
$place_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;

if (!$place_id) {
    header("Location: index.php");
    exit;


}

// 2. FETCH ALL DATA: Including description, petrol, and mechanics
// Querying the 'hidden_places' table specifically for the selected place
$query = "SELECT * FROM hidden_places WHERE place_id = '$place_id'";
$res = mysqli_query($conn, $query);
$place = mysqli_fetch_assoc($res);

if (!$place) {
    echo "<h3 style='text-align:center; margin-top:50px;'>Place not found. Please check your database records.</h3>";
    exit;
}

// 3. SET VARIABLES FOR DISPLAY
$name = $place['name'];
$image = $place['image_name'];
$description = $place['description'] ?? "No description provided for this gem.";
$petrol = $place['petrol_pumps'] ?? "No nearby petrol pumps listed.";
$mechanic = $place['mechanics'] ?? "No local mechanics listed.";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($name); ?> - Hidden Kerala</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        /* Global Styles matching your theme */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f4f1; margin: 0; color: #1b4332; }
        .container { max-width: 1100px; margin: 40px auto; background: white; border-radius: 30px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
        
        /* Flicker-free Hero Image logic */
        .hero-img { width: 100%; height: 500px; background: #dfe6df; overflow: hidden; position: relative; }
        .hero-img img { width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 0.8s ease-in-out; }
        .hero-img img.loaded { opacity: 1; }

        .content { padding: 50px; }
        .back-btn { display: inline-block; margin-bottom: 25px; color: #2d6a4f; text-decoration: none; font-weight: bold; font-size: 1.1rem; }
        .back-btn:hover { color: #1b4332; }

        /* Service Cards Grid */
        .service-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 40px; }
        .service-card { background: #f9fbf7; padding: 30px; border-radius: 20px; border: 1px solid #e0eadd; transition: 0.3s; }
        .service-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .service-card h3 { margin-top: 0; color: #2d6a4f; display: flex; align-items: center; gap: 12px; font-size: 1.4rem; }
        
        .description-text { line-height: 1.8; color: #444; font-size: 1.15rem; margin-bottom: 30px; }
          /* Modal & Map Styles */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; display: none; align-items: center; justify-content: center; }
        .modal-content { background: white; padding: 20px; border-radius: 12px; width: 90%; max-width: 800px; position: relative; }
        .close-btn { position: absolute; right: 20px; top: 10px; font-size: 28px; cursor: pointer; font-weight: bold; }
        
        #map-container { 
            height: 450px !important; 
            width: 100%; 
            border-radius: 8px; 
            margin-top: 15px;
            background-color: #eee; 
        }
        .leaflet-container { z-index: 1001 !important; }
 .btn-utility {
            background-color: #f0fdf4; color: #2d6a4f; border: 1px solid #2d6a4f;
            padding: 10px 15px; border-radius: 8px; font-weight: bold;
            cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.3s;
        }
        .btn-utility:hover { background-color: #2d6a4f; color: white; }

      </style>
</head>
<body>
    <div class="container">
    <a href="index.php" class="back-btn">← Back to Explore</a>

    <?php
    // 1. Updated to listen for 'id' instead of 'name' to match index.php
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $query = "SELECT * FROM hidden_places WHERE place_id = '$id' LIMIT 1";
        $result = mysqli_query($conn, $query);
    }

        if ($row = mysqli_fetch_assoc($result)) {
            $lat = $row['latitude'];
            $lng = $row['longitude'];
            $pid = $row['place_id'];
            } // Store this for the review section below
            ?>
            
            
            <h1><?php echo htmlspecialchars($row['name']); ?></h1>

    


<div class="container">
    <div class="hero-img">
        <?php 
            // Standardizing path to your CAPITAL 'Images' folder
            $img_path = (!empty($image) && file_exists('Images/' . $image)) ? 'Images/' . $image : 'Images/default.jpg'; 
        ?>
        <img src="<?php echo $img_path; ?>" 
             onload="this.classList.add('loaded')" 
             onerror="this.src='Images/default.jpg'; this.classList.add('loaded');">
    </div>

    <div class="content">
        <a href="index.php" class="back-btn">← Back to Explore Districts</a>
        
        <h1 style="font-size: 3rem; margin: 0 0 20px 0; color: #1b4332;"><?php echo htmlspecialchars($name); ?></h1>
        
        <div class="description-text">
            <?php echo nl2br(htmlspecialchars($description)); ?>
        </div>

      <div class="detail-section">
                <div class="label">Nearby Services</div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px;">
                    <button type="button" onclick="showNearbyService('hospitality', <?php echo $lat; ?>, <?php echo $lng; ?>)" class="btn-utility">🏨🍴 Stay & Eat</button>
                    <button type="button" onclick="showNearbyService('fuel', <?php echo $lat; ?>, <?php echo $lng; ?>)" class="btn-utility">⛽ Petrol Pump</button>
                    <button type="button" onclick="showNearbyService('car_repair', <?php echo $lat; ?>, <?php echo $lng; ?>)" class="btn-utility">🛠️ Mechanic</button>
                    <button type="button" onclick="showNearbyService('hospital', <?php echo $lat; ?>, <?php echo $lng; ?>)" class="btn-utility">🏥 Hospital</button>
                </div>
            </div>

            <div class="detail-section">
                <p class="label">Things to Do</p>
                <p><?php echo nl2br(htmlspecialchars($row['things_to_do'] ?? 'No activities listed.')); ?></p>
            </div>
            <?php
    
    ?>
    <div class="detail-section">
    <p class="label">Traveler Reviews</p>
    <?php
    // We use $row['place_id'] because you already fetched the place details in Line 51
    $pid = $row['place_id']; 
    $rev_query = "SELECT reviews.*, users.username FROM reviews 
                  JOIN users ON reviews.user_id = users.user_id 
                  WHERE reviews.place_id = '$pid' ORDER BY review_date DESC";
    $rev_result = mysqli_query($conn, $rev_query);

    if(mysqli_num_rows($rev_result) > 0) {
        while($rev = mysqli_fetch_assoc($rev_result)) {
            echo "<div style='background:#f9f9f9; padding:15px; border-radius:8px; margin-bottom:10px;'>";
            echo "<strong>" . htmlspecialchars($rev['username']) . "</strong> ";
            echo "<span style='color:#2d6a4f;'>(" . $rev['rating'] . "/5 Stars)</span>";
            echo "<p style='margin:5px 0;'>" . htmlspecialchars($rev['comment']) . "</p>";
            echo "<small style='color:#888;'>" . $rev['review_date'] . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews yet. Be the first to share your experience!</p>";
    }
    ?>

    <div style="margin-top:30px; border-top:1px solid #eee; padding-top:20px;">
        <p class="label">Leave a Review</p>
        <form action="submit_review.php" method="POST">
            <input type="hidden" name="place_id" value="<?php echo $pid; ?>">
            <select name="rating" class="btn-utility" style="margin-bottom:10px; width:auto;">
                <option value="5">5 Stars - Excellent</option>
                <option value="4">4 Stars - Good</option>
                <option value="3">3 Stars - Average</option>
                <option value="2">2 Stars - Poor</option>
                <option value="1">1 Star - Terrible</option>
            </select><br>
            <textarea name="comment" placeholder="Write your comment here..." style="width:100%; height:100px; padding:10px; border-radius:8px; border:1px solid #ddd;" required></textarea><br>
            <button type="submit" name="post_review" class="btn-utility" style="margin-top:10px; background:#2d6a4f; color:white;">Submit Review</button>
        </form>
    </div>
<div id="mapModal" class="modal-overlay">
    <div class="modal-content">
        <span class="close-btn" onclick="closeMap()">&times;</span>
        <h3 id="mapTitle" style="margin-top: 0; color: #2d6a4f;">Search Results</h3>
        <div id="map-container"></div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="map.js"></script>

        

<footer style="text-align: center; padding: 50px 0; color: #888; font-size: 14px;">
    &copy; 2026 Hidden Kerala Travel Portal - Built for BCA Final Year Project
</footer>

</body>
</html>
