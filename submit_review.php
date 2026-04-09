<?php
session_start();
include('db.php'); // Ensure this matches your connection filename

if(isset($_POST['post_review'])) {
    // 1. Check if user is logged in
    if(!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please login to post a review'); window.location.href='login.php';</script>";
        exit();
    }

    // 2. Capture data from the form
    $place_id = $_POST['place_id'];
    $rating = $_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $user_id = $_SESSION['user_id']; // From login session
    $date = date('Y-m-d');

    // 3. Insert into the reviews table
    $insert_sql = "INSERT INTO reviews (user_id, place_id, rating, comment, review_date) 
                   VALUES ('$user_id', '$place_id', '$rating', '$comment', '$date')";

    if(mysqli_query($conn, $insert_sql)) {
        // Redirect back to the place page after success
        header("Location: details.php?id=" . $place_id);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>