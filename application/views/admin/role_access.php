<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
    
    <?= $this->session->flashdata('message'); ?>
  <div class="row">
      <div class="col-lg-6">
          <!-- table -->
         <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Role <?= $role['role'];?></h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Menu</th>
                                <th scope="col">Access</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php $i = 1; ?>
                              <?php foreach ($menu as $m) : ?>
                              <tr>
                                  <th scope="row"><?= $i++; ?></th>
                                  <td><?= $m['menu'] ; ?></td>
                                  <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" <?= check_access($role['id'], $m['id']); ?> data-role="<?= $role['id']; ?>" data-menu="<?= $m['id']; ?>">
                                    </div>
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
