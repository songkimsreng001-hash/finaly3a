<?php 

$id    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = getEvent($id);

if (!$event) {
    header('Location: ./?page=events');
    exit;
}

if (deleteEvent($id)) {
    header('Location: ./?page=events');
} else {
    echo '<div class="alert alert-danger container mt-4">Failed to delete event.</div>';
}
exit;