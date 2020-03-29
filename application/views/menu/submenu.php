<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
    <?php if(validation_errors()) : ?>
    <div class='row mt-3'>
        <div class='col-md-2'>
            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <?= validation_errors(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?= $this->session->flashdata('message'); ?>
  <div class="row">
      <div class="col-lg">
          <a href="" class="btn btn-primary mb-3"  data-toggle="modal" data-target="#newSubMenuModal">Add New Submenu</a>
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
                                    <th scope="col">Title</th>
                                    <th scope="col">Menu</th>
                                    <th scope="col">Url</th>
                                    <th scope="col">Icon</th>
                                    <th scope="col">Active</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($subMenu as $sm) : ?>
                                <tr>
                                    <th scope="row"><?= $i++; ?></th>
                                    <td><?= $sm['title'] ; ?></td>
                                    <td><?= $sm['menu'] ; ?></td>
                                    <td><?= $sm['url'] ; ?></td>
                                    <td><?= $sm['icon'] ; ?></td>
                                    <td><?= $sm['is_active'] ; ?></td>
                                    <td>
                                        <a href="" class="badge badge-success" data-toggle="modal" data-target="#editSubMenuModal<?= $sm['id']; ?>">
                                            edit
                                        </a>
                                        <a href="<?= base_url('menu/deleteSubMenu/'.$sm['id']); ?>" class="badge badge-danger" onclick="return confirm('Are you sure?');">
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
<div class="modal fade" id="newSubMenuModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newSubMenuModalLabel">Add New Sub Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('menu/submenu'); ?>" method="post">
        <div class="modal-body">
            <div class="form-group">
                <input type="text" class="form-control" id="title" name="title" placeholder="Submenu title">
            </div>
            <div class="form-group">
                <select name="menu_id" id="menu_id" class="form-control">
                    <option value="" selected disabled>--Select menu--</option>
                    <?php foreach($menu as $m): ?>
                    <option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="url" name="url" placeholder="Submenu url">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu icon">
            </div>
            <div class="form-group ml-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
                    <label class="form-check-label" for="defaultCheck1">
                        Active?
                    </label>
                </div>
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

<?php foreach($subMenu as $sm): ?>
<!-- Modal -->
<div class="modal fade" id="editSubMenuModal<?= $sm['id']; ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSubMenuModalLabel">Edit Sub Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('menu/editSubMenu/'.$sm['id']); ?>" method="post">
        <div class="modal-body">
            <div class="form-group">
                <input type="hidden" class="form-control" id="id" name="id" value="<?= $sm['id']; ?>">
                <input type="text" class="form-control" id="title" name="title" value="<?= $sm['title']; ?>">
            </div>
            <div class="form-group">
                <select name="menu_id" id="menu_id" class="form-control">
                    <?php foreach($menu as $m): 
                        if ($m['menu'] == $sm['menu']): ?>
                            <option value="<?= $m['id']; ?>" selected><?= $m['menu']; ?></option>
                        <?php else : ?>
                            <option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="url" name="url" value="<?= $sm['url']; ?>">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="icon" name="icon" value="<?= $sm['icon']; ?>">
            </div>
            <div class="form-group ml-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
                    <label class="form-check-label" for="defaultCheck1">
                        Active?
                    </label>
                </div>
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
<?php endforeach; ?>
