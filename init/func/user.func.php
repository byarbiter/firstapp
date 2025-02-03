<?php
function usernameExists($username)
{
  global $db;
  $query = $db->query("SELECT id_user FROM tbl_user WHERE username = '$username'");
  // $db->close();
  if ($query->num_rows) {
    return true;
  }
  return false;
}

function logUserIn($username, $passwd)
{
  global $db;
  $query = $db->query("SELECT id_user FROM tbl_user WHERE username = '$username' AND passwd = '$passwd'");
  // $db->close();
  if ($query->num_rows) {
    $_SESSION['id_user'] = $query->fetch_object()->id_user;
    //  $_SESSION['id_user'] = $query->fetch_assoc[id_user];
    return true;
  }
  return false;
}
function LoggedInUser()
{
  global $db;
  if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $query = $db->query("SELECT user_label, id_user FROM tbl_user WHERE id_user = '$id_user'");
    if ($query->num_rows) {
      return $query->fetch_object();
    } else {
      return false;
    }
  } else {
    return false;
  }
}
function isAdmin(){
  global $db;
  if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $query = $db->query("SELECT user_label, id_user FROM tbl_user WHERE id_user = '$id_user' AND level = 'Admin'");
    if ($query->num_rows) {
      return $query->fetch_object();
    } else {
      return false;
    }
  } else {
    return false;
  }
}