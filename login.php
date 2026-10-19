<?php
session_start();

// New hashed key hi hackers hehe
$hash_key = '$2a$13$4SNS04vAelLzm9S6jG3z3OD/i2AuzMpYkKkTi7.PwQ8ulP0BIT6Em';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Trim input to remove spaces
    $key = trim($_POST['key']);
    
    if(password_verify($key, $hash_key)){
        $_SESSION['loggedin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid key. Make sure there are no extra spaces.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Login - Bangor Jewish Society</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="uploads/logo.png">
</head>
<body>
<div class="container mt-5" style="max-width:400px;">
  <div class="text-center mb-4">
    <img src="uploads/logo.png" alt="BJS Logo" style="max-width:120px;">
  </div>
  <h2 class="text-center mb-3">Admin Login</h2>
  <form method="POST">
    <div class="mb-3">
      <input type="password" class="form-control" name="key" placeholder="Enter secret key" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
  <?php if(isset($error)) echo '<div class="text-danger mt-2 text-center">'.$error.'</div>'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
