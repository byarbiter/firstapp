<?php
// Fetch statistics from the database
$product_count = $db->query("SELECT COUNT(*) AS count FROM tbl_product")->fetch_object()->count;
$category_count = $db->query("SELECT COUNT(*) AS count FROM tbl_category")->fetch_object()->count;
$stock_count = $db->query("SELECT COUNT(*) AS count FROM tbl_stock")->fetch_object()->count;
$user_count = $db->query("SELECT COUNT(*) AS count FROM tbl_user")->fetch_object()->count;
?>

    <header class="dashboard-header">
        <h1>Welcome to the Dashboard</h1>
    </header>

    <main class="dashboard-main">
        <section class="statistics">
            <h2>Statistics</h2>
            <div class="stats-container">
                <div class="stat-item">
                    <h3>Products</h3>
                    <p><?php echo $product_count; ?></p>
                </div>
                <div class="stat-item">
                    <h3>Categories</h3>
                    <p><?php echo $category_count; ?></p>
                </div>
                <div class="stat-item">
                    <h3>Stock Items</h3>
                    <p><?php echo $stock_count; ?></p>
                </div>
                <div class="stat-item">
                    <h3>Users</h3>
                    <p><?php echo $user_count; ?></p>
                </div>
            </div>
        </section>