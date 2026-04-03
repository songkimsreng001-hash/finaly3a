<?php
ob_start();
$baseUrl = '/Project/';
session_start();
require_once './init/db.init.php';
require_once './init/func/auth.func.php';
require_once './init/func/user.func.php';
require_once './init/func/event.func.php';   