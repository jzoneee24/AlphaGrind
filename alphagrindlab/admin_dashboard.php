<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Alpha Grind Lab</title>
  <link rel="icon" href="alphalogo.png" type="image/png" />
  <link rel="stylesheet" href="dashboard.css" />
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Dashboard</h2>
      <a href="#manage-trainers">Manage Trainers</a>
      <a href="#manage-users">Manage Users</a>
      <a href="#manage-payments">Manage Payments</a>
      <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
      <h1 class="welcome">Welcome, Admin (<?php echo $_SESSION['full_name']; ?>)</h1>

      <div class="card" id="manage-trainers">
        <h2>Manage Trainers</h2>
        <p>View, add, edit, or delete trainer accounts.</p>
        <a href="manage_trainers.php" class="btn">Go to Trainers</a>
      </div>

      <div class="card" id="manage-users">
        <h2>Manage Users</h2>
        <p>View, add, edit, or delete user accounts.</p>
        <a href="manage_users.php" class="btn">Go to Users</a>
      </div>

      <div class="card" id="manage-payments">
        <h2>Manage Payments</h2>
        <p>Track and manage membership payments.</p>
        <a href="manage_payments.php" class="btn">Go to Payments</a>
      </div>
    </div>
  </div>
</body>
</html>
