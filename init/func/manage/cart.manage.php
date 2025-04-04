<?php
function addProductToCart($id_product)
{
  global $db;
  $cart = null;
  $user = LoggedInUser();
  $query = $db->prepare("SELECT * FROM tbl_cart WHERE id_user = ? AND status = ?");
  $query->bind_param('is', $user->id_user, $status);
  $status = 'pending';
  $query->execute();
  $result = $query->get_result();
  if ($result->num_rows) {
    $cart = $result->fetch_object();
  } else {
    $query = $db->prepare("INSERT INTO tbl_cart (id_user, status) values (?,'pending')");
    $query->bind_param('i', $user->id_user);
    $query->execute();
    if ($db->affected_rows) {
      $cart_id = $db->insert_id;
      $query = $db->prepare("SELECT * FROM tbl_cart WHERE id_cart = ?");
      $query->bind_param('i', $cart_id);
      $query->execute();
      $result = $query->get_result();
      $cart = $result->fetch_object();
    }
  }

  if ($cart) {
    $query = $db->prepare("INSERT INTO tbl_cart_detail (id_cart, id_product, qty) values (?, ?, 1)");
    $query->bind_param('ii', $cart->id_cart, $id_product);
    $query->execute();
    if ($db->affected_rows) {
      return true;
    }
  }

  return null;
}
