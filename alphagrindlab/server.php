<?php
// server.php
// Central PHP server file for Alpha Grind Lab system

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================
// DATABASE CONNECTION
// =====================
$host = "localhost";   // usually "localhost"
$user = "root";        // XAMPP/WAMP default username
$pass = "";            // XAMPP/WAMP default password is empty
$db   = "gymdb";       // make sure you created this DB in phpMyAdmin

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// =====================
// USER SIGNUP HANDLER (members table)
// =====================
if (isset($_POST['signup'])) {
    $full_name = $_POST['full_name'];
    $email     = $_POST['email'];
    $phone     = $_POST['phone_number'];
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];
    $membership = $_POST['membership_type'];
    $goals     = $_POST['fitness_goals'];
    $emergency_name = $_POST['emergency_contact_name'];
    $emergency_phone = $_POST['emergency_contact_number'];
    $payment   = $_POST['payment_method'];
    $ewallet   = $_POST['ewallet_choice'] ?? null;

    if ($password !== $confirm) {
        die("❌ Passwords do not match.");
    }

    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO members 
        (full_name, email, phone_number, password, membership_type, fitness_goals, emergency_contact_name, emergency_contact_number, payment_method, ewallet_choice) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", 
        $full_name, $email, $phone, $hashed_pass, $membership, $goals, $emergency_name, $emergency_phone, $payment, $ewallet
    );

    if ($stmt->execute()) {
        $_SESSION['member_id'] = $stmt->insert_id;
        $_SESSION['email']   = $email;
        $_SESSION['role']    = "member";
        $_SESSION['full_name'] = $full_name;
        $_SESSION['membership_type'] = $membership;
        header("Location: user_dashboard.php");
        exit();
    } else {
        die("❌ Signup failed: " . $stmt->error);
    }
}

// =====================
// LOGIN HANDLER (members + trainers)
// =====================
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // First check trainers
    $stmt = $conn->prepare("SELECT * FROM trainers WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $trainerResult = $stmt->get_result();

    if ($trainerResult->num_rows > 0) {
        $trainer = $trainerResult->fetch_assoc();
        if (password_verify($password, $trainer['password'])) {
            $_SESSION['trainer_id'] = $trainer['trainer_id'];
            $_SESSION['email']   = $trainer['email'];
            $_SESSION['role']    = "trainer";
            $_SESSION['full_name'] = $trainer['full_name'];
            header("Location: trainer_dashboard.php");
            exit();
        } else {
            die("❌ Invalid trainer password.");
        }
    }

    // Else check members
    $stmt = $conn->prepare("SELECT * FROM members WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $memberResult = $stmt->get_result();

    if ($memberResult->num_rows > 0) {
        $member = $memberResult->fetch_assoc();
        if (password_verify($password, $member['password'])) {
            $_SESSION['member_id'] = $member['member_id'];
            $_SESSION['email']   = $member['email'];
            $_SESSION['role']    = "member";
            $_SESSION['full_name'] = $member['full_name'];
            $_SESSION['membership_type'] = $member['membership_type'];
            header("Location: user_dashboard.php");
            exit();
        } else {
            die("❌ Invalid member password.");
        }
    }

    die("❌ No account found with that email.");
}

// =====================
// LOGOUT HANDLER
// =====================
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.html");
    exit();
}
?>
