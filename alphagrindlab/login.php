<?php
session_start();

$host = "localhost";
$user = "root"; 
$pass = "";
$db   = "gymdb";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email_add'] ?? '';
$password = $_POST['pass'] ?? '';

if (!empty($email) && !empty($password)) {

    // ✅ Hardcoded Admin
    if ($email === "admin@alphagrindlab.com" && $password === "admin123") {
        $_SESSION['role']      = "admin";
        $_SESSION['full_name'] = "Jason";
        $_SESSION['email']     = $email;

        echo "admin"; // JS will redirect to admin_dashboard.php
        exit;
    }

   // ✅ Hardcoded Trainer
if ($email === "trainer@alphagrindlab.com" && $password === "trainer123") {
    $_SESSION['role']       = "trainer";
    $_SESSION['trainer_id'] = 1; // or the real trainer_id from your DB
    $_SESSION['full_name']  = "Tanggol Montenegro";
    $_SESSION['email']      = $email;

    echo "trainer"; // JS will redirect to trainer_dashboard.php
    exit;
}


    // ✅ Normal Member Login (from DB)
    $stmt = $conn->prepare("SELECT member_id, full_name, email, password, membership_type, payment_method 
                            FROM members WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // ✅ Compare with hashed password
        if (password_verify($password, $row['password'])) {
            // ✅ Save this specific member’s info
            $_SESSION['role']            = "member";
            $_SESSION['member_id']       = $row['member_id'];
            $_SESSION['full_name']       = $row['full_name'];
            $_SESSION['email']           = $row['email'];
            $_SESSION['membership_type'] = $row['membership_type'];
            $_SESSION['payment_method']  = $row['payment_method'];

            echo "member";
        } else {
            echo "error"; // wrong password
        }
    } else {
        echo "error"; // no user found
    }

    $stmt->close();

} else {
    echo "error"; // empty fields
}

$conn->close();
