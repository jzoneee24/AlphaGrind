<?php
session_start();
if (!isset($_SESSION['member_id'])) {
    header("Location: login.html");
    exit();
}

include "server.php"; // sets up $conn (mysqli)

$member_id = intval($_SESSION['member_id']);

// ---------------- Fetch workout plans ----------------
$workout_plans = [];
$sql = "SELECT id, trainer_id, workout, created_at 
        FROM workout_plans 
        WHERE member_id = ? 
        ORDER BY created_at DESC";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $workout_plans[] = $row;
    }
    $stmt->close();
}

// ---------------- Fetch diet plans ----------------
$diet_plans = [];
$sql = "SELECT id, trainer_id, diet, created_at 
        FROM diet_plans 
        WHERE member_id = ? 
        ORDER BY created_at DESC";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $diet_plans[] = $row;
    }
    $stmt->close();
}

// ---------------- Fetch progress history ----------------
$progress_records = [];
$sql = "SELECT weight, reps, sets, recorded_at 
        FROM progress 
        WHERE member_id = ? 
        ORDER BY recorded_at DESC";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $progress_records[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard - Alpha Grind Lab</title>
  <link rel="stylesheet" href="dashboard.css">
  <link rel="icon" href="alphalogo.png" type="image/png" />
  <style>
    body {
      background-color: #111;
      color: #eee;
      font-family: 'Arial', sans-serif;
      margin: 0;
    }
    .dashboard-container {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 220px;
      background: #222;
      padding: 20px;
    }
    .sidebar h2 {
      color: #f44336;
    }
    .sidebar a {
      display: block;
      padding: 10px;
      margin: 6px 0;
      background: #333;
      color: #eee;
      text-decoration: none;
      border-radius: 6px;
      transition: background-color 0.3s ease;
    }
    .sidebar a:hover {
      background: #f44336;
      color: #fff;
    }
    .content {
      flex: 1;
      padding: 20px;
      background: #1a1a1a;
    }
    .card {
      background: #222;
      margin-bottom: 20px;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .card h2 {
      margin-top: 0;
      color: #f44336;
    }
    .plan-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    /* Updated plan item design */
    .plan-item {
      background: #2c2c2c;  /* Darker background to match the theme */
      color: #eee;           /* Light text for contrast */
      margin-bottom: 15px;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .plan-item:hover {
      transform: translateY(-5px); /* Hover effect for interactivity */
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    .plan-meta {
      font-size: 0.85rem;
      color: #aaa;  /* Lighter color for metadata */
      margin-bottom: 8px;
    }
    .plan-body {
      font-size: 1rem;
      font-weight: bold;
      line-height: 1.5;
      color: #ddd;  /* Light color for readability */
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    table th, table td {
      border: 1px solid #444;
      padding: 8px;
      text-align: center;
    }
    table th {
      background: #333;
      color: #fff;
    }
    /* CSS to make workout and diet plans scrollable */
    .scrollable-plans-container {
      max-height: 400px; /* Adjust height as needed */
      overflow-y: auto;
      padding-right: 20px;
    }
    /* Scrollbar styling */
    .scrollable-plans-container::-webkit-scrollbar {
      width: 8px;
    }
    .scrollable-plans-container::-webkit-scrollbar-thumb {
      background: #f44336;
      border-radius: 4px;
    }
    .scrollable-plans-container::-webkit-scrollbar-track {
      background: #333;
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Dashboard</h2>
      <a href="#profile">Profile</a>
      <a href="#workouts">Workout Plans</a>
      <a href="#diet">Diet Plans</a>
      <a href="#progress">Progress Tracking</a>
      <a href="#tutorials">Workout Tutorials</a>
      <a href="#payments">Payments</a>
      <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
      <!-- Profile -->
      <div id="profile" class="card">
        <h2>My Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <p><strong>Membership:</strong> <?php echo htmlspecialchars($_SESSION['membership_type']); ?></p>
      </div>

      <!-- Workouts -->
      <div id="workouts" class="card">
        <h2>Workout Plans</h2>
        <div class="scrollable-plans-container">
          <?php if (!empty($workout_plans)): ?>
            <ul class="plan-list">
              <?php foreach ($workout_plans as $wp): ?>
                <li class="plan-item">
                  <div class="plan-meta">
                    <?php echo htmlspecialchars(date('M j, Y H:i', strtotime($wp['created_at']))); ?>
                    • Trainer ID: <?php echo htmlspecialchars($wp['trainer_id']); ?>
                  </div>
                  <div class="plan-body">
                    <?php echo nl2br(htmlspecialchars($wp['workout'])); ?>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p>No workout plans assigned yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Diet -->
      <div id="diet" class="card">
        <h2>Diet Plans</h2>
        <div class="scrollable-plans-container">
          <?php if (!empty($diet_plans)): ?>
            <ul class="plan-list">
              <?php foreach ($diet_plans as $dp): ?>
                <li class="plan-item">
                  <div class="plan-meta">
                    <?php echo htmlspecialchars(date('M j, Y H:i', strtotime($dp['created_at']))); ?>
                    • Trainer ID: <?php echo htmlspecialchars($dp['trainer_id']); ?>
                  </div>
                  <div class="plan-body">
                    <?php echo nl2br(htmlspecialchars($dp['diet'])); ?>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p>No diet plans assigned yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Progress -->
      <div id="progress" class="card">
        <h2>Progress Tracking</h2>
        <form method="post" action="save_progress.php">
          <input type="number" name="weight" placeholder="Weight (kg)" step="0.1" required>
          <input type="number" name="reps" placeholder="Reps" required>
          <input type="number" name="sets" placeholder="Sets" required>
          <button type="submit">Save Progress</button>
        </form>

        <?php if (!empty($progress_records)): ?>
          <h3>Progress History</h3>
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>Weight (kg)</th>
                <th>Reps</th>
                <th>Sets</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($progress_records as $record): ?>
                <tr>
                  <td><?php echo htmlspecialchars($record['recorded_at']); ?></td>
                  <td><?php echo htmlspecialchars($record['weight']); ?></td>
                  <td><?php echo htmlspecialchars($record['reps']); ?></td>
                  <td><?php echo htmlspecialchars($record['sets']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No progress records yet.</p>
        <?php endif; ?>
      </div>

      <!-- Tutorials -->
      <div id="tutorials" class="card">
        <h2>Workout Tutorials</h2>
        <ul>
          <li><a href="#">Push-up Tutorial (Video)</a></li>
          <li><a href="#">Squat Form (Image)</a></li>
          <li><a href="#">Deadlift Guide (PDF)</a></li>
        </ul>
      </div>

      <!-- Payments -->
      <div id="payments" class="card">
        <h2>Payment Info</h2>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($_SESSION['payment_method']); ?></p>
      </div>
    </div>
  </div>
</body>
</html>
