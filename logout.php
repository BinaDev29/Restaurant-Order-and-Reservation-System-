<?php
require_once 'app/config.php';
require_once 'app/controllers/AuthController.php';

$auth = new AuthController();
$auth->logout();
