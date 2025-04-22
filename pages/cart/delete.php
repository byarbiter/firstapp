<?php
// Check if ID parameter exists
if (!isset($_GET['id'])) {
  header('Location: ./?page=cart/home');
  exit;
}

$id_cart_detail = $_GET['id'];

// Call the function to delete the item
if (deleteCartItem($id_cart_detail)) {
  // Successful deletion
  header('Location: ./?page=cart/home');
} else {
  // Failed deletion
  header('Location: ./?page=cart/home&error=delete_failed');
}
exit;
