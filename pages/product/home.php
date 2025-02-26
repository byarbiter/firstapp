<div class="container mt-5">
    <div class="d-flex justify-content-between">
        <h3>Product List</h3>
        <div>
            <a href="./?page=product/create" class="btn  btn-success">Add Product</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Short Description</th>  
                    <th>Long Description</th>
                    <th>Action</th>
                </tr>
                <?php
                // $manage_users = getUsers();
                // if($manage_users !== null) {
                //     while($row = $manage_users->fetch_object()) {
                //         echo '<tr>
                //         <td>' .$row->id_user.'</td>
                //         <td>'.$row->user_label.'</td>
                //         </tr>';
                //     }
                // }
                ?>
                <?php
                $manage_product = getProduct();
                if ($manage_product !== null) {
                    while ($row = $manage_product->fetch_object()) {
                ?>
                        <tr>
                            <td><?php echo $row->id_product ?></td>
                            <td><?php echo $row->name ?></td>
                            <td><?php echo $row->slug ?></td>
                            <td><?php echo $row->price ?></td>
                            <td><?php echo $row->qty ?></td>
                            <td><?php echo $row->short_des ?></td>
                            <td><?php echo $row->long_des ?></td>
                            <td>
                                <a class="btn btn-primary" href="./?page=product/update&id=<?php echo $row->id_product ?>">update</a>
                                <a class="btn btn-danger" href="./?page=product/delete&id=<?php echo $row->id_product ?>">delete</a>
                            <td>

                        </tr>
                <?php
                    }
                }
                ?>


            </table>
        </div>
    </div>
</div>