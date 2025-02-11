<?php
if (!isset($_GET['id']) || getCategoryByID($_GET['id']) === null) {
  header('Location: ./?page=category/home');
}

$manage_category = getCategoryByID($_GET['id']);
$name_err = $slug_err = '';
if (isset($_POST['name']) && isset($_POST['slug']) ) {
  $id_category = $_GET['id'];
  $name = $_POST['name'];
  $slug = $_POST['slug'];

  if (empty($name)) {
    $name_err = 'Name is required';
  }

  if (!empty($slug) && usernameExists($slug)) {
    $slug_err = 'Slug already exists';
  }

  if (empty($name_err) && empty($slug_err)) {
    if (updateCategory($id_category, $name, $slug)) {
      header('Location: ./?page=category/home');
    } else {
      echo '<div class="alert alert-danger" role="alert">
                    Can not update user!
                    </div>';
    }
  }
}
?>

<form action="./?page=category/update&id=<?php echo $manage_category->id_category ?>" method="post" class="w-50 mx-auto">
  <h1>Update Category ID: <?php echo $manage_category->id_category ?></h1>
  <div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" name="name" class="form-control <?php echo $name_err !== '' ?  'is-invalid' : ' ' ?>" id="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : $manage_category->name ?>">
    <div class="invalid-feedback">
      <?php echo $name_err ?>
    </div>
  </div>
  <div class="mb-3">
    <label for="slug" class="form-label">Slug</label>
    <input type="text" name="slug" placeholder="(optional) input slug to update" class="form-control <?php echo $slug_err !== '' ?  'is-invalid' : ' ' ?>" id="slug" value="<?php echo isset($_POST['slug']) ? $_POST['slug'] : '' ?>">
    <div class="invalid-feedback">
      <?php echo $slug_err ?>
    </div>
  </div>
  <div class="d-flex justify-content-between">
    <a role="button" href="./?page=category/home" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-success">Update</button>
  </div>
</form>