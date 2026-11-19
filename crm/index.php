<?php

ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    isset($_SESSION['admin_logged_in']) &&
    $_SESSION['admin_logged_in'] === true
) {
    header("Location: /ajmera/crm/dashboard.php");
    exit;
}

require_once '../dbc.php'; 

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Password verify kar rahe hain
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "Invalid Username!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow-lg p-4" style="width: 380px; border-radius: 10px;">
        <div class="text-center mb-4">
            <h4> Admin Panel</h4>
            <p class="text-muted">Please login to continue</p>
        </div>
        
        <?php if(isset($error)) { echo "<div class='alert alert-danger py-2 text-center'>$error</div>"; } ?>
        
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 fw-bold">LOGIN</button>
        </form>
    </div>
</body>
</html>