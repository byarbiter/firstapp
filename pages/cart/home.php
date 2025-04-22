<div class="container mt-5">
  <div class="d-flex justify-content-between">
    <h3>Cart List</h3>
  </div>
  <div class="card">
    <div class="card-body">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Fetch cart details
          $manage_cart_details = getPendingCartProductDetails();
          if ($manage_cart_details !== null) {
            while ($row = $manage_cart_details->fetch_object()) {
              $product = getProductById($row->id_product);
          ?>
              <tr>
                <td><?php echo $product->name ?></td>
                <td><?php echo $product->price ?>$</td>
                <td>
                  <input class="w-50 form-control" type="number" value="1">
                </td>
                <td>
                  <a class="btn btn-danger" href="./?page=cart/delete&id=<?php echo $row->id_cart_detail ?>">delete</a>
                </td>
              </tr>
          <?php
            }
          }

          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>