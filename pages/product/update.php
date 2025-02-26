<?php
if (!isset($_GET['id']) || getProductByID($_GET['id']) === null) {
    header('Location: ./?page=product/home');
    exit;
}

$manage_product = getProductByID($_GET['id']);

$name_err = $slug_err = $price_err = $short_des_err = $long_des_err = $id_categories_err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $price = $_POST['price'] ?? '';
    $short_des = $_POST['short_des'] ?? '';
    $long_des = $_POST['long_des'] ?? '';
    $id_categories = isset($_POST['id_categories']) ? $_POST['id_categories'] : [];

    // Validation
    if (empty($name)) {
        $name_err = "Name is required!";
    }
    if (empty($slug)) {
        $slug_err = "Slug is required!";
    } else {
        if ($slug !== $manage_product->slug && productSlugExists($slug)) {
            $slug_err = "Slug is already exists!";
        }
    }
    if (empty($price)) {
        $price_err = "Price is required!";
    }
    if (empty($short_des)) {
        $short_des_err = "Short Description is required!";
    }
    if (empty($long_des)) {
        $long_des_err = "Long Description is required!";
    }
    if (empty($id_categories)) {
        $id_categories_err = "At least one category is required!";
    }

    // Debug info - you can remove this later
    // echo '<div class="alert alert-info">Processing form: ';
    // echo 'Categories selected: ' . (empty($id_categories) ? 'None' : implode(', ', $id_categories));
    // echo '</div>';

    if (empty($name_err) && empty($slug_err) && empty($price_err) && empty($short_des_err) && empty($long_des_err) && empty($id_categories_err)) {
        if (updateProduct($manage_product->id_product, $name, $slug, $price, $short_des, $long_des, $id_categories)) {
            echo '<div class="alert alert-success" role="alert">
                Product Updated successfully. <a href="./?page=product/home">Product Page</a>
                </div>';
            $name_err = $slug_err = $price_err = $short_des_err = $long_des_err = $id_categories_err = '';
            unset($_POST['name']);
            unset($_POST['slug']);
            unset($_POST['price']);
            unset($_POST['short_des']);
            unset($_POST['long_des']);
            unset($_POST['id_categories']);
        } else {
            echo '<div class="alert alert-danger" role="alert">
            Product update Failed.
            </div>';
        }
    }
}
// Make sure we have the latest data
$manage_product = getProductByID($_GET['id']);
?>

<form action="./?page=product/update&id=<?php echo $manage_product->id_product ?>" method="post" class="w-50 mx-auto">
    <h1>Update Product</h1>
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" class="form-control <?php echo $name_err !== '' ? 'is-invalid' : '' ?>" value="<?php echo isset($_POST['name']) ? $_POST['name'] : $manage_product->name ?>">
        <div class="invalid-feedback">
            <?php echo $name_err ?>
        </div>
    </div>

    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control <?php echo $slug_err !== '' ? 'is-invalid' : '' ?>" value="<?php echo isset($_POST['slug']) ? $_POST['slug'] : $manage_product->slug ?>">
        <div class="invalid-feedback">
            <?php echo $slug_err ?>
        </div>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" name="price" class="form-control <?php echo $price_err !== '' ? 'is-invalid' : '' ?>" value="<?php echo isset($_POST['price']) ? $_POST['price'] : $manage_product->price ?>">
        <div class="invalid-feedback">
            <?php echo $price_err ?>
        </div>
    </div>

    <div class="mb-3">
        <label for="short_des" class="form-label">Short Description</label>
        <textarea name="short_des" class="form-control <?php echo $short_des_err !== '' ? 'is-invalid' : '' ?>"><?php echo isset($_POST['short_des']) ? $_POST['short_des'] : $manage_product->short_des ?></textarea>
        <div class="invalid-feedback">
            <?php echo $short_des_err ?>
        </div>
    </div>

    <div class="mb-3">
        <label for="long_des" class="form-label">Long Description</label>
        <textarea name="long_des" class="form-control <?php echo $long_des_err !== '' ? 'is-invalid' : '' ?>"><?php echo isset($_POST['long_des']) ? $_POST['long_des'] : $manage_product->long_des ?></textarea>
        <div class="invalid-feedback">
            <?php echo $long_des_err ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Categories</label>
        <div class="border rounded p-3 <?php echo $id_categories_err !== '' ? 'border-danger' : '' ?>">
            <?php
            $categories = getCategories();
            if ($categories !== null) {
                while ($category = $categories->fetch_object()) {
                    $checked = '';
                    $product_categories = getProductCategories($manage_product->id_product);
                    if ($product_categories !== null) {
                        mysqli_data_seek($product_categories, 0); // Reset the pointer to start
                        while ($product_category = $product_categories->fetch_object()) {
                            if ($product_category->id_category == $category->id_category) {
                                $checked = 'checked';
                                break;
                            }
                        }
                    }
                    echo '<div class="form-check">';
                    echo '<input class="form-check-input" type="checkbox" name="id_categories[]" value="' . $category->id_category . '" id="category_' . $category->id_category . '" ' . $checked . '>';
                    echo '<label class="form-check-label" for="category_' . $category->id_category . '">' . $category->name . '</label>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-muted">No categories available</p>';
            }
            ?>
        </div>
        <?php if ($id_categories_err !== '') : ?>
            <div class="text-danger mt-1 small">
                <?php echo $id_categories_err ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="d-flex justify-content-between">
        <a href="./?page=product/home" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success">Update</button>
    </div>


</form>