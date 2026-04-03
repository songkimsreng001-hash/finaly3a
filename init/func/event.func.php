<?php
function validateImage($image)
{
    $img_name = $image['name'];
    $img_size = $image['size'];
    $tmp_name = $image['tmp_name'];
    $error    = $image['error'];

    $dir = './assets/images/';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $allow_exs          = ['jpg', 'png', 'jpeg'];
    $image_ex           = pathinfo($img_name, PATHINFO_EXTENSION);
    $image_lowercase_ex = strtolower($image_ex);

    if ($error !== 0) {
        throw new Exception('Image upload error (code ' . $error . '). Check php.ini upload settings.');
    }
    if (!in_array($image_lowercase_ex, $allow_exs)) {
        throw new Exception('File extension not allowed! Only JPG, JPEG, and PNG are accepted.');
    }
    if ($img_size > 500000) {
        throw new Exception('File size too large! Maximum allowed size is 500KB.');
    }
    $new_image_name = uniqid("PI-") . '.' . $image_lowercase_ex;
    $image_path     = $dir . $new_image_name;
    if (!move_uploaded_file($tmp_name, $image_path)) {
        throw new Exception('Failed to save uploaded image. Check write permissions on: ' . realpath($dir));
    }

    return $image_path;
}
function getAllEvents()
{
    global $db;
    $query = $db->prepare(
        'SELECT e.*, 
                COUNT(r.id) AS reg_count
         FROM tbl_events e
         LEFT JOIN tbl_registrations r ON r.event_id = e.id AND r.status <> "rejected"
         GROUP BY e.id
         ORDER BY e.event_date ASC'
    );
    $query->execute();
    return $query->get_result();
}
function getEvent($id)
{
    global $db;
    $query = $db->prepare(
        'SELECT e.*,
                COUNT(r.id) AS reg_count
         FROM tbl_events e
         LEFT JOIN tbl_registrations r ON r.event_id = e.id AND r.status <> "rejected"
         WHERE e.id = ?
         GROUP BY e.id'
    );
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_object();
    }
    return null;
}
function createEvent($title, $description, $location, $event_date, $capacity, $image)
{
    global $db;

    $image_path = null;

    if (!empty($image['name'])) {
        $image_path = validateImage($image);
    }

    $sql = "INSERT INTO tbl_events 
            (title, description, location, event_date, capacity, image) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $db->error);
    }

    $stmt->bind_param("ssssis", $title, $description, $location, $event_date, $capacity, $image_path);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    return true;
}
function updateEvent($id, $title, $description, $location, $event_date, $capacity, $image)
{
    global $db;

    if (!empty($image['name'])) {
        $image_path = validateImage($image);

        $sql = "UPDATE tbl_events 
                SET title=?, description=?, location=?, event_date=?, capacity=?, image=? 
                WHERE id=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssisi", $title, $description, $location, $event_date, $capacity, $image_path, $id);

    } else {
        $sql = "UPDATE tbl_events 
                SET title=?, description=?, location=?, event_date=?, capacity=? 
                WHERE id=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssii", $title, $description, $location, $event_date, $capacity, $id);
    }

    if (!$stmt->execute()) {
        die("Update failed: " . $stmt->error);
    }

    return true;
}
function deleteEvent($id)
{
    global $db;
    $query = $db->prepare('DELETE FROM tbl_events WHERE id = ?');
    $query->bind_param('i', $id);
    $query->execute();
    return $db->affected_rows > 0;
}
function registerForEvent($event_id, $user_id, $note = '')
{
    global $db;
    $query = $db->prepare(
        'INSERT INTO tbl_registrations (event_id, user_id, note) VALUES (?, ?, ?)'
    );
    $query->bind_param('iis', $event_id, $user_id, $note);
    $query->execute();
    return $db->affected_rows > 0;
}
function isUserRegistered($event_id, $user_id)
{
    global $db;
    $query = $db->prepare(
        'SELECT id FROM tbl_registrations WHERE event_id = ? AND user_id = ?'
    );
    $query->bind_param('ii', $event_id, $user_id);
    $query->execute();
    return $query->get_result()->num_rows > 0;
}
function cancelRegistration($event_id, $user_id)
{
    global $db;
    $query = $db->prepare(
        'DELETE FROM tbl_registrations WHERE event_id = ? AND user_id = ?'
    );
    $query->bind_param('ii', $event_id, $user_id);
    $query->execute();
    return $db->affected_rows > 0;
}
function getMyRegistrations($user_id)
{
    global $db;
    $query = $db->prepare(
        'SELECT r.*, e.title, e.location, e.event_date, e.image
         FROM tbl_registrations r
         JOIN tbl_events e ON e.id = r.event_id
         WHERE r.user_id = ?
         ORDER BY e.event_date ASC'
    );
    $query->bind_param('i', $user_id);
    $query->execute();
    return $query->get_result();
}function getEventRegistrations($event_id)
{
    global $db;
    $query = $db->prepare(
        'SELECT r.*, u.name, u.username, u.photo
         FROM tbl_registrations r
         JOIN tbl_users u ON u.id = r.user_id
         WHERE r.event_id = ?
         ORDER BY r.created_at ASC'
    );
    $query->bind_param('i', $event_id);
    $query->execute();
    return $query->get_result();
}
function updateRegistrationStatus($reg_id, $status)
{
    global $db;
    $query = $db->prepare(
        'UPDATE tbl_registrations SET status = ? WHERE id = ?'
    );
    $query->bind_param('si', $status, $reg_id);
    $query->execute();
    return $db->affected_rows > 0;
}
function getEventStats()
{
    global $db;
    $stats = new stdClass();

    $r = $db->query('SELECT COUNT(*) as cnt FROM tbl_events');
    $stats->total_events = $r->fetch_object()->cnt;

    $r = $db->query('SELECT COUNT(*) as cnt FROM tbl_registrations');
    $stats->total_registrations = $r->fetch_object()->cnt;

    $r = $db->query('SELECT COUNT(*) as cnt FROM tbl_registrations WHERE status = "pending"');
    $stats->pending = $r->fetch_object()->cnt;

    $r = $db->query('SELECT COUNT(*) as cnt FROM tbl_users WHERE level <> "admin"');
    $stats->total_users = $r->fetch_object()->cnt;

    return $stats;
}