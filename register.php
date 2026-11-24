<?php
require_once __DIR__ . '/config/app.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $name=trim($_POST['name'] ?? ''); $email=trim($_POST['email'] ?? ''); $role=$_POST['role'] ?? 'employee';
    $password=$_POST['password'] ?? ''; $confirm=$_POST['confirm_password'] ?? '';
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $error='Enter a valid email address.';
    elseif (strlen($password)<8) $error='Password must be at least 8 characters.';
    elseif ($password!==$confirm) $error='Passwords do not match.';
    elseif (!in_array($role,['employer','employee'],true)) $error='Invalid account role.';
    else {
        $check=$pdo->prepare('SELECT id FROM users WHERE email=?'); $check->execute([$email]);
        if ($check->fetch()) $error='That email is already registered.';
        else {
            $pdo->beginTransaction();
            $stmt=$pdo->prepare('INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)');
            $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),$role]);
            $uid=(int)$pdo->lastInsertId();
            if($role==='employer') { $s=$pdo->prepare('INSERT INTO employers(user_id,company_name,phone) VALUES(?,?,?)'); $s->execute([$uid,trim($_POST['company_name']?:$name),trim($_POST['phone']??'')]); }
            else { $s=$pdo->prepare('INSERT INTO employees(user_id,name,phone) VALUES(?,?,?)'); $s->execute([$uid,$name,trim($_POST['phone']??'')]); }
            $pdo->commit(); flash('Account created. Please sign in.'); redirect('login.php');
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Register | EMS</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><main class="container py-5"><div class="row justify-content-center"><div class="col-md-7"><div class="card shadow"><div class="card-body p-4"><h3>Register</h3><?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif ?><form method="post"><input type="hidden" name="csrf" value="<?= csrf_token() ?>"><div class="row"><div class="col-md-6 mb-3"><label class="form-label">Full Name</label><input class="form-control" name="name" required></div><div class="col-md-6 mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div><div class="col-md-6 mb-3"><label class="form-label">Role</label><select class="form-select" name="role" required><option value="employee">Employee / Job Seeker</option><option value="employer">Employer</option></select></div><div class="col-md-6 mb-3"><label class="form-label">Phone</label><input class="form-control" name="phone"></div><div class="col-md-12 mb-3"><label class="form-label">Company Name (employers only)</label><input class="form-control" name="company_name"></div><div class="col-md-6 mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" minlength="8" required></div><div class="col-md-6 mb-3"><label class="form-label">Confirm Password</label><input class="form-control" type="password" name="confirm_password" minlength="8" required></div></div><button class="btn btn-primary">Create Account</button> <a class="btn btn-link" href="login.php">Sign in</a></form></div></div></div></div></main></body></html>
