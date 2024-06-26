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

// Handle file upload
$target_dir = "uploads/";  // Directory where uploaded files will be saved
$target_file = $target_dir . basename($_FILES["photo"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["photo"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["photo"]["name"])). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO vehicle (regno, engno, chassisno, title, make, model, year, color, owner, fuel, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssissss", $regno, $engno, $chassisno, $title, $make, $model, $year, $color, $owner, $fuel, $photo);

// Set parameters and execute
$regno = $_POST['regno'];
$engno = $_POST['engno'];
$chassisno = $_POST['chassisno'];
$title = $_POST['title'];
$make = $_POST['make'];
$model = $_POST['model'];
$year = $_POST['year'];
$color = $_POST['color'];
$owner = $_POST['owner'];
$fuel = $_POST['fuel'];
$photo = $target_file;  // Save the path to the uploaded file

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
