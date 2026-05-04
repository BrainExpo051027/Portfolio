<?php
require_once __DIR__ . '/../classes/User.php';

$user = new User();
$user->logout();

header('Location: login.php');
exit;
