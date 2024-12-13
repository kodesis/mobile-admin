<div id="page">
    <?php include APPPATH . '/views/v_nav.php' ?>
    <div class="page-content">
        <div class="card card-style">
            <div class="content mb-0">
                <h3>Create New Digital Memo</h3>
                <form action="<?= base_url('app/simpan_memo') ?>" class="my-3" enctype="multipart/form-data" id="form-memo" method="post">
                    <div class="has-borders mb-2">
                        <label for="kepada" class="form-label">Send To <em>(required)</em></label>
                        <?php if (!empty($this->uri->segment(4))) { ?>
                            <div class="">
                                <?php if (!empty($memo->nip_kpd)) { ?>
                                    <select class="form-control js-example-basic-multiple" name="tujuan_memo[]" id="tujuan_memo" multiple="multiple">
                                        <?php foreach ($sendto as $data) : ?>
                                            <?php
                                            if (($data->nip == $memo->nip_dari)) { ?>
                                                <option selected="selected" value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?></option>
                                            <?php } ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('tujuan_memo[]') ?>
                                <?php } else { ?>
                                    <select class="form-control js-example-basic-multiple" name="tujuan_memo[]" id="tujuan_memo" multiple="multiple">
                                        <?php foreach ($sendto as $data) : ?>
                                            <option value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('tujuan_memo[]') ?>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <?php if (!empty($memo->nip_kpd)) { ?>
                                <select class="form-control js-example-basic-multiple" name="tujuan_memo[]" id="tujuan_memo" multiple="multiple">
                                    <?php foreach ($sendto as $data) : ?>
                                        <?php
                                        if (($data->nip == $memo->nip_dari)) { ?>
                                            <option selected="selected" value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('tujuan_memo[]') ?>
                            <?php } else { ?>
                                <select class="form-control js-example-basic-multiple" name="tujuan_memo[]" id="tujuan_memo" multiple="multiple">
                                    <?php foreach ($sendto as $data) : ?>
                                        <option value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?> (<?php echo $data->nama_jabatan; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('tujuan_memo[]') ?>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <div class="has-borders mb-2">
                        <label for="cc" class="form-label">CC BOC</label>
                        <?php if (!empty($this->uri->segment(4))) { ?>
                            <select class="form-control js-example-basic-multiple" name="cc_memo[]" id="cc_memo" multiple="multiple">
                                <?php foreach ($sendto as $data) : ?>
                                    <option value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php } else { ?>
                            <?php if (!empty($memo->nip_cc)) { ?>
                                <select class="form-control js-example-basic-multiple" name="cc_memo[]" id="cc_memo" multiple="multiple">
                                    <?php foreach ($sendto as $data) : ?>
                                        <?php if (strpos($memo->nip_cc, $data->nip) !== false) {
                                            if ($data->nip <> $this->session->userdata('nip')) { ?>
                                                <option selected="selected" value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?></option>
                                            <?php } ?>
                                            <?php } elseif (strpos($memo->nip_kpd, $data->nip) !== false) {
                                            if ($data->nip <> $this->session->userdata('nip')) { ?>
                                                <option selected="selected" value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?></option>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <option value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            <?php } else if (empty($memo->nip_cc) && $this->uri->segment(3)) { ?>
                                <select class="form-control js-example-basic-multiple" name="cc_memo[]" id="cc_memo" multiple="multiple">
                                    <?php foreach ($sendto as $data) : ?>
                                        <?php if (strpos($memo->nip_kpd, $data->nip) !== false) {
                                            if ($data->nip <> $this->session->userdata('nip')) {
                                        ?>
                                                <option selected="selected" value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?></option>
                                        <?php }
                                        } ?>
                                        <option value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?> (<?php echo $data->nama_jabatan; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            <?php } else { ?>
                                <select class="form-control js-example-basic-multiple" name="cc_memo[]" id="cc_memo" multiple="multiple">
                                    <?php foreach ($sendto as $data) : ?>
                                        <option value="<?php echo $data->nip; ?>"><?php echo $data->nama; ?> (<?php echo $data->nama_jabatan; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <div class="has-borders no-icon mb-2">
                        <label for="judul" class="form-label">Subject E-Memo <em>(required)</em></label>
                        <input type="text" class="form-control" id="subject_memo" name="subject_memo" value="<?= !empty($memo->judul) ? $memo->judul : set_value('subject_memo') ?>">
                        <?= form_error('subject_memo') ?>
                    </div>

                    <div class="has-borders no-icon mb-2">
                        <label for="judul" class="form-label">Attachment</label>
                        <?php if (!empty($memo->attach_name)) { ?>
                            <input id="attch_exist" class="form-control" data-validate-length-range="6" data-validate-words="1" name="attch_exist" type="text" value="<?php echo $memo->attach_name; ?>" readonly>
                            <input id="attch_exist_nm" class="form-control" data-validate-length-range="6" data-validate-words="1" name="attch_exist_nm" type="hidden" value="<?php echo $memo->attach; ?>">
                            <input type="file" name="file[]" id="file" class="m" multiple>
                        <?php } else { ?>
                            <input type="file" name="file[]" id="file" class="form-control" multiple>
                        <?php } ?>
                    </div>

                    <div class="has-borders no-icon mb-2">
                        <label for="isi_memo" class="form-label">Contents E-Memo <em>(required)</em></label>
                        <textarea class="form-control" name="isi_memo" id="isi_memo"><?= set_value('isi_memo') ?>
                        <?php
                        if ($this->uri->segment(3) == true) {
                            $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
                            $bln = $array_bln[date('n', strtotime($memo->tanggal))];
                        }
                        if (!empty($memo->isi_memo)) {
                            echo ('<br><hr/>');
                            echo ('<br> created by. ');
                            $nip = $memo->nip_dari;

                            $query = $this->db->query("SELECT nama,nama_jabatan FROM users WHERE nip='$nip';")->row()->nama;
                            echo $query;
                            if ($this->uri->segment(3) == true) {
                                echo "<br>";
                                echo "No Memo : " . sprintf("%03d", $memo->nomor_memo) . '/E-MEMO/' . $memo->kode_nama . '/' . $bln . '/' . date('Y', strtotime($memo->tanggal));
                            }
                            echo $memo->isi_memo;
                        }

                        echo set_value('isi_memo');
                        ?>
                    </textarea>
                        <?= form_error('isi_memo') ?>
                    </div>
                    <div class="mb-2">
                        <p class="text-danger">(*) Make sure the memo is made correctly before the memo is sent.</p>
                    </div>
                    <div>
                        <a href="<?= base_url('app/inbox') ?>" class="btn btn-warning">Back</a>
                        <button type="submit" class="btn btn-success" id="send-memo">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>