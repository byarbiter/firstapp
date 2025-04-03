<?php
function addProductToCart($id_product) {
  global $db;
  $cart = null;
  $user = LoggedInUser();
    $query = $db->query("SELECT * FROM tbl_cart where id_user = $user->id_user and status = 'pending' ");
    if ($query->num_rows) {
        $cart = $query->fetch_object();
    } else{
      $query = $db->prepare("INSERT INTO tbl_cart (id_user, status) values (?,'pending')");
      $query->bind_param('i', $user->id_user);
      $query->execute();
      $result = $query -> get_result();
      if($db->affected_rows){
        $cart = $result->fetch_object();
      }
    }

    if($cart){
      $query = $db->prepare("INSERT INTO tbl_cart_detail (id_cart, id_product, qty) values (?, ?, 1)");
      $query->bind_param('ii', $cart->id_cart, $id_product);
      $query->execute();
      if($db->affected_rows){
        return true;
      }
    }

    return null;
}
