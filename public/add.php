<?php
require_once __DIR__ . '/../src/bootstrap.php';
use CT275\Labs\Contact;

$contact = new Contact($PDO);

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
  <?php $subtitle = 'Thêm liên hệ mới'; include_once __DIR__ . '/../src/partials/heading.php'; ?>
  <form method="post">
    <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control"></div>
    <div class="mb-3"><label>Phone</label><input type="text" name="phone" class="form-control"></div>
    <div class="mb-3"><label>Notes</label><textarea name="notes" class="form-control"></textarea></div>
    <button type="submit" class="btn btn-success">Save</button>
  </form>
</div>
<?php include_once __DIR__ . '/../src/partials/footer.php' ?>
</body>
</html>
