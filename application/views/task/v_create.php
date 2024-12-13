<div id="page">
  <?php include APPPATH . '/views/v_nav.php' ?>
  <div class="page-content">
    <div class="card card-style">
      <div class="content mb-0">
        <?php if (!$this->uri->segment(3)) { ?>
          <h3>Create Task</h3>
          <form action="<?= base_url('task/save_task') ?>" method="post" enctype="multipart/form-data" id="form-create-task">
            <div class="has-borders no-icon mb-2">
              <label for="project_name" class="form-label">Task Name <em>(required)</em></label>
              <input type="text" class="form-control" name="project_name" id="project_name" value="<?= set_value('project_name') ?>">
              <?= form_error('project_name') ?>
            </div>
            <div class="has-borders no-icon mb-2">
              <label for="member_task" class="form-label">Task Member <em>(required)</em></label>
              <select class="form-control js-example-basic-multiple" name="member_task[]" id="member_task" multiple>
                <?php foreach ($sendto as $data) { ?>
                  <option value="<?= $data->nip ?>" <?= set_select('member_task[]', $data->nip) ?>><?= $data->nama ?> (<?php echo $data->nama_jabatan; ?>)</option>
                <?php } ?>
              </select>
              <?= form_error('member_task[]') ?>
            </div>
            <div class="has-borders no-icon mb-2">
              <label for="activity">Task Activity <em>(required)</em></label>
              <select name="activity" id="activity" class="form-select">
                <option value="1" <?= set_select('activity', '1') ?>>Open</option>
                <option value="2" <?= set_select('activity', '2') ?>>Pending</option>
                <option value="3" <?= set_select('activity', '3') ?>>Close</option>
              </select>
              <?= form_error('activity') ?>
            </div>
            <div class="has-borders no-icon mb-2">
              <label for="comment">Description</label>
              <textarea name="comment" id="comment" class="form-control"><?= set_value('comment') ?></textarea>
            </div>
            <div class="my-3">
              <a href="<?= base_url('task/task') ?>" class="btn btn-warning">Back</a>
              <button type="reset" class="btn btn-primary">Reset</button>
              <button type="submit" class="btn btn-success" id="btn-submit-task">Submit Task</button>
            </div>
          </form>
        <?php } else { ?>
          <h3>Update Task</h3>
          <form action="<?= base_url('task/update_task/' . $this->uri->segment(3)) ?>" method="post" enctype="multipart/form-data" id="form-create-task">
            <div class="has-borders no-icon mb-2">
              <label for="project_name" class="form-label">Task Name <em>(required)</em></label>
              <input type="text" class="form-control" name="project_name" id="project_name" value="<?= $task_edit['name'] ?>">
              <?= form_error('project_name') ?>
            </div>
            <div class="has-borders no-icon mb-2">
              <label for="member_task" class="form-label">Task Member <em>(required)</em></label>
              <select class="form-control js-example-basic-multiple" name="member_task[]" id="member_task" multiple>
                <?php
                foreach ($sendto as $data) :
                  if (strpos($task_edit['member'], $data->nip) !== false) {
                ?>
                    <option selected value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?>
                      (<?php echo $data->nama_jabatan; ?>)</option>
                  <?php
                  } else { ?>
                    <option value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?>
                      (<?php echo $data->nama_jabatan; ?>)</option>

                <?php }
                endforeach; ?>
              </select>
              <?= form_error('member_task[]') ?>
            </div>
            <div class="has-borders no-icon mb-2">
              <label for="activity">Task Activity <em>(required)</em></label>
              <select name="activity" id="activity" class="form-select">
                <option value="1" <?= $task_edit['activity'] == '1' ? 'selected' : '' ?>>Open</option>
                <option value="2" <?= $task_edit['activity'] == '2' ? 'selected' : '' ?>>Pending</option>
                <option value="3" <?= $task_edit['activity'] == '3' ? 'selected' : '' ?>>Close</option>
              </select>
              <?= form_error('activity') ?>
            </div>
            <div class="has-borders no-icon mb-2">
              <label for="comment">Description</label>
              <textarea name="comment" id="comment" class="form-control"><?= $task_edit['comment'] ?></textarea>
            </div>
            <div class="my-3">
              <a href="<?= base_url('task/task') ?>" class="btn btn-warning">Back</a>
              <button type="reset" class="btn btn-primary">Reset</button>
              <button type="submit" class="btn btn-success" id="btn-submit-task">Update Task</button>
            </div>
          </form>
        <?php } ?>
      </div>
    </div>
  </div>
</div>