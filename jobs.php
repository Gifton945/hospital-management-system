<?php
require_once __DIR__ . '/config/app.php';
$user=require_role(['admin','employer','employee']); $EMS_WITH_SIDEBAR=true;
require_once __DIR__.'/includes/layout.php'; page_header('Jobs',$user); echo flash();

if ($_SERVER['REQUEST_METHOD']==='POST' && $user['role']==='employer') {
    verify_csrf(); $title=trim($_POST['title']); $desc=trim($_POST['description']); $req=trim($_POST['requirements']); $deadline=$_POST['deadline'];
    $emp=$pdo->prepare('SELECT id FROM employers WHERE user_id=?'); $emp->execute([$user['id']]); $employer=$emp->fetch();
    if ($employer) { $s=$pdo->prepare('INSERT INTO jobs(employer_id,title,description,requirements,deadline) VALUES(?,?,?,?,?)'); $s->execute([$employer['id'],$title,$desc,$req,$deadline]); flash('Job posted successfully.'); }
}

if (isset($_GET['apply']) && $user['role']==='employee') {
    verify_csrf();
}

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['apply_job']) && $user['role']==='employee') {
    verify_csrf(); $job=(int)$_POST['apply_job'];
    try { $s=$pdo->prepare('INSERT INTO applications(job_id,applicant_id) VALUES(?,?)'); $s->execute([$job,$user['id']]); flash('Application submitted.'); }
    catch(PDOException $e){ flash('You have already applied for this job.','warning'); }
}

if ($user['role']==='employer') {
    $stmt=$pdo->prepare('SELECT j.* FROM jobs j JOIN employers e ON e.id=j.employer_id WHERE e.user_id=? ORDER BY j.created_at DESC'); $stmt->execute([$user['id']]);
} else { $stmt=$pdo->query("SELECT j.*,e.company_name FROM jobs j JOIN employers e ON e.id=j.employer_id WHERE j.status='open' AND j.deadline>=CURDATE() ORDER BY j.created_at DESC"); }
$jobs=$stmt->fetchAll();

if($user['role']==='employer'): ?>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#jobModal">Post Job</button>
<div class="modal" id="jobModal"><div class="modal-dialog"><div class="modal-content"><form method="post"><div class="modal-header"><h5>Post a Job</h5></div><div class="modal-body"><input type="hidden" name="csrf" value="<?= csrf_token() ?>"><input class="form-control mb-2" name="title" placeholder="Job title" required><textarea class="form-control mb-2" name="description" placeholder="Description" required></textarea><textarea class="form-control mb-2" name="requirements" placeholder="Requirements"></textarea><input class="form-control" type="date" name="deadline" required></div><div class="modal-footer"><button class="btn btn-primary">Save</button></div></form></div></div></div>
<?php endif ?>
<div class="row g-3">
<?php foreach($jobs as $job): ?><div class="col-md-6"><div class="card h-100"><div class="card-body"><h5><?= htmlspecialchars($job['title']) ?></h5><?php if(isset($job['company_name'])):?><h6 class="text-muted"><?= htmlspecialchars($job['company_name']) ?></h6><?php endif ?><p><?= nl2br(htmlspecialchars($job['description'])) ?></p><small>Deadline: <?= htmlspecialchars($job['deadline']) ?></small><?php if($user['role']==='employee'): ?><form method="post" class="mt-3"><input type="hidden" name="csrf" value="<?= csrf_token() ?>"><button class="btn btn-success btn-sm" name="apply_job" value="<?= $job['id'] ?>">Apply</button></form><?php endif ?></div></div></div><?php endforeach ?>
</div>
<?php page_footer(); ?>
