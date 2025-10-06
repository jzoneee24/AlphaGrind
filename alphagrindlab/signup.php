<?php
session_start();
include "server.php";
$host = "localhost"; 
$user = "root";      // default XAMPP phpMyAdmin user
$pass = "";          // default XAMPP password is empty
$db   = "gymdb";     // your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash password
$membership_type = $_POST['membership_type'];
$fitness_goals = $_POST['fitness_goals'];
$emergency_contact_name = $_POST['emergency_contact_name'];
$emergency_contact_number = $_POST['emergency_contact_number'];
$payment_method = $_POST['payment_method'];
$ewallet_choice = isset($_POST['ewallet_choice']) ? $_POST['ewallet_choice'] : NULL;

// Insert data into database
$sql = "INSERT INTO members 
        (full_name, email, phone_number, password, membership_type, fitness_goals, emergency_contact_name, emergency_contact_number, payment_method, ewallet_choice)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssss", $full_name, $email, $phone_number, $password, $membership_type, $fitness_goals, $emergency_contact_name, $emergency_contact_number, $payment_method, $ewallet_choice);

if ($stmt->execute()) {
    header("Location: index.html#loginModal"); // go back to login modal
    exit();
} else {
    echo "❌ Error: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>
