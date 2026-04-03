<?php

require_once './init/init.php';
$user = loggedInUser();
$isAdmin = isAdmin();
include './includes/header.inc.php';
include './includes/navbar.inc.php';

$admin_pages = [
    'user/list',
    'user/create',
    'user/update',
    'user/delete',
    'event/create',
    'event/edit',
    'event/delete',
    'event/registrations'
];

$logged_in_pages = ['dashboard', 'profile', 'event/register', 'event/cancel', 'my-registrations'];
$non_logged_in_pages = ['login', 'register'];
$public_pages = ['events', 'event/detail'];

$available_pages = [
    'logout',
    ...$non_logged_in_pages,
    ...$logged_in_pages,
    ...$admin_pages,
    ...$public_pages
];

$page = '';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if (in_array($page, $logged_in_pages) && empty($user)) {
    header('Location: ./?page=login');
    exit;
}
if (in_array($page, $non_logged_in_pages) && !empty($user)) {
    header('Location: ./?page=dashboard');
    exit;
}
if (in_array($page, $available_pages)) {
    if (in_array($page, $admin_pages) && !$isAdmin) {
        header('Location: ./?page=dashboard');
        exit;
    }
    include './pages/' . $page . '.php';
    
} else {
    if ($user) {
        include './pages/dashboard.php';
    } else {
        include './pages/events.php';
    }
}

include './includes/footer.inc.php';