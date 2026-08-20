<?php
require_once __DIR__ . '/../src/bootstrap.php';
use CT275\Labs\Contact;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $contact = new Contact($PDO);
    $contact = $contact->find((int)$_POST['id']);
    if ($contact) {
        $contact->delete();
    }
}
redirect('/');
