<?php
$username_err = $passwd_err = ''; // Initialize error variables

if (isset($_POST['username']) && isset($_POST['passwd'])) { // Check if username and password are set
  $username = $_POST['username']; // Get the username from POST data
  $passwd = $_POST['passwd']; // Get the password from POST data
  /*
    $username = $db->real_escape_string($username);                             // Escape the username for database query
    $passwd = $db->real_escape_string($passwd);                                 // Escape the password for database query
    $query = $db->query("SELECT * FROM tbl_user WHERE username = '$username'"); // Query to check if username exists
  */
  if (usernameExists($username)) { // Check if the username exists
    if (logUserIn($username, $passwd)) { // Attempt to log the user in
      // $_SESSION['id_user'] = $query->fetch_object()->id_user; // Set the user session ID
      header('Location: ./?page=dashboard'); // Redirect to home page on successful login
    } else {
      $passwd_err = 'Password not match'; // Set password error message
    }
  } else {
    $username_err = 'Username not found'; // Set username error message
  }
}
?>

<form style="max-width: 500px;" class="mx-auto" action="./?page=login" method="POST">
  <h1>Login Page</h1>
  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" name="username" class="form-control <?php echo $username_err !== '' ? 'is-invalid' : '' ?>" id="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>">
    <div class="invalid-feedback">
      <?php echo $username_err ?>
    </div>
    <div class="mb-3">
      <label for="passwd" class="form-label">Password</label>
      <input type="password" name="passwd" class="form-control <?php echo $passwd_err !== '' ? 'is-invalid' : '' ?>" id="passwd" value="<?php echo isset($_POST['passwd']) ? $_POST['passwd'] : '' ?>">
      <div class="invalid-feedback">
        <?php echo $passwd_err ?>
      </div>
    </div>
    <!-- <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="check">
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div> -->
    <button type="submit" class="btn btn-primary">Login</button>
</form>