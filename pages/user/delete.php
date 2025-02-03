<?php
// Check if 'id' parameter is not set or if the user with the given ID does not exist
if (!isset($_GET['id']) || getUserByID($_GET['id']) === null) {
  // Redirect to the user home page if the condition is met
  header('Location: ./?page=user/home');
}

// Attempt to delete the user with the given ID
if (deleteUser($_GET['id'])) {
  // Display a success message if the user was deleted successfully
  echo '<div class="alert alert-success" role="alert">
      User deleted successfully. <a href="./?page=user/home">User page</a>
      </div>';
} else {
  // Display an error message if the user could not be deleted
  echo '<div class="alert alert-danger" role="alert">
    can not delete user! <a href="./?page=user/home">User page</a>
    </div>';
}