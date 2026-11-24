<?php
function page_header(string $title, array $user = null): void {
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title) ?> | EMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>.sidebar{min-height:100vh}.card-stat{border-left:4px solid #0d6efd}.nav-link{color:#cfe2ff}.nav-link:hover,.nav-link.active{color:#fff;background:#0d6efd}.navbar-brand{letter-spacing:.5px}</style>
</head>
<body>
<nav class="navbar navbar-dark bg-primary"><div class="container-fluid"><a class="navbar-brand fw-bold" href="dashboard.php">EMS</a><?php if($user): ?><span class="text-white me-3 d-none d-md-inline"><?= htmlspecialchars($user['name']) ?> (<?= ucfirst($user['role']) ?>)</span><a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a><?php endif ?></div></nav>
<div class="container-fluid"><div class="row">
<?php if($user): ?><aside class="col-md-2 p-0 bg-dark sidebar"><nav class="nav flex-column p-3">
<a class="nav-link" href="dashboard.php">Dashboard</a>
<?php if(in_array($user['role'],['admin','employer'])): ?><a class="nav-link" href="jobs.php">Jobs</a><?php endif ?>
<?php if($user['role'] === 'employee'): ?><a class="nav-link" href="jobs.php">Browse Jobs</a><?php endif ?>
<?php if(in_array($user['role'],['admin','employer'])): ?><a class="nav-link" href="applications.php">Applications</a><?php endif ?>
<?php if($user['role'] === 'admin'): ?><a class="nav-link" href="employees.php">Employees</a><a class="nav-link" href="attendance.php">Attendance</a><a class="nav-link" href="reports.php">Reports</a><?php endif ?>
</nav></aside><main class="col-md-10 p-4"><h2 class="mb-4"><?= htmlspecialchars($title) ?></h2><?php endif ?>
<?php }
function page_footer(): void { ?><?php if(!empty($GLOBALS['EMS_WITH_SIDEBAR'])): ?></main><?php endif ?>
</div></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php }
