<?php
require_once '../config/functions.php';
session_unset(); session_destroy();
header('Location: login.php'); exit;
?>