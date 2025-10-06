<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header("Location: login.html");
    exit();
}

include "server.php"; // database connection

$trainer_id = intval($_SESSION['trainer_id']);

// Fetch trainer's full name from DB if not already stored in session
if (!isset($_SESSION['full_name'])) {
    $stmt = $conn->prepare("SELECT full_name FROM trainers WHERE trainer_id = ?");
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $stmt->bind_result($full_name);
    if ($stmt->fetch()) {
        $_SESSION['full_name'] = $full_name;
    } else {
        $_SESSION['full_name'] = "Trainer"; // fallback
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trainer Dashboard - Alpha Gym</title>
  <link rel="icon" href="alphalogo.png" type="image/png" />
  <link rel="stylesheet" href="dashboard.css" />
  <style>
    .card { padding:16px; margin-bottom:16px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    .btn { display:inline-block; padding:6px 12px; background:#007bff; color:#fff; border:none; border-radius:4px; text-decoration:none; cursor:pointer; }
    textarea, input { width:100%; margin-bottom:10px; padding:8px; border:1px solid #ddd; border-radius:4px; }
    h3 { margin-top:12px; }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Dashboard</h2>
      <a href="#assigned-members">Assigned Members</a>
      <a href="#workout-plans">Workout Plans</a>
      <a href="#diet-plans">Diet Plans</a>
      <a href="#progress-reports">Progress Reports</a>
      <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
      <h1 class="welcome">
  Welcome, Trainer (<?php echo htmlspecialchars($_SESSION['full_name'] ?? ''); ?>)
</h1>

      <!-- Assigned Members -->
      <div class="card" id="assigned-members">
        <h2>Assigned Members</h2>
        <p>View the list of members assigned to you.</p>
        <a href="view_members.php" class="btn">View Members</a>
      </div>

    <!-- Workout Plans -->
<div class="card" id="workout-plans">
  <h2>Workout Plans</h2>
  <p>Manage and assign workout routines.</p>

  <form action="save_workout.php" method="POST">
    <input type="hidden" name="trainer_id" value="<?php echo $trainer_id; ?>">

    <label for="member_id">Select Member:</label>
    <input type="number" name="member_id" required placeholder="Enter Member ID">
    
    <label for="workout">Workout Plan:</label>
    <textarea name="workout" placeholder="Enter workout plan here..."></textarea>
    
    <button type="submit" class="btn">Save Workout Plan</button>
  </form>
</div>

<!-- Diet Plans -->
<div class="card" id="diet-plans">
  <h2>Diet Plans</h2>
  <p>Customize diet recommendations for members.</p>

  <form action="save_diet.php" method="POST">
    <input type="hidden" name="trainer_id" value="<?php echo $trainer_id; ?>">

    <label for="member_id">Select Member:</label>
    <input type="number" name="member_id" required placeholder="Enter Member ID">

    <label for="diet">Diet Plan:</label>
    <textarea name="diet" placeholder="Enter diet plan here..."></textarea>
    
    <button type="submit" class="btn">Save Diet Plan</button>
  </form>
</div>


      <!-- Progress Reports -->
      <div class="card" id="progress-reports">
        <h2>Progress Reports</h2>
        <p>Track member progress and performance.</p>

        <form action="save_progress.php" method="POST">
          <label for="member_id">Select Member:</label>
          <input type="number" name="member_id" required placeholder="Enter Member ID">

          <label for="weight">Weight (kg):</label>
          <input type="number" step="0.1" name="weight" placeholder="e.g. 70.5">

          <label for="reps">Reps:</label>
          <input type="number" name="reps" placeholder="e.g. 12">

          <label for="sets">Sets:</label>
          <input type="number" name="sets" placeholder="e.g. 4">

          <button type="submit" class="btn">Save Progress</button>
        </form>
      </div>

    </div>
  </div>
</body>
</html>
