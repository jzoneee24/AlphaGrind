<?php
session_start();
include "server.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id  = intval($_POST['member_id']);
    $trainer_id = intval($_POST['trainer_id']);
    $workout    = $_POST['workout'];

   $sql = "INSERT INTO workout_plans (member_id, trainer_id, workout, created_at) 
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
        workout = VALUES(workout), 
        created_at = NOW()";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $member_id, $trainer_id, $workout);

if ($stmt->execute()) {
    echo "Workout saved/updated successfully.";
} else {
    echo "Error: " . $stmt->error;
}

}
?>
