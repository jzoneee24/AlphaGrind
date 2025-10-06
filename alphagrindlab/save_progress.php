<?php
session_start();

if (!isset($_SESSION['member_id'])) {
    header("Location: login.html");
    exit();
}

include "server.php"; // should set up $conn

$member_id = intval($_SESSION['member_id']);
$weight    = floatval($_POST['weight'] ?? 0);
$reps      = intval($_POST['reps'] ?? 0);
$sets      = intval($_POST['sets'] ?? 0);

if ($member_id <= 0 || $weight <= 0 || $reps <= 0 || $sets <= 0) {
    die("❌ Invalid input.");
}

$sql = "INSERT INTO progress (member_id, weight, reps, sets, recorded_at)
        VALUES (?, ?, ?, ?, NOW())";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("idii", $member_id, $weight, $reps, $sets);
    if ($stmt->execute()) {
        header("Location: user_dashboard.php?progress=1");
        exit();
    } else {
        die("❌ Failed to save progress: " . $stmt->error);
    }
    $stmt->close();
} else {
    die("❌ SQL error: " . $conn->error);
}
