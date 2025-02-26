
<?php

if (!isset($_GET['id']) || getProductByID($_GET['id']) === null) {
    header('Location: ./?page=product/home');
    exit;
}
if (deleteProduct($_GET['id'])) {
    echo '<div class="alert alert-success" role="alert">
        Product deleted successfully.<a href="./?page=product/home">Product page</a>
        </div>';
} else {
    echo '<div class="alert alert-danger" role="alert">
        Cannot delete Product! <a href="./?page=product/home">Product page</a>
        </div>';
}
/*if (!isset($_GET['id']) || getUserByID($_GET['id']) === null) {
    header('Location: ./?page=user/home');
    exit;
}

if (isset($_POST['confirm_delete'])) {
    if (deleteUser($_GET['id'])) {
        echo '<div class="alert alert-success" role="alert">
        User deleted successfully. <a href="./?page=user/home">User page</a>
        </div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">
        Cannot delete user! <a href="./?page=user/home">User page</a>
        </div>';
    }
} else {
    // Show confirmation dialog
    ?>
    <div class="alert alert-warning" role="alert">
        Are you sure you want to delete this user?
        <form method="POST">
            <button type="submit" name="confirm_delete" class="btn btn-danger">Delete</button>
            <a href="./?page=user/home" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <?php
}*/
?>


