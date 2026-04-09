<?php
$conn = mysqli_connect("localhost", "root", "", "hidden_places_db");

// Check if parameters are provided, otherwise set to empty string
$district = isset($_GET['district']) ? mysqli_real_escape_string($conn, $_GET['district']) : '';
$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';

$data = [];

// Only query if both district and type are provided
if ($district != '' && $type != '') {
    $query = "SELECT station_name, latitude, longitude, contact_info 
              FROM transport_details 
              WHERE district = '$district' AND type = '$type'";

    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

// Always return JSON, even if it is an empty list []
header('Content-Type: application/json');
echo json_encode($data);
?>