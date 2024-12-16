<div id="page">
    <?php include 'v_nav.php' ?>
    <div class="page-content">
        <div class="card card-style shadow-xl">
            <div class="content">
                <h1 class="font-24 font-700 mb-2">
                    Welcome, <?= $user->nama ?>
                </h1>
                <p class="mb-1 font-20">
                    <?= $user->nama_jabatan ?>
                </p>
            </div>
        </div>

        <?php if ($this->session->flashdata('msg')) {
            echo $this->session->flashdata('msg');
        }
        ?>

        <div class="card card-style">
            <div class="content">
                <h5 class="font-14 opacity-50">Notifications</h5>
                <div class="divider mb-3"></div>

                <div class="list-group list-custom-small list-menu ms-0 me-2">
                    <a href="<?= base_url('app/inbox') ?>" class="menu-active">
                        <i class="fa fa-envelope gradient-blue color-white"></i>
                        <span>New Memo</span>
                        <span class="badge gradient-blue color-white"><?= $memo['jumlah_memo'] ?></span>
                        <i class="fa fa-angle-right"></i>
                    </a>
                    <a href="<?= base_url('task/task') ?>">
                        <img src="<?= base_url('assets/images/tello.png') ?>" alt="">
                        <span>Task</span>
                        <span class="badge gradient-red color-white"><?= $task['jumlah_task'] ?></span>
                        <i class="fa fa-angle-right"></i>
                    </a>
                    <a href="<?= base_url('absensi/absen_wfa') ?>">
                        <!-- <img src="<?= base_url('assets/images/tello.png') ?>" alt=""> -->
                        <i class="gradient-blue color-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-bounding-box" viewBox="0 0 16 16">
                                <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5" />
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            </svg>
                        </i>
                        <span>Absen WFA</span>
                        <!-- <span class="badge gradient-red color-white"><?= $task['jumlah_task'] ?></span> -->
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>

            </div>
        </div>
        <a href="#" data-toggle-theme>
            <div class="card card-style">
                <div class="d-flex pt-3 mt-1 mb-2 pb-2">
                    <div class="align-self-center">
                        <i class="color-icon-gray color-gray-dark font-30 icon-40 text-center fa fa-moon ms-3 show-on-theme-light"></i>
                        <i class="color-icon-yellow color-yellow-dark font-30 icon-40 text-center fa fa-sun ms-3 show-on-theme-dark"></i>
                    </div>
                    <div class="align-self-center">
                        <p class="ps-2 ms-1 color-highlight font-500 mb-n1 mt-n2">
                            Tap to Enable
                        </p>
                        <h4 class="show-on-theme-light ps-2 ms-1 mb-0">Dark Mode</h4>
                        <h4 class="show-on-theme-dark ps-2 ms-1 mb-0">Light Mode</h4>
                    </div>
                    <div class="ms-auto align-self-center mt-n2">
                        <div class="custom-control small-switch ios-switch me-3 mt-n2">
                            <input data-toggle-theme type="checkbox" class="ios-input" id="toggle-dark-home" />
                            <label class="custom-control-label" for="toggle-dark-home"></label>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>