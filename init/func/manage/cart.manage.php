<?php
function addProductToCart($id_product)
{
    global $db;
    $user = LoggedInUser(); // Assuming this function returns the logged-in user object

    if (!$user || !isset($user->id_user)) {
        return false; // No logged-in user
    }

    $status = 'pending';

    // Check if the user already has a cart
    $query = $db->prepare("SELECT * FROM tbl_cart WHERE id_user = ? AND status = ?");
    $query->bind_param('is', $user->id_user, $status);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows) {
        // Cart exists
        $cart = $result->fetch_object();
    } else {
        // Create a new cart
        $query = $db->prepare("INSERT INTO tbl_cart (id_user, status) VALUES (?, ?)");
        $query->bind_param('is', $user->id_user, $status);
        if (!$query->execute()) {
            return false; // Failed to create cart
        }

        $cart_id = $db->insert_id; // Get the ID of the newly created cart
        $cart = (object) ['id_cart' => $cart_id]; // Simulate a cart object
    }

    // Add the product to the cart
    $query = $db->prepare("INSERT INTO tbl_cart_detail (id_cart, id_product, qty) VALUES (?, ?, 1)");
    $query->bind_param('ii', $cart->id_cart, $id_product);

    if ($query->execute()) {
        return true; // Product added successfully
    }

    return false; // Failed to add product
}

function getPendingCartProductCount()
{
    global $db;
    $user = LoggedInUser();

    if (!$user || !isset($user->id_user)) {
        return 0; // No logged-in user
    }

    $status = 'pending';

    $query = $db->prepare("SELECT COUNT(*) as total FROM tbl_cart_detail 
                           INNER JOIN tbl_cart ON tbl_cart.id_cart = tbl_cart_detail.id_cart 
                           WHERE tbl_cart.status = ? AND tbl_cart.id_user = ?");
    $query->bind_param('si', $status, $user->id_user);
    $query->execute();
    $result = $query->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total']; // Return the count
    }

    return 0; // Return 0 if no rows or an error occurs
}
function getPendingCartProductDetails()
{
    global $db;
    $user = LoggedInUser();
    if (!$user || !isset($user->id_user)) {
        return null;
    }
    $status = 'pending';
    $query = $db->prepare("SELECT * FROM tbl_cart_detail
  INNER JOIN tbl_cart ON tbl_cart.id_cart = tbl_cart_detail.id_cart
  WHERE tbl_cart.id_user = ? AND tbl_cart.status = ?");
    $query->bind_param('is', $user->id_user, $status);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return $result;
    } else {
        return null;
    }
}
function deleteCartItem($id_cart_detail)
{
    global $db;
    $user = LoggedInUser(); // Get the logged-in user

    if (!$user || !isset($user->id_user)) {
        return false; // No logged-in user
    }

    // First, verify the cart item belongs to the user's cart
    $query = $db->prepare("SELECT tbl_cart_detail.id_cart_detail 
                          FROM tbl_cart_detail 
                          INNER JOIN tbl_cart ON tbl_cart.id_cart = tbl_cart_detail.id_cart 
                          WHERE tbl_cart_detail.id_cart_detail = ? AND tbl_cart.id_user = ?");

    $query->bind_param('ii', $id_cart_detail, $user->id_user);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        return false; // Cart item not found or doesn't belong to the user
    }

    // Proceed with deletion
    $deleteQuery = $db->prepare("DELETE FROM tbl_cart_detail WHERE id_cart_detail = ?");
    $deleteQuery->bind_param('i', $id_cart_detail);
    $success = $deleteQuery->execute();

    // Check if the deletion was successful
    if ($success) {
        // Check if the cart is now empty and perform any necessary cleanup
        $cartQuery = $db->prepare("SELECT COUNT(*) as item_count 
                                  FROM tbl_cart_detail 
                                  WHERE id_cart IN (SELECT id_cart FROM tbl_cart WHERE id_user = ?)");
        $cartQuery->bind_param('i', $user->id_user);
        $cartQuery->execute();
        $cartResult = $cartQuery->get_result();
        $row = $cartResult->fetch_assoc();

        // Optional: If cart is empty, you could update its status or perform other actions
        // if ($row['item_count'] == 0) {
        //     // Handle empty cart if needed
        // }
    }

    return $success;
}
