<?php
require_once __DIR__ . '/../src/bootstrap.php';

use CT275\Labs\Contact;
use CT275\Labs\Paginator;

$contact = new Contact($PDO);

$limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? (int)$_GET['limit'] : 5;
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;

$paginator = new Paginator(
    recordsPerPage: $limit,
    totalRecords: $contact->count(),
    currentPage: $page
);

$contacts = $contact->paginate($paginator->recordOffset, $paginator->recordsPerPage);
$pages = $paginator->getPages(length: 3);

include_once __DIR__ . '/../src/partials/header.php';
?>
<body>
  <?php include_once __DIR__ . '/../src/partials/navbar.php' ?>
  <div class="container">
    <?php $subtitle = 'Danh sách liên hệ với phân trang.'; include_once __DIR__ . '/../src/partials/heading.php'; ?>
    <a href="/add.php" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> New Contact</a>
    <table class="table table-striped table-bordered">
      <thead><tr><th>Name</th><th>Phone</th><th>Date Created</th><th>Notes</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($contacts as $contact): ?>
          <tr>
            <td><?=html_escape($contact->name)?></td>
            <td><?=html_escape($contact->phone)?></td>
            <td><?=html_escape(date("d-m-Y", strtotime($contact->created_at)))?></td>
            <td><?=html_escape($contact->notes)?></td>
            <td>
              <a href="/edit.php?id=<?=$contact->id?>" class="btn btn-warning btn-sm">Edit</a>
              <form action="/delete.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?=$contact->id?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <nav>
      <ul class="pagination">
        <li class="page-item <?=$paginator->getPrevPage() ? '' : 'disabled'?>">
          <a class="page-link" href="/?page=<?=$paginator->getPrevPage()?>&limit=<?=$limit?>">&laquo;</a>
        </li>
        <?php foreach ($pages as $p): ?>
          <li class="page-item <?=$paginator->currentPage == $p ? 'active' : ''?>">
            <a class="page-link" href="/?page=<?=$p?>&limit=<?=$limit?>"><?=$p?></a>
          </li>
        <?php endforeach ?>
        <li class="page-item <?=$paginator->getNextPage() ? '' : 'disabled'?>">
          <a class="page-link" href="/?page=<?=$paginator->getNextPage()?>&limit=<?=$limit?>">&raquo;</a>
        </li>
      </ul>
    </nav>
  </div>
  <?php include_once __DIR__ . '/../src/partials/footer.php' ?>
</body>
</html>
