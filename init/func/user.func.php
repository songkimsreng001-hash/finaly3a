<?php
function createUser($name, $username, $passwd, $photo)
{
    global $db;

    $image_path = null;
    if (!empty($photo['name'])) {
        $image_path = uploadImage($photo);
    }

    $query = $db->prepare('INSERT INTO tbl_users (name,username,passwd,photo) VALUES (?,?,?,?)');
    $query->bind_param('ssss', $name, $username, $passwd, $image_path);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}function getUsers()
{
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE level <> "admin"');
    $query->execute();
    $result = $query->get_result();
    return $result;
}

function readUser($id){
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE id = ?');
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if($result->num_rows > 0){
        return $result->fetch_object();
    }
    return null;
}

function deleteUser($id){
    global $db;
    $query = $db->prepare('DELETE FROM tbl_users WHERE id = ?');
    $query->bind_param('i', $id);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}

function updateUser($id, $name, $username, $passwd, $photo)
{
    global $db;

    $image_path = null;
    if (!empty($photo['name'])) {
        $image_path = uploadImage($photo);
    }

    if ($image_path) {
        $query = $db->prepare('UPDATE tbl_users SET name=?, username=?, passwd=?, photo=? WHERE id=?');
        $query->bind_param('ssssi', $name, $username, $passwd, $image_path, $id);
    } else {

        if ($image_path) {

            $query = $db->prepare('UPDATE tbl_users 
                                   SET name=?, username=?, photo=? 
                                   WHERE id=?');

            $query->bind_param('sssi', $name, $username, $image_path, $id);

        } else {

            $query = $db->prepare('UPDATE tbl_users 
                                   SET name=?, username=? 
                                   WHERE id=?');

            $query->bind_param('ssi', $name, $username, $id);
        }

    }
    
    $query->execute();
    if ($db->affected_rows >= 0) {
        return true;
    }
    return false;
}

?>
