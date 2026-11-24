<?php
require_once __DIR__.'/config/app.php'; $user=require_role(['admin']); $EMS_WITH_SIDEBAR=true;
require_once __DIR__.'/includes/layout.php'; page_header('Reports',$user);
$employeeCount=$pdo->query('SELECT COUNT(*) FROM employees')->fetchColumn();$jobCount=$pdo->query('SELECT COUNT(*) FROM jobs')->fetchColumn();$appCount=$pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn();$presentCount=$pdo->query("SELECT COUNT(*) FROM attendance WHERE status='present'")->fetchColumn();
?>
<div class="row g-3"><div class="col-md-3"><div class="card"><div class="card-body"><h6>Employees</h6><h3><?=$employeeCount?></h3></div></div></div><div class="col-md-3"><div class="card"><div class="card-body"><h6>Jobs</h6><h3><?=$jobCount?></h3></div></div></div><div class="col-md-3"><div class="card"><div class="card-body"><h6>Applications</h6><h3><?=$appCount?></h3></div></div></div><div class="col-md-3"><div class="card"><div class="card-body"><h6>Present Records</h6><h3><?=$presentCount?></h3></div></div></div></div>
<h4 class="mt-4">Attendance by Status</h4><table class="table table-bordered"><thead><tr><th>Status</th><th>Records</th></tr></thead><tbody><?php foreach($pdo->query('SELECT status,COUNT(*) total FROM attendance GROUP BY status')->fetchAll() as$r):?><tr><td><?=ucfirst(htmlspecialchars($r['status']))?></td><td><?=$r['total']?></td></tr><?php endforeach?></tbody></table>
<?php page_footer(); ?>
