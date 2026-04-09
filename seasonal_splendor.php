<?php 
include "db.php"; 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seasonal Splendors - Hidden Kerala</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        /* Using the same color palette as your index.php */
        :root { --kerala-green: #2d6a4f; --accent-green: #4a6755; }
        
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background-color: #f9f9f9; }
        
        .splendor-header { padding: 60px 20px; text-align: center; }
        .splendor-header h1 { color: var(--kerala-green); font-size: 2.8rem; margin-bottom: 10px; }
        .splendor-header p { color: #555; max-width: 700px; margin: 0 auto; line-height: 1.6; }

        .festival-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); 
            gap: 30px; 
            max-width: 1200px; 
            margin: 0 auto 50px auto; 
            padding: 0 20px;
        }

        /* Card Design matching your screenshot */
        .f-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        
        .f-img { 
            height: 250px; 
            background-size: cover; 
            background-position: center; 
            position: relative; 
            display: flex; 
            align-items: flex-end; 
            padding: 20px; 
        }
        
        .f-img::after { 
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
            background: linear-gradient(transparent, rgba(0,0,0,0.7)); 
        }

        .f-img h3 { color: white; position: relative; z-index: 2; margin: 0; font-size: 1.8rem; }
        
        .month-badge { 
            position: absolute; top: 15px; right: 15px; z-index: 2;
            padding: 6px 15px; border-radius: 20px; color: white; font-weight: bold; font-size: 0.85rem; 
        }

        .f-body { padding: 25px; }
        .f-body p { color: #666; line-height: 1.5; margin-bottom: 20px; }

        .btn-learn { 
            display: block; text-align: center; padding: 12px; 
            background: var(--accent-green); color: white; 
            text-decoration: none; border-radius: 10px; font-weight: bold; 
        }

        /* Plan Your Visit Banner */
        .plan-visit { 
            background: var(--kerala-green); color: white; 
            padding: 60px 20px; text-align: center; border-radius: 30px;
            max-width: 1160px; margin: 0 auto 60px auto;
        }
        .btn-calendar { 
            display: inline-block; margin-top: 20px; padding: 15px 30px; 
            background: white; color: var(--kerala-green); 
            border-radius: 12px; text-decoration: none; font-weight: bold; 
        }
    </style>
</head>
<body>

<nav style="background: #2d6a4f; padding: 15px 30px; color: white; display: flex; justify-content: space-between; align-items: center;">
    <div style="font-weight: bold; font-size: 1.2rem;"><a href="index.php" style="color:white; text-decoration:none;">Hidden Kerala</a></div>
    <div>
        <?php if(isset($_SESSION['username'])): ?>
            <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</span> | 
            <a href="profile.php" style="color: white; text-decoration: none;">My Profile</a> | 
            <a href="logout.php" style="color: #ffcccc; text-decoration: none;">Logout</a>
        <?php else: ?>
            <a href="login.php" style="color: white; text-decoration: none;">Login to Explore</a>
        <?php endif; ?>
    </div>
</nav>

<div class="splendor-header">
    <h1>✨ Seasonal Splendors ✨</h1>
    <p>Experience Kerala in every season - from vibrant harvest festivals to sacred rituals, each celebration tells a unique story of tradition, culture, and devotion.</p>
</div>

<div class="festival-grid">
    <div class="f-card">
        <div class="f-img" style="background-image: url('Images/onam.jpg');"
          <span class="month-badge" style="background:#ffb703; color: #fff; display: inline-block;">Aug-Sep</span>
            <h3>Onam</h3>
        </div>
        <div class="f-body">
            <p>Kerala's grandest harvest festival celebrating the legendary King Mahabali's annual return. Experience vibrant pookalam (flower carpets) and Onasadya feasts.</p>
           <a href="https://en.wikipedia.org/wiki/Onam" class="btn-learn" target="_blank">Learn More</a>
        </div>
    </div>

    <div class="f-card">
        <div class="f-img" style="background-image: url('Images/vishu.jpg');">
            <span class="month-badge" style="background: #f1c40f;">April</span>
            <h3>Vishu</h3>
        </div>
        <div class="f-body">
            <p>The Malayalam New Year celebrated with Vishukkani (auspicious first sight), traditional Sadhya feasts, and Vishukkaineetam (gifts for prosperity).</p>
            <a href="https://en.wikipedia.org/wiki/Vishu" class="btn-learn">Learn More</a>
        </div>
    </div>
    
    <div class="f-card">
        <div class="f-img" style="background-image: url('Images/Theyyam.jpg');">
            <span class="month-badge" style="background: #e67e22;">Nov - Apr</span>
            <h3>Theyyam</h3>
        </div>
        <div class="f-body">
            <p>An ancient ritual art form and sacred dance that brings deities to life. Witness vibrant costumes and dramatic face paintings in North Kerala.</p>
            <a href="https://en.wikipedia.org/wiki/Theyyam" class="btn-learn">Learn More</a>
        </div>
    </div>
    <div class="f-card">
        <div class="f-img" style="background-image: url('Images/Thiraa.jpg');">
            <span class="month-badge" style="background: #f1c40f;">february</span>
            <h3>Thira</h3>
        </div>
        <div class="f-body">
            <p>Thira is a vibrant, ritualistic dance-drama from Kerala's Malabar region, performed during temple festivals (Utsavam) to invoke deities, particularly Goddess Bhagavathi.</p>
            <a href="https://en.wikipedia.org/wiki/Thirra" class="btn-learn">Learn More</a>
        </div>
    </div>
    
    <div class="f-card">
        <div class="f-img" style="background-image: url('Images/vilakku.jpg');">
            <span class="month-badge" style="background: #f1c40f;">November</span>
            <h3>Ayyappavilaku</h3>
        </div>
        <div class="f-body">
            <p>Ayyappan Vilakku is a major, traditional festival celebrated with immense fervor across the Malabar region of Kerala, particularly during the Mandalam-Makaravilakku season (mid-November to mid-January).</p>
            <a href="#" class="btn-learn">Learn More</a>
        </div>
    </div>
</div>

<div class="plan-visit">
    <h2>Plan Your Visit</h2>
    <p>Join us in celebrating Kerala's rich cultural heritage. Each festival offers a unique glimpse into the soul of God's Own Country.</p>
    
    <a href="https://calendar.google.com/calendar/u/0/embed?src=en.indian%23holiday%40group.v.calendar.google.com&ctz=Asia/Kolkata" 
       target="_blank" 
       class="btn-calendar">
       Plan Your Visit
    </a>
</div>
<footer style="background: #2d6a4f; color: white; padding: 40px 20px; text-align: center;">
    <p style="font-size: 12px; opacity: 0.8;">&copy; 2026 Hidden Kerala Travel Portal. All rights reserved.</p>
</footer>

</body>
</html>