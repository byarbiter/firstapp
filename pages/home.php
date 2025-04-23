<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <?php
            $imagePath = 'assets/images/product.jpg';
            if (file_exists($imagePath)) {
                echo '<img src="' . $imagePath . '" class="d-block w-100" alt="First slide" style="width: 800px; height: 400px; object-fit: cover;">';
            } else {
            ?>
                <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#777"></rect>
                    <text x="50%" y="50%" fill="#555" dy=".3em" text-anchor="middle" dominant-baseline="middle">First slide</text>
                </svg>
            <?php
            }
            ?>
        </div>
        <div class="carousel-item">
            <?php
            $imagePath = 'assets/images/product1.png';
            if (file_exists($imagePath)) {
                echo '<img src="' . $imagePath . '" class="d-block w-100" alt="Second slide" style="width: 800px; height: 400px; object-fit: cover;">';
            } else {
            ?>
                <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#777"></rect>
                    <text x="50%" y="50%" fill="#555" dy=".3em" text-anchor="middle" dominant-baseline="middle">Second slide</text>
                </svg>
            <?php
            }
            ?>
        </div>

        <div class="carousel-item">
            <?php
            $imagePath = 'assets/images/product2.png';
            if (file_exists($imagePath)) {
                echo '<img src="' . $imagePath . '" class="d-block w-100" alt="Third slide" style="width: 800px; height: 400px; object-fit: cover;">';
            } else {
            ?>
                <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#777"></rect>
                    <text x="50%" y="50%" fill="#555" dy=".3em" text-anchor="middle" dominant-baseline="middle">Third slide</text>
                </svg>
            <?php
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="container product-slider-section">
        <h1 class="my-3 section-heading">Featured Products</h1>

        <div class="row product-container">
            <?php
            $manage_products = getProduct();
            if ($manage_products !== null) {
                while ($row = $manage_products->fetch_object()) {
                    $imagePath = htmlspecialchars($row->image);
            ?>
                    <div class="col-md-4 mb-4">
                        <div class="card product" style="width: 18rem;">
                            <?php
                            // Add new badge for products less than 30 days old
                            if (isset($row->date_added) && (time() - strtotime($row->date_added) < 2592000)) {
                                echo '<span class="product-badge">New</span>';
                            }

                            if (file_exists($imagePath)) {
                                echo '<img src="' . $imagePath . '" class="card-img-top product-slide-img" alt="' . htmlspecialchars($row->name) . '">';
                            } else {
                                echo '<img src="' . $row->image . '" class="card-img-top product-slide-img" alt="' . htmlspecialchars($row->name) . '">';
                            }
                            ?>

                            <div class="card-body product-content">
                                <h5 class="card-title"><?php echo htmlspecialchars($row->name); ?></h5>
                                <div class="product-price">
                                    <span class="price-line"></span>
                                    <p class="card-text">$<?php echo htmlspecialchars($row->price); ?></p>
                                </div>

                                <?php if (!empty($row->short_des)) { ?>
                                    <p class="card-text product-description"><?php echo htmlspecialchars($row->short_des); ?></p>
                                <?php } ?>

                                <?php
                                // Only show condensed version of long description if available
                                if (!empty($row->long_des)) {
                                    $truncated_desc = strlen($row->long_des) > 100 ?
                                        substr(htmlspecialchars($row->long_des), 0, 100) . '...' :
                                        htmlspecialchars($row->long_des);
                                    echo '<p class="card-text product-description">' . $truncated_desc . '</p>';
                                }
                                ?>

                                <a role="button" href="./?page=cart/create&id= <?php echo htmlspecialchars($row->id_product) ?>" class="btn btn-primary add-to-cart">Add to Cart</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p>No products found</p>';
            }
            ?>
        </div>
    </div>