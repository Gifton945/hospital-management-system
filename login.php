<?php
require_once __DIR__ . '/config/app.php';
$error='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email=trim($_POST['email'] ?? ''); $password=$_POST['password'] ?? '';
    $stmt=$pdo->prepare('SELECT * FROM users WHERE email=? AND status="active"'); $stmt->execute([$email]); $user=$stmt->fetch();
    if ($user && password_verify($password,$user['password'])) {
        session_regenerate_id(true); $_SESSION['user']=$user; redirect('dashboard.php');
    } $error='Invalid email or password.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login | EMS</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><main class="container"><div class="row justify-content-center"><div class="col-md-5 col-lg-4"><div class="card shadow mt-5"><div class="card-body p-4"><h3 class="text-center mb-4">Employment Management System</h3><?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif ?><form method="post"><input type="hidden" name="csrf" value="<?= csrf_token() ?>"><div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div><div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div><button class="btn btn-primary w-100">Sign In</button></form><div class="text-center mt-3"><a href="register.php">Create an account</a></div></div></div></div></div></main></body></html>
