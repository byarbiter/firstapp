<?php
$username_err = $passwd_err = '';

if (isset($_POST['username']) && isset($_POST['passwd'])) {
  $username = $_POST['username'];
  $passwd = $_POST['passwd'];

  if (usernameExists($username)) {
    // Check if the login function is setting the session correctly
    $loginResult = logUserIn($username, $passwd);
    if ($loginResult) {
      // Get the exact role string from the database
      $role = getUserRole();
      if ($role === 'Admin') {
        header('Location: ./?page=dashboard');
      } elseif ($role === 'User') {
        header('Location: ./?page=home');
      } else {
        header('Location: ./');
      }
      exit();
    } else {
      $passwd_err = 'Incorrect password';
    }
  } else {
    $username_err = 'Username not found';
  }
}
?>


<form action="./?page=login" method="post" class="w-50 mx-auto">
  <h1>Login Page</h1>
  <div class="mb-3">
    <label for="username" class="form-label">Username </label>
    <input type="text" name="username" class="form-control <?php echo $username_err !== '' ? 'is-invalid' : '' ?>" id="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>">
    <div class="invalid-feedback">
      <?php echo $username_err ?>
    </div>

  </div>
  <div class="mb-3">
    <label for="passwd" class="form-label">Password</label>
    <input type="password" name="passwd" class="form-control <?php echo $passwd_err !== '' ? 'is-invalid' : '' ?>" id="passwd" value="<?php echo isset($_POST['passwd']) ? $_POST['passwd'] : '' ?>">
    <div class="invalid-feedback">
      <?php echo $passwd_err ?>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Login</button>
</form>