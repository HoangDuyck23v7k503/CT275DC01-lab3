<?php
require_once __DIR__ . '/../src/bootstrap.php';
use CT275\Labs\Contact;

$contact = new Contact($PDO);
$id = $_GET['id'] ?? null;
if ($id) $contact = $contact->find((int)$id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact->fill($_POST);
    if ($contact->save()) {
        redirect('/');
    }
}

include_once __DIR__ . '/../src/partials/header.php';
?>
<body>
<?php include_once __DIR__ . '/../src/partials/navbar.php' ?>
<div class="container">
  <?php $subtitle = 'Chỉnh sửa liên hệ'; include_once __DIR__ . '/../src/partials/heading.php'; ?>
  <form method="post">
    <input type="hidden" name="id" value="<?=$contact->id?>">
    <div class="mb-3"><label>Name</label><input type="text" name="name" value="<?=$contact->name?>" class="form-control"></div>
    <div class="mb-3"><label>Phone</label><input type="text" name="phone" value="<?=$contact->phone?>" class="form-control"></div>
    <div class="mb-3"><label>Notes</label><textarea name="notes" class="form-control"><?=$contact->notes?></textarea></div>
    <button type="submit" class="btn btn-success">Update</button>
  </form>
</div>
<?php include_once __DIR__ . '/../src/partials/footer.php' ?>
</body>
</html>
