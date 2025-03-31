<?php
$name_err = $username_err = $passwd_err = '';
$success_msg = ''; // Variable to store success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_label = $_POST['user_label'];
  $username = $_POST['username'];
  $passwd = $_POST['passwd'];

  if (empty($user_label)) {
    $name_err = 'Name is required';
  }

  if (empty($username)) {
    $username_err = 'Username is required';
  } else {
    if (usernameExists($username)) {
      $username_err = 'Username already exists';
    }
  }

  if (empty($passwd)) {
    $passwd_err = 'Password is required';
  }

  if ($name_err === '' && $username_err === '' && $passwd_err === '') {
    $query = $db->prepare("INSERT INTO tbl_user (user_label, username, passwd) VALUES (?, ?, ?)");
    $query->bind_param("sss", $user_label, $username, $passwd);
    $query->execute();
    $query->close();

    $success_msg = 'Registration successful! You can now <a href="./?page=login">log in</a>.';
  }
}
?>
<form action="./?page=register" method="post" class="w-50 mx-auto">
  <h1>Register Page</h1>

  <div class="mb-3">
    <label for="user_label" class="form-label">Name</label>
    <input type="text" name="user_label" class="form-control <?php echo $name_err !== '' ? 'is-invalid' : '' ?>" id="user_label" value="<?php echo isset($_POST['user_label']) ? $_POST['user_label'] : '' ?>">
    <div class="invalid-feedback">
      <?php echo $name_err ?>
    </div>
  </div>
  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
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

  <button type="submit" class="btn btn-primary">Register</button>

  <!-- Success Message -->
  <?php if ($success_msg !== ''): ?>
    <div class="alert alert-success" role="alert" style="margin-top: 10px;">
      <?php echo $success_msg ?>
    </div>
  <?php endif; ?>

</form>