<?php
function categorySlugExists($slug)
{
  global $db;
  $query = $db->query("SELECT id_category FROM tbl_category WHERE slug = '$slug'");
  //$db->close();
  if ($query->num_rows) {
    return true;
  }
  return false;
}
function createCategory($name, $slug)
{
  global $db;
  $query = $db->query("INSERT INTO tbl_category (name, slug) VALUES ('$name', '$slug')");
  //$db->close();
  if ($query) {
    return true;
  }
  return false;
}

function getCategories()
{
  global $db;
  $query = $db->query("SELECT * FROM tbl_category");
  if ($query->num_rows) {
    return $query;
  }
  return null;
}
function getCategoryByID($id)
{
  global $db;
  $query = $db->query("SELECT * FROM tbl_category WHERE id_category = '$id'");
  if ($query->num_rows) {
    return $query->fetch_object();
  }
  return null;
}
function updateCategory($id, $name, $slug)
{
  global $db;

  // if (empty($username)) {
  //     $username_query = "";
  // } else {
  //     $username_query = ", username = '$username'";
  // }

  // $username_query = empty($username) ?  "" : ", username = '$username'";

  // if (empty($passwd)) {
  //     $passwd_query = "";
  // } else {
  //     $passwd_query = ", passwd = '$passwd'";
  // }


  // $db->query("UPDATE tbl_user SET user_label = '$user_label', username = '$username', passwd = '$passwd' WHERE id_user = '$id'");

  $db->query("UPDATE tbl_category SET name = '$name', slug = '$slug' WHERE id_category = '$id'");

  if ($db->affected_rows) {
    return true;
  }
  return false;
}
function deleteCategory($id)
{
  global $db;
  $db->query("DELETE FROM tbl_category WHERE id_category ='$id'");
  if ($db->affected_rows) {
    return true;
  }
  return false;
}
