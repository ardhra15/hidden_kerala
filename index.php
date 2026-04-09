<?php 
include "db.php"; 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hidden Kerala - Discover Gems</title>
    <link rel="stylesheet" href="style.css"> 
    
    <style>
        /* Move the section padding here! */
        section {
            padding: 120px 0;
}
  .search-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 70px; /* This creates the even space between your buttons/bars */
    }
    .district-container {
        margin-top: 60px; /* Separates the districts from the search area above */
    }
        /* --- 1. GLOBAL & STABILITY STYLES --- */
        html { scroll-behavior: smooth; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            background-color: #f0f4f1; 
            color: #1b4332; 
            overflow-x: hidden; 
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* --- 2. HERO SECTION --- */
        .hero-section { 
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 20px;
            text-align: center;
            border-radius: 0 0 40px 40px;
        }

        /* --- 3. CATEGORY FEATURE CARDS --- */
        .feature-card { 
            background: white; 
            padding: 25px; 
            border-radius: 20px; 
            box-shadow: 0 10px 20px rgba(45, 106, 79, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e0eadd;
            width: 280px;
            height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            box-sizing: border-box;
        }
        .feature-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(45, 106, 79, 0.15); }

        /* --- 4. GEMS GRID (FLICKER-FREE) --- */
        .gems-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 30px; 
            padding: 30px 0; 
            min-height: 400px;
        }
        .gem-card { 
            background: white; 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.05); 
            border: 1px solid #e0eadd;
            transition: 0.3s;
        }
        .img-container {
            width: 100%;
            height: 230px;
            background-color: #dfe6df; 
            overflow: hidden;
            position: relative;
        }
        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            opacity: 0; /* Prevents flickering flash */
            transition: opacity 0.6s ease-in-out;
        }
        .img-container img.loaded { opacity: 1; }

        /* --- 5. DISTRICT FILTERS & BUTTONS --- */
        .district-link {
            padding: 10px 20px;
            background: #ffffff;
            color: #2d6a4f;
            text-decoration: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid #2d6a4f;
            transition: 0.3s;
        }
        .district-link:hover { background: #2d6a4f; color: white; }
        .share-btn {
            display: inline-block;
            background: #f1c40f; 
            color: #1b4332;
            padding: 16px 30px;
            border-radius: 35px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(241, 196, 15, 0.3);
        }

        /* --- 6. HOW IT WORKS SECTION --- */
         .how-it-works {
    background-color: #f9fbf7; /* Light off-white background */
    padding: 60px 20px;
    text-align: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
        .how-it-works .container {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 0 auto;
}
.step-card {
    flex: 1;
    min-width: 250px;
    margin: 20px;
    padding: 10px;
}
        .step-number {
            background-color: #d4af37;
            color: white;
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            font-size: 24px;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        /* Mobile Responsive */
@media (max-width: 768px) {
    .how-it-works .container {
        flex-direction: column;
    }
}
    </style>
</head>
<body>

<nav style="background: #1b4332; padding: 18px 30px; color: white; display: flex; justify-content: space-between; align-items: center; position: sticky; top:0; z-index: 1000;">
    <div style="font-weight: bold; font-size: 1.5rem; letter-spacing: 1px;">🌴 HIDDEN KERALA</div>
    <div>
        <?php if(isset($_SESSION['username'])): ?>
            <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span> | 
            <a href="profile.php" style="color: white; text-decoration: none; margin-left: 10px;">Profile</a> | 
            <a href="logout.php" style="color: #ffcccc; text-decoration: none; margin-left: 10px;">Logout</a>
        <?php else: ?>
            <a href="login.php" style="color: white; text-decoration: none; font-weight: bold;">Login to Explore</a>
        <?php endif; ?>
    </div>
</nav>

<div class="hero-section">
    <h1 style="font-size: 3.5rem; margin-bottom: 10px; text-shadow: 0 4px 10px rgba(0,0,0,0.3);">Kerala's Hidden Treasures</h1>
    <p style="font-size: 1.3rem; margin-bottom: 35px; opacity: 0.95;">Verified travel guidance for the paths less traveled.</p>
    <a href="#explore-section" style="background-color: #f1c40f; color: #1b4332; padding: 18px 40px; border-radius: 40px; text-decoration: none; font-weight: bold;">Start Your Journey</a>
</div>

<div class="container">
    <section style="padding: 60px 0; display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
        <a href="#districts" style="text-decoration: none; color: inherit;">
            <div class="feature-card">
                <div style="font-size: 45px; margin-bottom: 15px;">🗺️</div>
                <h3 style="color: #2d6a4f;">Hidden Places</h3>
                <p style="color: #555;">Unique spots discovered by locals.</p>
            </div>
        </a>
        <a href="seasonal_splendor.php" style="text-decoration: none; color: inherit;">
            <div class="feature-card">
                <div style="font-size: 45px; margin-bottom: 15px;">🌸</div>
                <h3 style="color: #2d6a4f;">Seasonal</h3>
                <p style="color: #555;">The best time to visit.</p>
            </div>
        </a>
        <a href="transport.php" style="text-decoration: none; color: inherit;">
            <div class="feature-card">
                <div style="font-size: 45px; margin-bottom: 15px;">🚕</div>
                <h3 style="color: #2d6a4f;">Transport</h3>
                <p style="color: #555;">Easy routes & commutes.</p>
            </div>
        </a>
    </section>

    <div style="max-width: 700px; margin: 0 auto 50px auto;">
        <form action="index.php#explore-section" method="GET" style="display: flex; gap: 12px; background: white; padding: 8px; border-radius: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
            <input type="text" name="search" placeholder="Where do you want to go?..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                   style="flex: 1; padding: 15px 25px; border: none; border-radius: 30px; outline: none; font-size: 16px;">
            <button type="submit" style="background: #2d6a4f; color: white; border: none; padding: 0 35px; border-radius: 30px; cursor: pointer; font-weight: bold;">Search</button>
        </form>
    </div>

    <div style="text-align: center; margin-bottom: 80px;">
        <a href="submit_gem.php" class="share-btn">+ Share a Gem & Earn 50 Points</a>
    </div>
<div id="districts" class="container">
        <h2 style="color: #1b4332; text-align: center; font-size: 2.2rem; margin-bottom: 40px;">Explore by District</h2>
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; margin-bottom: 50px;">
            <a href="index.php#explore-section" class="district-link" style="background: #2d6a4f; color: white;">All Districts</a>
            <?php
// Get the district ID from the URL if it exists
$selected_district = isset($_GET['id']) ? $_GET['id'] : '';

if ($selected_district != "") {
    // Fetch only places for the clicked district (make sure your column name is district_id)
    $query = "SELECT * FROM hidden_places WHERE district_id = '$selected_district'";
} else {
    // Fetch everything if 'All Districts' is selected
    $query = "SELECT * FROM hidden_places";
}

$result = mysqli_query($conn, $query);

            $d = mysqli_query($conn, "SELECT * FROM districts");
            while($row = mysqli_fetch_assoc($d)) {
                echo "<a href='index.php?id=".$row['district_id']."#explore-section' class='district-link'>".htmlspecialchars($row['district_name'])."</a>";
            }
            ?>
        </div>

        <div class="gems-grid">
            <?php
            $selected_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;
            $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : null;
            $status_filter = "review_status = 'Live on Site'";
            
            $gem_query = "SELECT * FROM hidden_places WHERE $status_filter";
            if($selected_id) $gem_query .= " AND district_id = '$selected_id'";
            if($search_query) $gem_query .= " AND name LIKE '%$search_query%'";
            
            $gem_result = mysqli_query($conn, $gem_query);

            if ($gem_result && mysqli_num_rows($gem_result) > 0) {
                while ($gem = mysqli_fetch_assoc($gem_result)) {
                    $db_img = trim($gem['image_name']);
                    $imagePath = (!empty($db_img) && file_exists('Images/' . $db_img)) ? 'Images/' . $db_img : 'Images/default.jpg';
                    $p_id = $gem['place_id']; // Using place_id as per your database structure
                    ?>
                    <a href="details.php?id=<?php echo $p_id; ?>" style="text-decoration: none; color: inherit;">
                        <div class="gem-card">
                            <div class="img-container">
                                <img src="<?php echo $imagePath; ?>" onload="this.classList.add('loaded')" onerror="this.src='Images/default.jpg'; this.classList.add('loaded');">
                            </div>
                            <div style="padding: 20px;">
                                <h3 style="margin: 0; font-size: 1.3rem;"><?php echo htmlspecialchars($gem['name']); ?></h3>
                                <p style="margin-top: 10px; color: #2d6a4f; font-weight: bold; font-size: 0.9rem;">View Details & Services →</p>
                            </div>
                        </div>
                    </a>
                    <?php
                }
            } else {
                echo "<div style='grid-column: 1/-1; text-align: center; padding: 80px;'><h3>No gems found.</h3></div>";
            }
            ?>
        </div>
    </div>
</div>

<section class="how-it-works">
    <div class="container">
        <div class="step-card">
            <div class="step-number">1</div>
            <h3>Register</h3>
            <p>Create your free account in seconds to unlock exclusive hidden gems.</p>
        </div>

        <div class="step-card">
            <div class="step-number">2</div>
            <h3>Explore</h3>
            <p>Browse unknown places in Kerala and plan your offbeat journey.</p>
        </div>

        <div class="step-card">
            <div class="step-number">3</div>
            <h3>Travel Safe</h3>
            <p>Access our community safety guides and emergency support whenever needed.</p>
        </div>
    </div>
</section>

<footer style="background: #1b4332; color: white; padding: 80px 20px 30px 20px; margin-top: 100px;">
    <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 50px;">
        <div>
            <h3 style="margin-bottom: 25px; font-size: 1.4rem;">Hidden Kerala</h3>
            <p style="font-size: 15px; opacity: 0.8; line-height: 1.8;">
                We are a community-driven travel portal dedicated to preserving and promoting the lesser-known wonders of Kerala.
            </p>
        </div>
        <div>
            <h3 style="margin-bottom: 25px; font-size: 1.4rem;">Links</h3>
            <ul style="list-style: none; padding: 0; font-size: 15px;">
                <li style="margin-bottom: 12px;"><a href="#explore-section" style="color: white; text-decoration: none; opacity: 0.8;">Explore Districts</a></li>
                <li><a href="login.php" style="color: white; text-decoration: none; opacity: 0.8;">Partner Login</a></li>
            </ul>
        </div>
        <div>
            <h3 style="margin-bottom: 25px; font-size: 1.4rem;">Contact</h3>
            <p style="font-size: 15px; opacity: 0.8; margin-bottom: 10px;">📍 Kozhikode, Kerala, India</p>
            <p style="font-size: 15px; opacity: 0.8;">📧 hello@hiddenkerala.com</p>
        </div>
    </div>
    <div style="text-align: center; margin-top: 60px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px; font-size: 13px; opacity: 0.5;">
        &copy; 2026 Hidden Kerala Travel Portal. Built for explorers.
    </div>
</footer>

</body>
</html>
