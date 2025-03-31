<?php

function getUsers()
{
    global $db;
    $query = $db->prepare("SELECT id_user, user_label, level FROM tbl_user WHERE level = 'User'");
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows) {
        return $result;
    }
    return null;
}

function createUser($user_label, $username, $passwd)
{
    global $db;
    $query = $db->prepare("INSERT INTO tbl_user (user_label, username, passwd, level) VALUES (?, ?, ?, 'User')");
    $query->bind_param("sss", $user_label, $username, $passwd);
    
    if ($query->execute()) {
        return true;
    }
    return false;
}

function getUserByID($id)
{
    global $db;
    $query = $db->prepare("SELECT id_user, user_label, level FROM tbl_user WHERE id_user = ? AND level = 'User'");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows) {
        return $result->fetch_object();
    }
    return null;
}

function updateUser($id, $user_label, $username, $passwd)
{
    global $db;
    
    // Initialize base query
    $query = "UPDATE tbl_user SET user_label = ?";
    $types = "s";
    $params = [$user_label];
    
    // Add username if provided
    if (!empty($username)) {
        $query .= ", username = ?";
        $types .= "s";
        $params[] = $username;
    }
    
    // Add password if provided
    if (!empty($passwd)) {
        $query .= ", passwd = ?";
        $types .= "s";
        $params[] = $passwd;
    }
    
    // Complete the query
    $query .= " WHERE id_user = ?";
    $types .= "i";
    $params[] = $id;
    
    // Prepare and execute
    $stmt = $db->prepare($query);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

function deleteUser($id)
{
    global $db;
    $query = $db->prepare("DELETE FROM tbl_user WHERE id_user = ?");
    $query->bind_param("i", $id);
    $query->execute();
    
    if ($db->affected_rows) {
        return true;
    }
    return false;
}