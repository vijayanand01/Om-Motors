<?php
$servername = "localhost";  // Replace with your database server name
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "ommotors";       // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch vehicles data
$sql = "SELECT * FROM vehicle";
$result = $conn->query($sql);

$vehicles = array();

if ($result->num_rows > 0) {
    // Fetch data for each row
    while ($row = $result->fetch_assoc()) {
        // Assuming 'photo' is the column name in the database storing the image path
        $vehicle = array(
            'id' => $row['id'],
            'regno' => $row['regno'],
            'engno' => $row['engno'],
            'chassisno' => $row['chassisno'],
            'title' => $row['title'],
            'make' => $row['make'],
            'model' => $row['model'],
            'year' => $row['year'],
            'color' => $row['color'],
            'owner' => $row['owner'],
            'fuel' => $row['fuel'],
            'photo' => $row['photo']  // Ensure 'photo' matches the column name in your database
        );
        array_push($vehicles, $vehicle);
    }
} else {
    echo "0 results";
}

$conn->close();

// Return vehicles data as JSON
header('Content-Type: application/json');
echo json_encode($vehicles);
?>
