<?php
$id = $_GET['id'];
$targetUser = readUser($id);
if ($targetUser == null || $targetUser->level == 'admin') {
    header('Location: ./?page=user/list');
}else {
    if (deleteUser($id)) {
        echo '<div class="alert alert-success" role="alert">
        Delete successful! <a href="./?page=user/list">go to list</a>
        </div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">
        Delete failed! Please try again.
        </div>';
    }
}