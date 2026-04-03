<?php 

$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
 
if ($event_id && $user) {
    cancelRegistration($event_id, $user->id);
}
header('Location: ./?page=my-registrations');
exit;