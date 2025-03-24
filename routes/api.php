<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    die(json_encode(["status" => "error", "message" => "No data received"]));
}

// Database Connection
$servername = "mysql";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "laravel"; // Change to your actual DB name




$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection failed"]));
}

// Insert Data
$stmt = $conn->prepare("INSERT INTO attendees (id, full_name, address, dob, sex, category) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $data['id'], $data['full_name'], $data['address'], $data['dob'], $data['sex'], $data['category']);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
