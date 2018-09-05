    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Users</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-2">
          <a class="btn btn-info btn-block add" data-toggle="modal" data-target="#modal-default">Add New User</a>
        </div>
      </div>
      <br>

      <!-- Default box -->
      <div class="box">
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Serial</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; foreach ($users as $user): ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $user->name; ?></td>
                <td><?php echo $user->email; ?></td>
                <td><?php echo $roles[$user->role_id]; ?></td>
                <td><?php echo ($user->is_active == 1) ? 'Active' : 'Blocked'; ?></td>
                <td>
                  <a class="btn btn-info btn-xs edit" data-id="<?php echo $user->id; ?>" data-toggle="modal" data-target="#modal-default">Edit</a>
                  <a class="btn btn-danger btn-xs delete" data-id="<?php echo $user->id; ?>">Delete</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Serial</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
