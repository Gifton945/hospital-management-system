<?php
require_once __DIR__ . '/../config/app.php';

$user = require_login();
$page_header = 'Dashboard';
$EMS_WITH_SIDEBAR = true;
require_once __DIR__ . '/includes/layout.php';
page_header($page_header, $user);

echo flash();

$counts = [];
if ($user['role'] === 'admin') {
    $counts[] = ['Employees',$pdo->query('SELECT COUNT(*) FROM employees')->fetchColumn(),'primary'];
    $counts[] = ['Open Jobs',$pdo->query("SELECT COUNT(*) FROM jobs WHERE status='open'")->fetchColumn(),'success'];
    $counts[] = ['Applications',$pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn(),'warning'];
} elseif ($user['role'] === 'employer') {
    $stmt=$pdo->prepare('SELECT COUNT(*) FROM jobs j JOIN employers e ON e.id=j.employer_id WHERE e.user_id=?'); $stmt->execute([$user['id']]);
    $counts[] = ['My Jobs',$stmt->fetchColumn(),'primary'];
    $stmt=$pdo->prepare('SELECT COUNT(*) FROM applications a JOIN jobs j ON j.id=a.job_id JOIN employers e ON e.id=j.employer_id WHERE e.user_id=?'); $stmt->execute([$user['id']]);
    $counts[] = ['Applications',$stmt->fetchColumn(),'warning'];
} else {
    $counts[] = ['Open Jobs',$pdo->query("SELECT COUNT(*) FROM jobs WHERE status='open' AND deadline>=CURDATE()")->fetchColumn(),'success'];
    $stmt=$pdo->prepare('SELECT COUNT(*) FROM applications WHERE applicant_id=?'); $stmt->execute([$user['id']]);
    $counts[] = ['My Applications',$stmt->fetchColumn(),'primary'];
}
?>
<div class="row g-3">
<?php foreach($counts as [$label,$count,$color]): ?>
<div class="col-md-4"><div class="card card-stat shadow-sm"><div class="card-body"><h6 class="text-muted"><?= $label ?></h6><h2 class="text-<?= $color ?>"><?= (int)$count ?></h2></div></div></div>
<?php endforeach ?>
</div>
<?php page_footer(); ?>
