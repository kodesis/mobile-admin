<div id="page">
  <?php include APPPATH . '/views/v_nav.php' ?>
  <div class="page-content">
    <div class="card card-style">
      <div class="content mb-0">
        <?php if ($this->session->flashdata('msg')) {
          echo $this->session->flashdata('msg');
        }
        ?>
        <form action="<?= base_url('auth/change') ?>" class="my-3" enctype="multipart/form-data" id="form-memo" method="post">
          <div class="has-borders no-icon mb-2">
            <label for="old_password" class="form-label">Old Password <em>(required)</em></label>
            <input type="text" class="form-control" name="old_password" id="old_password" value="<?= set_value('old_password') ?>">
            <?= form_error('old_password') ?>
          </div>
          <div class="has-borders no-icon mb-2">
            <label for="new_password" class="form-label">New Password <em>(required)</em></label>
            <input type="text" class="form-control" name="new_password" id="new_password" value="<?= set_value('new_password') ?>">
            <?= form_error('new_password') ?>
          </div>
          <div class="has-borders no-icon mb-2">
            <label for="confirm_password" class="form-label">Confirm Password <em>(required)</em></label>
            <input type="text" class="form-control" name="confirm_password" id="confirm_password" value="<?= set_value('confirm_password') ?>">
            <?= form_error('confirm_password') ?>
          </div>
          <div>
            <a href="<?= base_url('home') ?>" class="btn btn-warning">Back</a>
            <button type="submit" class="btn btn-success" id="save-password">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>