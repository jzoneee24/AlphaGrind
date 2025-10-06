<?php
session_start();

// Only allow trainers
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header("Location: login.html");
    exit();
}

include "server.php"; // includes $conn

// Get trainer ID from session
$trainer_id = intval($_SESSION['trainer_id'] ?? 0);

// Get form data
$member_id = intval($_POST['member_id'] ?? 0);
$workout   = trim($_POST['workout_plan'] ?? '');
$diet      = trim($_POST['diet_plan'] ?? '');

if ($trainer_id <= 0 || $member_id <= 0) {
    die("❌ Invalid trainer or member.");
}

/**
 * Insert/Update in routines table
 */
$sql = "
    INSERT INTO routines (member_id, trainer_id, workout_plan, diet_plan, updated_at)
    VALUES (?, ?, ?, ?, NOW())
    ON DUPLICATE KEY UPDATE 
        workout_plan = VALUES(workout_plan),
        diet_plan = VALUES(diet_plan),
        updated_at = NOW()
";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("iiss", $member_id, $trainer_id, $workout, $diet);
    $stmt->execute();
    $stmt->close();
}

/**
 * Insert/Update in workout_plans table
 */
if ($workout !== '') {
    $sql = "
        INSERT INTO workout_plans (member_id, trainer_id, plan_details, created_at)
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
            plan_details = VALUES(plan_details),
            created_at = NOW()
    ";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iis", $member_id, $trainer_id, $workout);
        $stmt->execute();
        $stmt->close();
    }
}

/**
 * Insert/Update in diet_plans table
 */
if ($diet !== '') {
    $sql = "
        INSERT INTO diet_plans (member_id, trainer_id, plan_details, created_at)
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
            plan_details = VALUES(plan_details),
            created_at = NOW()
    ";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iis", $member_id, $trainer_id, $diet);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: trainer_dashboard.php?success=1");
exit();
?>
