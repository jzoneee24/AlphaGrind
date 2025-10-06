<?php
session_start();
include "server.php";

// Get member and trainer IDs
$member_id = intval($_POST['member_id']);
$trainer_id = intval($_POST['trainer_id']);
$diet = trim($_POST['diet']);

$sql = "INSERT INTO diet_plans (member_id, trainer_id, diet, created_at) 
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
        diet = VALUES(diet), 
        created_at = NOW()";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $member_id, $trainer_id, $diet);

if ($stmt->execute()) {
    echo "Diet saved/updated successfully.";
} else {
    echo "Error: " . $stmt->error;
}
