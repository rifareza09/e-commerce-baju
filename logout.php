<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/helpers.php';

session_destroy();
header('Location: ' . baseUrl('index.php'));
exit();
?>
