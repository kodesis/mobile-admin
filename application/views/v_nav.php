<div class="header header-auto-show header-fixed header-logo-center">
    <a href="<?= base_url() ?>" class="header-title"><img src="<?= base_url() ?>assets/images/kodesis_kotak.png" alt="logo" width="50px" id="kodesis_kotak"></a>
    <?php if (!$this->uri->segment(2)) { ?>
        <a href="#" data-menu="menu-main" class="header-icon header-icon-1"><i class="fas fa-bars"></i></a>
    <?php } else { ?>
        <a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-chevron-left"></i></a>
    <?php } ?>
    <a href="#" data-toggle-theme class="header-icon header-icon-4 show-on-theme-dark"><i class="fas fa-sun"></i></a>
    <a href="#" data-toggle-theme class="header-icon header-icon-4 show-on-theme-light"><i class="fas fa-moon"></i></a>
</div>

<?php if ($this->uri->segment(2) == 'task_view' && $this->uri->segment(4)) {  ?>
    <form action="<?= base_url() ?>task/activity_comment" method="post" enctype="multipart/form-data">
        <div id="footer-bar" class="d-flex">
            <div class="me-3 speach-icon">
                <a href="#" data-menu="menu-upload" class="bg-gray-dark ms-2"><i class="fa fa-plus pt-2"></i></a>
            </div>
            <div class="flex-fill speach-input">
                <input type="hidden" name="id_task" value="<?= $this->uri->segment(3) ?>">
                <input type="hidden" name="id_detail" value="<?= $this->uri->segment(4) ?>">
                <textarea name="comment" id="comment" class="form-control" placeholder="Input text here"></textarea>
            </div>
            <div class="ms-3 speach-icon">
                <button type="submit" class="gradient-highlight color-white me-2"><i class="fa fa-arrow-right pt-2"></i></button>
            </div>
        </div>

        <div id="menu-upload" class="menu menu-box-bottom rounded-m" data-menu-height="200" data-menu-effect="menu-over">
            <div class="list-group list-custom-small ps-2 me-4">
                <label for="file">Upload File</label>
                <input type="file" class="form-control" name="file[]" id="file" multiple>
            </div>
        </div>
    </form>
<?php } else { ?>
    <div id="footer-bar" class="footer-bar-6">
        <a href="#" data-menu="menu-main"><i class="fa fa-bars"></i><span>Menu</span></a>
        <a href="<?= base_url('app/inbox') ?>" class="<?= $this->uri->segment(1) == 'app' ? 'circle-nav active-nav' : '' ?>"><i class="fa-solid fa-inbox"></i><span>Inbox</span></a>
        <a href="<?= base_url('home') ?>" class="<?= $this->uri->segment(1) == 'home' ? 'circle-nav active-nav' : '' ?>"><i class="fa fa-home"></i><span>Welcome</span></a>
        <a href="<?= base_url('task/task') ?>" class="<?= $this->uri->segment(1) == 'task' ? 'circle-nav active-nav' : '' ?>"><i class="fa-solid fa-bars-progress"></i><span>Task</span></a>
        <a href="<?= base_url('auth/logout') ?>" id="btn-logout"><i class="fa-solid fa-power-off"></i><span>Logout</span></a>
    </div>
<?php } ?>

<div class="page-title page-title-fixed">
    <h1><img src="<?= base_url() ?>assets/images/kodesis_kotak.png" alt="logo" width="50px" id="kodesis_kotak"></h1>
    <a href="#" class="page-title-icon shadow-xl bg-theme color-theme" data-menu="menu-main"><i class="fa fa-bars"></i></a>
    <a href="#" class="page-title-icon shadow-xl bg-theme color-theme show-on-theme-light" data-toggle-theme><i class="fa fa-moon"></i></a>
    <a href="#" class="page-title-icon shadow-xl bg-theme color-theme show-on-theme-dark" data-toggle-theme><i class="fa fa-lightbulb color-yellow-dark"></i></a>
</div>
<div class="page-title-clear"></div>

<!-- Main Menu-->
<div id="menu-main" class="menu menu-box-left rounded-0" data-menu-width="280">
    <!-- Menu Memo BOC -->
    <?php
    $a = $this->session->userdata('level');
    if (strpos($a, '40') !== false) {
    ?>
        <h6 class="menu-divider mt-3">BOC</h6>
        <div class="list-group list-custom-small list-menu">
            <a id="nav-welcome" href="<?= base_url('app/create_memo') ?>">
                <!-- <i class="fa fa-solid fa-inbox gradient-blue color-white"></i> -->
                <i class="fa-solid fa-plus gradient-blue color-white"></i>
                <span>Create</span>
                <i class="fa fa-angle-right"></i>
            </a>
            <a id="nav-welcome" href="<?= base_url('app/inbox') ?>">
                <i class="fa fa-solid fa-inbox gradient-blue color-white"></i>
                <span>Inbox</span>
                <i class="fa fa-angle-right"></i>
            </a>
            <a id="nav-welcome" href="<?= base_url('app/outbox') ?>">
                <i class="gradient-blue">
                    <svg fill="#ffff" height="20" viewBox="0 0 32 32" width="20" xmlns="http://www.w3.org/2000/svg">
                        <g stroke="#ffffff" stroke-linejoin="round" stroke-width="2">
                            <path d="m5 16h5.5s1 3.5 5.5 3.5 5.5-3.5 5.5-3.5h5.5v8c0 1.5-1.5 3-3 3h-16c-1.5 0-3-1.5-3-3z" stroke-miterlimit="4.62" />
                            <path d="m27 19.5v-3.5l-2.5-6h-2m-17.5 9.5v-3.5l2.5-6h2m6.5 5v-11m4 3-4-4-4 4" stroke-linecap="round" />
                        </g>
                    </svg>
                </i>
                <span>Outbox</span>
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    <?php } ?>

    <!-- Menu Task -->
    <?php
    $a = $this->session->userdata('level');
    if (strpos($a, '60') !== false) { ?>
        <h6 class="menu-divider mt-3">Tello</h6>
        <div class="list-group list-custom-small list-menu">
            <a id="nav-welcome" href="<?= base_url('task/task') ?>">
                <i class="fa-solid fa-list gradient-blue color-white"></i>
                <span>Task List</span>
                <i class="fa fa-angle-right"></i>
            </a>
            <a id="nav-welcome" href="<?= base_url('task/create_task') ?>">
                <i class="fa-solid fa-plus gradient-blue color-white"></i>
                <span>Create</span>
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    <?php } ?>

    <!-- Menu Task -->
    <?php
    $a = $this->session->userdata('level');
    if (strpos($a, '60') !== false) { ?>
        <h6 class="menu-divider mt-3">Absensi</h6>
        <div class="list-group list-custom-small list-menu">
            <a id="nav-welcome" href="<?= base_url('absensi/absen_wfa') ?>">
                <i class="fa-solid fa-list gradient-blue color-white"></i>
                <span>Absensi WFA</span>
                <i class="fa fa-angle-right"></i>
            </a>
            <a id="nav-welcome" href="<?= base_url('absensi/user_photo') ?>">
                <i class="fa-solid fa-list gradient-blue color-white"></i>
                <span>Data Photo</span>
                <i class="fa fa-angle-right"></i>
            </a>
            <!-- <a id="nav-welcome" href="<?= base_url('absensi/user') ?>">
                <i class="fa-solid fa-plus gradient-blue color-white"></i>
                <span>User List</span>
                <i class="fa fa-angle-right"></i>
            </a> -->
        </div>
    <?php } ?>

    <h6 class="menu-divider mt-3">Setting</h6>
    <div class="list-group list-custom-small list-menu">
        <a id="nav-welcome" href="<?= base_url('auth/password') ?>">
            <i class="fa-solid fa-key gradient-blue color-white"></i>
            <span>Change Password</span>
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>