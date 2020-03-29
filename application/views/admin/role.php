<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
    <?= form_error('menu', "<div class='row mt-3'>
                <div class='col-md-6'>
                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    ",'</div></div></div>'); ?>
    <?= $this->session->flashdata('message'); ?>
  <div class="row">
      <div class="col-lg-6">
          <a href="" class="btn btn-primary mb-3"  data-toggle="modal" data-target="#newRoleModal">Add New Role</a>
         <!-- table -->
         <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $title; ?></h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php $i = 1; ?>
                              <?php foreach ($role as $r) : ?>
                              <tr>
                                  <th scope="row"><?= $i++; ?></th>
                                  <td><?= $r['role'] ; ?></td>
                                  <td>
                                    <a href="<?= base_url('admin/roleaccess/'); ?><?= $r['id']; ?>" class="badge badge-primary">
                                      access
                                    </a>
                                    <a href="" class="badge badge-success" data-toggle="modal" data-target="#editRoleModal<?= $r['id']; ?>">
                                      edit
                                    </a>
                                    <a href="<?= base_url('admin/deleteRole/'); ?><?= $r['id']; ?>" class="badge badge-danger" onclick="return confirm('Are you sure?');">
                                      delete
                                    </a>
                                  </td>
                              </tr>
                              <?php endforeach;?>
                          </tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="newRoleModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newRoleModalLabel">Add New Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('admin/role'); ?>" method="post">
        <div class="modal-body">
            <div class="form-group">
                <input type="text" class="form-control" id="role" name="role" placeholder="Role name">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- modal edit -->
<?php foreach ($role as $r): ?>
<div class="modal fade" id="editRoleModal<?= $r['id']; ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php $id = $r['id']; ?>
      <form action="<?= base_url('admin/deleterole/'.$id); ?>" method="post">
        <div class="modal-body">
            <div class="form-group">
              <input type="hidden" class="form-control" id="id" name="id" value="<?= $r['id']; ?>">
                <input type="text" class="form-control" id="menu" name="menu" value="<?= $r['role']; ?>">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

