<?php
if (!isset($_GET['id']) || getStockByID($_GET['id']) === null) {
    header('Location: ./?page=stock/home');
    exit;
}

$manage_stock = getStockByID($_GET['id']);
$products = getProduct(); // You'll need a function to get all products

$id_product_err = $qty_err = $date_err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_product = $_POST['id_product'] ?? '';
    $qty = $_POST['qty'] ?? '';
    $date = $_POST['date'] ?? '';

    // Validation
    if (empty($id_product)) {
        $id_product_err = "Product is required!";
    }
    if (empty($qty)) {
        $qty_err = "Quantity is required!";
    } elseif (!is_numeric($qty)) {
        $qty_err = "Quantity must be a number!";
    }
    if (empty($date)) {
        $date_err = "Date is required!";
    }

    if (empty($id_product_err) && empty($qty_err) && empty($date_err)) {
        if (updateStock($_GET['id'], $id_product, $qty, $date)) {
            echo '<div class="alert alert-success" role="alert">
                Stock Updated successfully. <a href="./?page=stock/home">Stock Page</a>
                </div>';

            // Reset form and errors
            $id_product_err = $qty_err = $date_err = '';
            unset($_POST['id_product'], $_POST['qty'], $_POST['date']);

            // Refresh stock data after update
            $manage_stock = getStockByID($_GET['id']);
        } else {
            echo '<div class="alert alert-danger" role="alert">
            Stock update Failed.
            </div>';
        }
    }
}
?>

<form action="./?page=stock/update&id=<?php echo $manage_stock->id_stock ?>" method="post" class="w-50 mx-auto">
    <h1>Update Stock</h1>
    
    <div class="mb-3">
        <label for="id_product" class="form-label">Product</label>
        <select name="id_product" class="form-control <?php echo $id_product_err !== '' ? 'is-invalid' : '' ?>">
            <option value="">Select Product</option>
            <?php if ($products !== null): ?>
                <?php while ($product = $products->fetch_object()): ?>
                    <option value="<?php echo $product->id_product ?>" 
                        <?php echo (isset($_POST['id_product']) ? $_POST['id_product'] : $manage_stock->id_product) == $product->id_product ? 'selected' : '' ?>>
                        <?php echo $product->name ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
        <div class="invalid-feedback">
            <?php echo $id_product_err ?>
        </div>
    </div>

    <div class="mb-3">
        <label for="qty" class="form-label">Quantity</label>
        <input type="number" name="qty" class="form-control <?php echo $qty_err !== '' ? 'is-invalid' : '' ?>" 
               value="<?php echo isset($_POST['qty']) ? $_POST['qty'] : $manage_stock->qty ?>">
        <div class="invalid-feedback">
            <?php echo $qty_err ?>
        </div>
    </div>

    <div class="mb-3">
        <label for="date" class="form-label">Date</label>
        <input type="date" name="date" class="form-control <?php echo $date_err !== '' ? 'is-invalid' : '' ?>" 
               value="<?php echo isset($_POST['date']) ? $_POST['date'] : $manage_stock->date ?>">
        <div class="invalid-feedback">
            <?php echo $date_err ?>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="./?page=stock/home" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>