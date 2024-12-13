<style>
  #btn-create {
    position: fixed;
    bottom: 12%;
    right: 10px;
    z-index: 99;
    font-size: 18px;
    border: none;
    outline: none;
    background-color: #4A89DC;
    color: white;
    cursor: pointer;
    padding: 15px;
    border-radius: 4px;
  }

  #btn-create:hover {
    background-color: #555;
  }
</style>
<div id="page">
  <?php include APPPATH . '/views/v_nav.php' ?>
  <div class="page-content">
    <div class="content mt-0 mb-3">
      <?php if ($this->uri->segment(4) == false) {  ?>
        <h3 class="text-center my-3">Task</h3>
        <div class="card">
          <div class="text-center">
            <h2 class="badge bg-success"><?= $task->name ?></h2>
            <p class="m-2"><?= $task->comment ?></p>
            <hr>
            <span class="m-0">
              <b>Member Name</b> : <?php
                                    $data_nip = explode(';', $task->member);
                                    foreach ($data_nip as $x) {
                                      if ($x != '') {
                                        $this->db->where('nip', $x);
                                        $get = $this->db->get('users')->row_array();
                                        echo $get['nama'] . ', ';
                                      }
                                    }
                                    ?>
            </span>
          </div>
        </div>
        <div class="col-md-4 text-center">
          <a href="<?= base_url('task/task') ?>" class="btn btn-warning"> <i class="fa fa-arrow-left"></i> Back</a>
          <?php
          $cek_status = $this->db->get_where('task', ['id' => $this->uri->segment(3)])->row_array();
          $cek_role = $cek_status['pic'] == $this->session->userdata('nip');
          if ($cek_role == true && $cek_status['activity'] == '1') { ?>
            <a href="<?= base_url('task/detail_task/' . $this->uri->segment(3)) ?>" class="btn btn-primary"> <i class="fa fa-plus"></i> Add Card</a>
          <?php } ?>
          <?php
          if ($cek_status['activity'] == '1' && $cek_role == true) { ?>
            <a href="<?= base_url('task/close_task/' . $this->uri->segment(3)) ?>" class="btn btn-danger" id="btn-close-task"> Close Task</a>
          <?php } ?>
        </div>
      <?php } ?>

      <?php if ($this->uri->segment(3) == true && $this->uri->segment(4) == false) {
        foreach ($task_detail as $x) {
          $nip = $this->session->userdata('nip');
      ?>
          <div class="card card-style mt-3">
            <div class="content">
              <div class="text-end">
                <?php if ($x->responsible == $nip || $cek_status['pic'] == $nip) { ?>
                  <a href="<?= base_url('task/card_edit/' . $this->uri->segment(3) . '/' . $x->id_detail) ?>" class="badge gradient-green"><i class="fa fa-pencil"></i> Update</a>
                <?php } ?>
              </div>

              <?= preg_match("/$nip/i", $x->read) ? "" : "<span class='badge gradient-yellow color-white'>New</span>"; ?>
              <?php
              if ($x->activity == '1' && $x->due_date > date('Y-m-d')) {
                echo "<span class='badge gradient-blue color-white'>Open</span>";
              } else if ($x->activity == '3') {
                echo "<span class='badge gradient-brown color-white'>Closed</span>";
              } else {
                echo "<span class='badge gradient-red color-white'>Over Due</span>";
              }
              ?>

              <div class="text-start">
                <p class="mb-0 my-2" style="font-weight: <?= preg_match("/$nip/i", $x->read) ? '' : 'bolder' ?>; cursor:pointer" onclick="location.href='<?= base_url('task/task_view/' . $this->uri->segment(3) . '/' . $x->id_detail) ?>'">
                  <?php
                  $responsible = $this->db->get_where('users', ['nip' => $x->responsible])->row_array();
                  ?>
                  <?= $responsible['nama'] ?>
                <p class="font-10 mb-0 opacity-80"><?= date('d/m/y', strtotime($x->start_date)) . ' - ' .  date('d/m/y', strtotime($x->due_date)) ?></p>
              </div>
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="mb-n1" style="font-weight: <?= preg_match("/$nip/i", $x->read) ? '' : 'bolder' ?>; cursor:pointer" onclick="location.href='<?= base_url('task/task_view/' . $this->uri->segment(3) . '/' . $x->id_detail) ?>'"><?= $x->task_name ?></p>
                </div>
              </div>
            </div>
          </div>
        <?php }
      } else if ($this->uri->segment(4)) { ?>
        <h3 class="text-center">Card Detail</h3>
        <div class="text-center mb-3">
          <h2 class="badge bg-success"><?= $task_comment['task_name'] ?></h2>
        </div>

        <div class="item form-group">
          <div class="row">
            <div class="col-md-4">
              <a href="<?= base_url('task/task_view/' . $this->uri->segment(3)) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
          </div>
        </div>
        <div class="card p-2 rounded-3">
          <table>
            <tr>
              <th>Card Name</th>
              <td>:</td>
              <td><?= $task_comment['task_name'] ?></td>
            </tr>
            <tr>
              <th>Responsible</th>
              <td>:</td>
              <td><?= $task_comment['nama'] ?></td>
            </tr>
            <tr>
              <th>Description</th>
              <td>:</td>
              <td><?= $task_comment['description'] ?></td>
            </tr>
            <tr>
              <th>Start Date</th>
              <td>:</td>
              <td><?= $task_comment['start_date'] ?></td>
            </tr>
            <tr>
              <th>Due Date</th>
              <td>:</td>
              <td><?= $task_comment['due_date'] ?></td>
            </tr>
            <tr>
              <th>Attachment</th>
              <td>:</td>
              <td>

                <?php if ($task_comment['attachment'] != "") {
                  $att_xx = explode(';', $task_comment['attachment']);
                  $i = 1;
                ?>
                  <ul>
                    <?php foreach ($att_xx as $x) {

                    //   if (file_exists('upload/task_comment/' . $x)) {
                    //     $url = base_url('upload/task_comment/' . $x);
                    //   } else {
                    //     $url = base_url('upload/card_task/' . $x);
                    //   }
                    
                    $array = explode('.', $x);
                      $extension = end($array);
                      if ($extension == "png" || $extension == "jpg" || $extension == "jpeg") {
                    ?>
                        <li>
                          <a href="#" download style="white-space: pre-line;" data-bs-toggle="modal" data-bs-target="#exampleModal">File <?= $i++ ?></a>
                          <!-- Modal -->
                          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Attachment</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <img src="https://moc.mlejitoffice.id/upload/task_comment/<?= $x ?>" alt="attachment" width="100%">
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </li>
                      <?php } else { ?>
                        <li><a href="https://moc.mlejitoffice.id/upload/task_comment/<?= $x ?>" download style="white-space: pre-line;" onclick="PageReload()">File <?= $i++ ?></a></li>
                    <?php }
                    } ?>
                  </ul>
                <?php } else {
                  echo "";
                } ?>

              </td>
            </tr>
          </table>
        </div>

        <?php foreach ($task_comment_member as $x) {
          if ($x->member == $this->session->userdata('nip')) {
        ?>
            <div class="speech-bubble speech-left bg-highlight">
              <?= $x->comment_member ?>
              <?php if ($x->attachment != null) { ?>
                <hr>
                Attachment :
                <b>
                  <?php
                  $i = 1;
                  foreach (explode(';', $x->attachment_name) as $xx) {
                    $array = explode('.', $xx);
                    $extension = end($array);
                    if ($extension == "png" || $extension == "jpg" || $extension == "jpeg") {
                  ?>
                      <a style="color: white;" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        File <?= $i++ ?> ||
                      </a>
                      <!-- Modal -->
                      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Attachment</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <img src="https://moc.mlejitoffice.id/upload/task_comment/<?= $xx ?>" alt="attachment" width="100%">
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } else { ?>
                      <li><a style="color: white;" href="https://moc.mlejitoffice.id/<?= $xx ?>" download onclick="PageReload()">
                          File <?= $i++ ?> ||
                        </a></li>
                  <?php }
                  } ?>
                </b>
              <?php } ?>
            </div>
            <div class="clearfix"></div>
          <?php } else { ?>
            <div class="speech-bubble speech-right color-black">
              <b><?= $x->nama ?>:</b> <br>
              <?= $x->comment_member ?>
              <?php if ($x->attachment != null) { ?>
                <hr>
                <b>
                  <?php
                  $i = 1;
                  foreach (explode(';', $x->attachment_name) as $xx) {
                      $array = explode('.', $xx);
                      $extension = end($array);
                      if ($extension == "png" || $extension == "jpg" || $extension == "jpeg") {
                  ?>
                    <a style="color: black;" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      File <?= $i++ ?> ||
                    </a>
                    <!-- Modal -->
                      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Attachment</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <img src="https://moc.mlejitoffice.id/upload/task_comment/<?= $xx ?>" alt="attachment" width="100%">
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                  <?php } else { ?>
                    <a style="color: black;" href="https://moc.mlejitoffice.id/upload/task_comment/<?= $xx ?>" download onclick="PageReload()">
                      File <?= $i++ ?> ||
                    </a>
                  <?php 
                    }
                  } ?>
                </b>
              <?php } ?>
            </div>
            <div class="clearfix"></div>
        <?php }
        } ?>

    </div>
  <?php } ?>
  </div>
</div>
</div>