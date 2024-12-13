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
      <div class="search-box shadow-xl border-0 bg-theme rounded-sm bottom-0">
        <form action="" method="get">
          <i class="fa fa-search"></i>
          <input type="text" class="border-0" placeholder="Fill in the task name you want to search." id="search" name="search" value="<?= strtolower($this->input->get('search') ?? '') ?>">
        </form>
      </div>
    </div>
    <?php
    //Jika terdapat data task
    if ($task) {
      foreach ($task as $value) {
        $nip = $this->session->userdata('nip');
        $cek_detail = $this->db->get_where('task_detail', ['id_task' => $value->id])->result();
        $cek_num = $this->db->get_where('task_detail', ['id_task' => $value->id])->num_rows();
        if ($cek_num == true) {
          foreach ($cek_detail as $k) {
            if ($k->due_date > date('Y-m-d')) {
              $cek_task = 1;
            } else {
              $cek_task = 0;
            }
          }
        } else {
          $cek_task = 0;
        }
    ?>
        <div class="card card-style">
          <div class="content">
            <div class="text-end">
              <?php if ($value->pic == $this->session->userdata('nip')) { ?>
                <a href="<?= base_url('task/create_task/' . $value->id) ?>" class="badge gradient-green" style="background-color: black;color:white;"><i class="fa fa-pencil"></i> Update</a>
              <?php } ?>
            </div>

            <?= preg_match("/$nip/i", $value->read) ? "" : "<span class='badge gradient-yellow color-white'>New</span>"; ?>
            <?php
            if ($value->activity == '1' && $cek_task == 1) {
              echo "<span class='badge gradient-blue color-white'>Open</span>";
            } else if ($value->activity == '3') {
              echo "<span class='badge gradient-brown color-white'>Closed</span>";
            } else {
              echo "<span class='badge gradient-red color-white'>Over Due</span>";
            }
            ?>

            <div class="text-start">
              <p class="mb-0 my-2" style="font-weight: <?= preg_match("/$nip/i", $value->read) ? '' : 'bolder' ?>; cursor:pointer" onclick="location.href='<?= base_url('task/task_view/' . $value->id) ?>'">
                <?php
                $pic = $this->db->get_where('users', ['nip' => $value->pic])->row_array();
                ?>
                PIC : <?= $pic['nama'] ?>
              <p class="font-10 mb-0 opacity-80"><?= date('d/m/y', strtotime($value->date_created)) ?></p>
            </div>
            <div class="d-flex">
              <div class="flex-grow-1">
                <p class="mb-n1" style="font-weight: <?= preg_match("/$nip/i", $value->read) ? '' : 'bolder' ?>; cursor:pointer" onclick="location.href='<?= base_url('task/task_view/' . $value->id) ?>'"><?= $value->name ?></p>
              </div>
            </div>
          </div>
        </div>
      <?php }
      // Jika data tidak ada atau tidak ditemukan
    } else { ?>
      <div class="card card-style">
        <div class="content">
          <h5 class="text-center">No result</h5>
        </div>
      </div>
    <?php } ?>

    <!-- Pagination -->
    <div class="content">
      <div class="row">
        <div class="col-12 font-15">
          <nav>
            <?= $pagination ?>
          </nav>
        </div>
      </div>
    </div>

    <!-- Button Create -->
    <a href="<?= base_url('task/create_task') ?>" class="btn" id="btn-create"><i class="fa-solid fa-plus"></i></a>
  </div>
</div>