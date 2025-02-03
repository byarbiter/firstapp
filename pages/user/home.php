<div class="container mt-5">
  <h3>User List</h3>
  <div class="card">
    <div class="card-body">
      <table class="table table-hover">
        <tr>
          <th>ID</th>
          <th>User_Label</th>
        </tr>
        <?php
        // first method
        $manage_users = getUsers();
        if ($manage_users !== null) {
          while ($rows = $manage_users->fetch_object()) {
            echo '<tr>
                    <td>' . $rows->id_user . '</td>
                    <td>' . $rows->user_label . '</td>
                  </tr>';
          }
        }
        ?>
        <!-- <?php
              // second method
              $manage_users = getUsers();
              if ($manage_users !== null) {
                while ($rows = $manage_users->fetch_object()) {
              ?>
            <tr>
              <td><?php echo $rows->id_user ?></td>
              <td><?php echo $rows->user_label ?></td>
            </tr>
        <?php
                }
              }
        ?> -->

      </table>
    </div>
  </div>
</div>