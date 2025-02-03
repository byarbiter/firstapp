<?php
function getUsers()
{
  global $db;
  $query = $db->query("SELECT * FROM tbl_user");
  if ($query->num_rows) {
    return $query;
  } else {
    return null;
  }
}
