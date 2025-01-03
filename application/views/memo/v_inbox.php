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
            <h3 class="text-center my-3">INBOX</h3>
            <div class="search-box shadow-xl border-0 bg-theme rounded-sm bottom-0">
                <form action="" method="get">
                    <i class="fa fa-search"></i>
                    <input type="text" class="border-0" placeholder="Fill in the subject you want to search." id="search" name="search" value="<?= strtolower($this->input->get('search') ?? '') ?>">
                </form>
            </div>
        </div>
        <?php
        //Jika terdapat data inbox
        if ($inbox) {
            foreach ($inbox as $value) {
                $nip = $this->session->userdata('nip');
        ?>
                <div class="card card-style">
                    <div class="content" style="cursor: pointer;" onclick="location.href='<?= base_url('app/memo_view/' . $value->id) ?>'">
                        <?= preg_match("/$nip/i", $value->read) ? "" : "<span class='badge gradient-red color-white'>New</span>" ?>
                        <div class="text-start">
                            <p class="mb-0 my-2" style="font-weight: <?= preg_match("/$nip/i", $value->read) ? '' : 'bolder' ?>;"><?= $value->nama ?></p>
                            <p class="font-10 mb-0 opacity-80"><?= date('d/m/y | H:i:s', strtotime($value->tanggal)) ?></p>
                        </div>
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="mb-n1" style="font-weight: <?= preg_match("/$nip/i", $value->read) ? '' : 'bolder' ?>;"><?= $value->judul ?></p>
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
        <a href="<?= base_url('app/create_memo') ?>" class="btn" id="btn-create"><i class="fa-solid fa-plus"></i></a>
    </div>
</div>