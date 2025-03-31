<?php
function usernameExists($username)
{
    global $db;
    $query = $db->prepare("SELECT id_user FROM tbl_user WHERE username = ?");
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();
    return $result->num_rows > 0;
}

function logUserIn($username, $passwd)
{
    global $db;
    
    /* PLAIN TEXT VERSION (current implementation) */
    $query = $db->prepare("SELECT id_user, user_label, passwd, level FROM tbl_user WHERE username = ? AND passwd = ?");
    $query->bind_param('ss', $username, $passwd);
    $query->execute();
    $result = $query->get_result();
    
    /* HASHED PASSWORD VERSION (for future implementation)
    $query = $db->prepare("SELECT id_user, user_label, passwd, level FROM tbl_user WHERE username = ?");
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();
    */
    
    if ($result->num_rows) {
        $user = $result->fetch_object();
        
        /* PLAIN TEXT CHECK (current) */
        if ($passwd === $user->passwd) {
            $_SESSION['id_user'] = $user->id_user;
            $_SESSION['user_label'] = $user->user_label;
            $_SESSION['user_role'] = $user->level;
            return true;
        }
        
        /* HASHED PASSWORD CHECK (future)
        if (password_verify($passwd, $user->passwd)) {
            $_SESSION['id_user'] = $user->id_user;
            $_SESSION['user_label'] = $user->user_label;
            $_SESSION['user_role'] = $user->level;
            return true;
        }
        */
    }
    
    return false;
}

function LoggedInUser()
{
    if (isset($_SESSION['id_user'])) {
        return (object)[
            'id_user' => $_SESSION['id_user'],
            'user_label' => $_SESSION['user_label'] ?? null
        ];
    }
    return false;
}

function getUserRole()
{
    return $_SESSION['user_role'] ?? false;
}

function isAdmin()
{
    return getUserRole() === 'Admin';
}

function isUser()
{
    return getUserRole() === 'User';
}

function registerAccount()
{
    global $db;
    
    // Validate input
    if (empty($_POST['username']) || empty($_POST['passwd']) || empty($_POST['user_label'])) {
        return false;
    }
    
    $username = trim($_POST['username']);
    $user_label = trim($_POST['user_label']);
    
    // Check if username exists
    if (usernameExists($username)) {
        return false;
    }
    
    /* PLAIN TEXT VERSION (current) */
    $passwd = $_POST['passwd'];
    
    /* HASHED PASSWORD VERSION (future)
    $passwd = password_hash($_POST['passwd'], PASSWORD_DEFAULT);
    // Example hash for "admin123": $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
    */
    
    // Insert new user
    $query = $db->prepare("INSERT INTO tbl_user (user_label, username, passwd, level) VALUES (?, ?, ?, 'User')");
    $query->bind_param("sss", $user_label, $username, $passwd);
    
    if ($query->execute()) {
        return $db->insert_id;
    }
    
    return false;
}

function validateSession()
{
    if (isset($_SESSION['id_user'])) {
        global $db;
        $query = $db->prepare("SELECT 1 FROM tbl_user WHERE id_user = ?");
        $query->bind_param("i", $_SESSION['id_user']);
        $query->execute();
        return $query->get_result()->num_rows > 0;
    }
    return false;
}